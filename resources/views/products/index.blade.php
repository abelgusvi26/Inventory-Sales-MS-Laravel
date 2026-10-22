@extends('layouts.app')

@section('title', 'Katalog Produk Alat Berat')
@section('page_title', 'Master Data Produk & Suku Cadang')

@section('topbar_actions')
    @if(!Auth::user()->isCustomer())
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
    @endif
@endsection

@section('content')
<!-- Filter & Search Bar -->
<div class="filter-card">
    <form action="{{ route('products.index') }}" method="GET" class="filter-grid">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Pencarian</label>
            <input type="text" name="search" class="form-control" placeholder="Cari SKU, Nama, Brand, Seri..." value="{{ $search }}">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Status Ketersediaan</label>
            <select name="stock_status" class="form-select">
                <option value="">-- Semua Status Stok --</option>
                <option value="available" {{ $stockStatus == 'available' ? 'selected' : '' }}>Stok Aman (> Min)</option>
                <option value="low" {{ $stockStatus == 'low' ? 'selected' : '' }}>Stok Rendah (<= Min)</option>
                <option value="out" {{ $stockStatus == 'out' ? 'selected' : '' }}>Habis (0)</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-filter"></i> Terapkan
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary" title="Reset Filter">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- Product Table Card -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-boxes-stacked"></i>
            Daftar Produk Terdaftar ({{ $products->total() }})
        </div>
        <span style="font-size: 0.8rem; color: var(--text-muted);">
            Menampilkan halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU & Merek</th>
                    <th>Nama Produk & Seri</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok / Batas</th>
                    <th>Lokasi Rak</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="font-mono" style="font-weight: 700; color: #0284c7; text-decoration: none;">
                            {{ $product->sku }}
                        </a>
                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">
                            {{ $product->brand ?? 'OEM Universal' }}
                        </div>
                    </td>
                    <td>
                        <strong style="color: #0f172a;">{{ $product->name }}</strong>
                        @if($product->model_series)
                            <div style="font-size: 0.75rem; color: #64748b;">
                                Kompatibel: <span class="badge badge-secondary">{{ $product->model_series }}</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $product->category->name ?? '-' }}</span>
                    </td>
                    <td class="font-mono">
                        Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                    </td>
                    <td class="font-mono" style="font-weight: 700; color: #059669;">
                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($product->stock <= 0)
                            <span class="badge badge-danger">HABIS (0 {{ $product->unit }})</span>
                        @elseif($product->isLowStock())
                            <span class="badge badge-warning">{{ $product->stock }} {{ $product->unit }} (Min: {{ $product->min_stock }})</span>
                        @else
                            <span class="badge badge-success">{{ $product->stock }} {{ $product->unit }}</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size: 0.8rem; color: #64748b;">
                            <i class="fa-solid fa-location-dot me-1"></i> {{ $product->location ?? 'Gudang Utama' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 6px;">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary" title="Detail & Riwayat Transaksi">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-secondary" title="Edit Produk">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            @if(Auth::user()->isAdmin())
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Produk">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-magnifying-glass fa-2x" style="color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                        Tidak ada produk yang sesuai dengan kriteria pencarian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
