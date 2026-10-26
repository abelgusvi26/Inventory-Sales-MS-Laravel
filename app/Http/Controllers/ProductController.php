<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $stockStatus = $request->input('stock_status'); // all, low, out, available

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('sku', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('model_series', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($stockStatus === 'low', function ($query) {
                return $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            })
            ->when($stockStatus === 'out', function ($query) {
                return $query->where('stock', '<=', 0);
            })
            ->when($stockStatus === 'available', function ($query) {
                return $query->whereColumn('stock', '>', 'min_stock');
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'stockStatus'));
    }

    public function lowStock(Request $request)
    {
        $products = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('products.low_stock', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:200'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model_series' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ], [
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'purchase_price.min' => 'Harga beli tidak boleh negatif.',
            'selling_price.min' => 'Harga jual tidak boleh negatif.',
            'stock.min' => 'Stok awal tidak boleh bernilai negatif.',
            'min_stock.min' => 'Batas minimum stok tidak boleh negatif.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Produk baru ' . $product->name . ' berhasil didaftarkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'purchaseItems.purchase.supplier', 'saleItems.sale.user']);

        // Recent purchase records for this product
        $recentPurchases = $product->purchaseItems()
            ->with('purchase.supplier')
            ->latest('id')
            ->limit(5)
            ->get();

        // Recent sales records for this product
        $recentSales = $product->saleItems()
            ->with('sale.user')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('products.show', compact('product', 'recentPurchases', 'recentSales'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['required', 'string', 'max:200'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model_series' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ], [
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika tersimpan di disk public
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        // Note: Stock is modified exclusively via purchase stock-in or sale stock-out/cancellation!
        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Business Rule: Produk yang sudah dipakai dalam transaksi tidak dihapus secara sembarangan
        if ($product->hasTransactions()) {
            return back()->with('error', 'Produk ini tidak dapat dihapus permanen karena memiliki riwayat transaksi pembelian atau penjualan. Sistem hanya memperbolehkan arsip/soft-delete.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dinonaktifkan/dihapus (soft delete).');
    }
}
