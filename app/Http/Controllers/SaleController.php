<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $paymentMethod = $request->input('payment_method');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $currentUser = Auth::user();

        $sales = Sale::with(['user', 'items.product'])
            ->when($currentUser->isCustomer(), function ($query) use ($currentUser) {
                return $query->where(function ($q) use ($currentUser) {
                    $q->where('customer_name', 'like', "%{$currentUser->name}%")
                      ->orWhere('customer_name', 'like', "%{$currentUser->username}%")
                      ->when($currentUser->phone, function ($sq) use ($currentUser) {
                          return $sq->orWhere('customer_phone', $currentUser->phone);
                      });
                });
            })
            ->when($search, function ($query, $search) {
                return $query->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('sale_date', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('sale_date', '<=', $endDate);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', compact('sales', 'search', 'status', 'paymentMethod', 'startDate', 'endDate'));
    }

    public function create()
    {
        // Only products with stock > 0 are eligible for sale
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $customers = \App\Models\User::where('role', 'customer')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $todayStr = date('Ymd');
        $countToday = Sale::whereDate('created_at', Carbon::today())->count() + 1;
        $defaultInvoice = 'INV-' . $todayStr . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        return view('sales.create', compact('products', 'customers', 'defaultInvoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'sale_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,transfer,tempo'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ], [
            'customer_name.required' => 'Nama pelanggan/proyek wajib diisi.',
            'items.required' => 'Minimal satu produk harus dipilih dalam penjualan.',
            'items.min' => 'Minimal satu item produk diperlukan.',
            'items.*.quantity.min' => 'Kuantitas penjualan harus minimal 1.',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                // 1. Group and validate duplicate product selections in the same form
                $aggregatedQuantities = [];
                foreach ($request->items as $itemInput) {
                    $pid = $itemInput['product_id'];
                    $qty = (int) $itemInput['quantity'];
                    $aggregatedQuantities[$pid] = ($aggregatedQuantities[$pid] ?? 0) + $qty;
                }

                // 2. Lock products and check stock availability strictly
                $calculatedTotal = 0;
                $itemsData = [];

                foreach ($request->items as $itemInput) {
                    $pid = $itemInput['product_id'];
                    $qty = (int) $itemInput['quantity'];
                    $price = (float) $itemInput['unit_price'];

                    // Lock row for concurrency safety
                    $product = Product::lockForUpdate()->findOrFail($pid);

                    // Check total requested quantity for this product against available stock
                    $totalRequested = $aggregatedQuantities[$pid];
                    if ($product->stock < $totalRequested) {
                        throw new Exception("Stok untuk produk '{$product->name}' tidak mencukupi. Tersedia: {$product->stock} {$product->unit}, Permintaan: {$totalRequested} {$product->unit}. Transaksi ditolak.");
                    }

                    $subtotal = $qty * $price;
                    $calculatedTotal += $subtotal;

                    $itemsData[] = [
                        'product_id' => $pid,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }

                // Calculate payment & change
                $paidAmount = $request->filled('paid_amount') ? (float) $request->paid_amount : $calculatedTotal;
                $changeAmount = max(0, $paidAmount - $calculatedTotal);

                // Sequential invoice number
                $todayStr = date('Ymd');
                $lastSale = Sale::where('invoice_no', 'like', "INV-{$todayStr}-%")
                    ->orderBy('id', 'desc')
                    ->lockForUpdate()
                    ->first();

                $seq = 1;
                if ($lastSale) {
                    $parts = explode('-', $lastSale->invoice_no);
                    $seq = (int) end($parts) + 1;
                }
                $invoiceNo = 'INV-' . $todayStr . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

                // Create Sale header
                $sale = Sale::create([
                    'invoice_no' => $invoiceNo,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'user_id' => Auth::id(),
                    'sale_date' => $request->sale_date,
                    'status' => 'completed',
                    'total_amount' => $calculatedTotal,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                ]);

                // Create items & deduct stock atomically
                foreach ($itemsData as $data) {
                    $sale->items()->create($data);

                    $product = Product::lockForUpdate()->findOrFail($data['product_id']);
                    $product->stock -= $data['quantity'];
                    $product->save();
                }

                return $sale;
            });

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Transaksi penjualan ' . $sale->invoice_no . ' berhasil disimpan. Stok produk telah dikurangi.');

        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->isCustomer()) {
            $matchesName = stripos($sale->customer_name, $currentUser->name) !== false || stripos($sale->customer_name, $currentUser->username) !== false;
            $matchesPhone = $currentUser->phone && $sale->customer_phone === $currentUser->phone;
            if (!$matchesName && !$matchesPhone) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat faktur pesanan ini.');
            }
        }

        $sale->load(['user', 'cancelledByUser', 'items.product.category']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Cancel Sale (Admin only, as per business rules)
     * Restores stock atomically to products
     */
    public function cancel(Request $request, Sale $sale)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Hanya Admin/Owner yang dapat membatalkan transaksi.');
        }

        if ($sale->isCancelled()) {
            return back()->with('error', 'Transaksi penjualan ini sudah dalam status dibatalkan.');
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:255'],
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan transaksi wajib diisi.',
        ]);

        try {
            DB::transaction(function () use ($sale, $request) {
                // Restore stock for all sold items
                foreach ($sale->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    $product->stock += $item->quantity;
                    $product->save();
                }

                $sale->update([
                    'status' => 'cancelled',
                    'cancelled_at' => Carbon::now(),
                    'cancelled_by' => Auth::id(),
                    'cancellation_reason' => $request->cancellation_reason,
                ]);
            });

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Transaksi penjualan ' . $sale->invoice_no . ' berhasil dibatalkan dan stok produk telah dipulihkan.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
