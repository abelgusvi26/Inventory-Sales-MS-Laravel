<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $supplierId = $request->input('supplier_id');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $purchases = Purchase::with(['supplier', 'user', 'items.product'])
            ->when($search, function ($query, $search) {
                return $query->where('reference_no', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('company_name', 'like', "%{$search}%");
                    });
            })
            ->when($supplierId, function ($query, $supplierId) {
                return $query->where('supplier_id', $supplierId);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('purchase_date', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('purchase_date', '<=', $endDate);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers', 'search', 'supplierId', 'status', 'startDate', 'endDate'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        // Generate automatic Reference No: PO-YYYYMMDD-XXXX
        $todayStr = date('Ymd');
        $countToday = Purchase::whereDate('created_at', Carbon::today())->count() + 1;
        $defaultReference = 'PO-' . $todayStr . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('suppliers', 'products', 'defaultReference'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ], [
            'supplier_id.required' => 'Silakan pilih supplier.',
            'items.required' => 'Minimal satu produk harus dipilih dalam pembelian.',
            'items.min' => 'Minimal satu item produk diperlukan.',
            'items.*.quantity.min' => 'Kuantitas produk harus minimal 1.',
            'items.*.unit_cost.min' => 'Harga beli satuan tidak boleh bernilai negatif.',
        ]);

        try {
            $purchase = DB::transaction(function () use ($request) {
                // Generate secure sequential reference
                $todayStr = date('Ymd');
                $lastPurchase = Purchase::where('reference_no', 'like', "PO-{$todayStr}-%")
                    ->orderBy('id', 'desc')
                    ->lockForUpdate()
                    ->first();

                $seq = 1;
                if ($lastPurchase) {
                    $parts = explode('-', $lastPurchase->reference_no);
                    $seq = (int) end($parts) + 1;
                }
                $referenceNo = 'PO-' . $todayStr . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

                // Calculate totals server-side (never trust input browser)
                $calculatedTotal = 0;
                $itemsData = [];

                foreach ($request->items as $itemInput) {
                    $qty = (int) $itemInput['quantity'];
                    $cost = (float) $itemInput['unit_cost'];
                    $subtotal = $qty * $cost;
                    $calculatedTotal += $subtotal;

                    $itemsData[] = [
                        'product_id' => $itemInput['product_id'],
                        'quantity' => $qty,
                        'unit_cost' => $cost,
                        'subtotal' => $subtotal,
                    ];
                }

                // Create Purchase header
                $purchase = Purchase::create([
                    'reference_no' => $referenceNo,
                    'supplier_id' => $request->supplier_id,
                    'user_id' => Auth::id(),
                    'purchase_date' => $request->purchase_date,
                    'status' => 'completed',
                    'total_amount' => $calculatedTotal,
                    'notes' => $request->notes,
                ]);

                // Create items & atomically update stock in database
                foreach ($itemsData as $data) {
                    $purchase->items()->create($data);

                    // Atomic increment stock and optionally update last purchase price
                    $product = Product::lockForUpdate()->findOrFail($data['product_id']);
                    $product->stock += $data['quantity'];
                    $product->purchase_price = $data['unit_cost'];
                    $product->save();
                }

                return $purchase;
            });

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Transaksi pembelian ' . $purchase->reference_no . ' berhasil disimpan dan stok gudang telah diperbarui.');

        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pembelian: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'cancelledByUser', 'items.product.category']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Cancel Purchase (Admin only, as per business rules)
     * Restores stock safely by decrementing the added quantity,
     * but prevents cancellation if stock would drop below zero!
     */
    public function cancel(Request $request, Purchase $purchase)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Hanya Admin/Owner yang dapat membatalkan transaksi.');
        }

        if ($purchase->isCancelled()) {
            return back()->with('error', 'Transaksi pembelian ini sudah dalam status dibatalkan.');
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:255'],
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan transaksi wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($purchase, $request) {
                // Verify all products have enough stock to be deducted back
                foreach ($purchase->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    if ($product->stock < $item->quantity) {
                        throw new Exception("Stok produk '{$product->name}' saat ini ({$product->stock}) tidak mencukupi untuk dikurangi kembali sebesar {$item->quantity} pcs karena sebagian telah terjual.");
                    }
                }

                // Deduct stock back
                foreach ($purchase->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    $product->stock -= $item->quantity;
                    $product->save();
                }

                $purchase->update([
                    'status' => 'cancelled',
                    'cancelled_at' => Carbon::now(),
                    'cancelled_by' => Auth::id(),
                    'cancellation_reason' => $request->cancellation_reason,
                ]);
            });

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Transaksi pembelian ' . $purchase->reference_no . ' berhasil dibatalkan dan stok dikembalikan.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
