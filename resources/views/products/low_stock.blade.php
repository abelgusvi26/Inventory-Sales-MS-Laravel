@extends('layouts.app')

@section('title', 'Peringatan Stok Suku Cadang Kritis')
@section('page_title', 'Suku Cadang Perlu Restock Segera')

@section('topbar_actions')
    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-cart-plus"></i> Buat Pesanan Kulakan (PO)
    </a>
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
    </a>
@endsection

@section('content')
<div class="alert alert-warning">
    <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
    <div>
        <strong>Pemberitahuan Stok Bengkel & Toko:</strong> Daftar di bawah ini memuat suku cadang sepeda motor yang stok fisiknya sudah mencapai atau berada di bawah batas minimum (*safety stock*). Segera order ke distributor resmi (AHM/YGP/Daytona/RCB) untuk mencegah kekosongan stok saat melayani konsumen bengkel.
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title" style="color: #b45309;">
            <i class="fa-solid fa-motorcycle"></i>
            Suku Cadang Motor Kritis ({{ $products->total() }})
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">Foto</th>
                    <th>SKU</th>
                    <th>Nama Suku Cadang & Motor</th>
                    <th>Kategori</th>
                    <th>Sisa Stok</th>
                    <th>Batas Minimum</th>
                    <th>Defisit Kebutuhan</th>
                    <th>Lokasi Rak</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                @php
                    $deficit = max(0, $product->min_stock - $product->stock);
                @endphp
                <tr>
                    <td>
                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" 
                             style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border); background-color: #0f172a;"
                             onerror="this.src='{{ asset('images/products/placeholder.svg') }}'">
                    </td>
                    <td class="font-mono"><strong>{{ $product->sku }}</strong></td>
                    <td>
                        <strong style="color: #0f172a;">{{ $product->name }}</strong>
                        <div style="font-size: 0.75rem; color: #64748b;">
                            {{ $product->brand ?? '-' }} | Motor: <span class="badge badge-secondary" style="font-size: 0.7rem;">{{ $product->model_series ?? 'Universal' }}</span>
                        </div>
                    </td>
                    <td><span class="badge badge-secondary">{{ $product->category->name ?? '-' }}</span></td>
                    <td>
                        @if($product->stock <= 0)
                            <span class="badge badge-danger">HABIS TOTAL (0)</span>
                        @else
                            <span class="badge badge-warning">{{ $product->stock }} {{ $product->unit }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $product->min_stock }}</strong> {{ $product->unit }}</td>
                    <td>
                        <span style="color: var(--danger); font-weight: 700;">
                            +{{ $deficit }} {{ $product->unit }}
                        </span>
                    </td>
                    <td>{{ $product->location ?? 'Etalase Toko' }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary" title="Beli Stok">
                            <i class="fa-solid fa-cart-plus"></i> Restock
                        </a>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary" title="Lihat Detail">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-circle-check fa-2x" style="color: var(--success); margin-bottom: 8px; display: block;"></i>
                        Semua suku cadang motor saat ini dalam batas aman! Tidak ada stok kritis.
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
