@extends('layouts.app')

@section('title', 'Edit Suku Cadang ' . $product->name)
@section('page_title', 'Perbarui Data Suku Cadang Motor')

@section('topbar_actions')
    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-motorcycle text-primary"></i>
            Edit Suku Cadang: <span class="font-mono" style="color: #0284c7;">{{ $product->sku }}</span>
        </div>
        <span class="badge badge-info">Stok Saat Ini: {{ $product->stock }} {{ $product->unit }}</span>
    </div>
    <div class="card-body">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="sku">Kode SKU Produk <span class="required">*</span></label>
                    <input type="text" id="sku" name="sku" class="form-control font-mono @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Kategori Komponen</label>
                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap Suku Cadang <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="brand">Merek / Pabrikan</label>
                    <input type="text" id="brand" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand', $product->brand) }}">
                    @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="model_series">Kompatibel Sepeda Motor</label>
                    <input type="text" id="model_series" name="model_series" class="form-control @error('model_series') is-invalid @enderror" value="{{ old('model_series', $product->model_series) }}">
                    @error('model_series')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="unit">Satuan (Unit) <span class="required">*</span></label>
                    <input type="text" id="unit" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $product->unit) }}" required>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Upload & Pratinjau Gambar -->
            <div class="form-group" style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px dashed var(--border);">
                <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-image text-primary"></i> Foto / Gambar Suku Cadang Motor
                </label>
                
                <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 12px;">
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" 
                         style="width: 80px; height: 80px; object-fit: contain; border-radius: 8px; background: #0f172a; border: 1px solid var(--border);"
                         onerror="this.src='{{ asset('images/products/placeholder.svg') }}'">
                    <div>
                        <div style="font-size: 0.82rem; font-weight: 600; color: #334155;">Gambar Saat Ini</div>
                        <div style="font-size: 0.74rem; color: var(--text-muted);">Unggah file gambar baru di bawah jika ingin mengganti gambar produk.</div>
                    </div>
                </div>

                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 4px;">
                    Format didukung: JPG, PNG, WEBP, SVG. Maksimal: 2 MB.
                </span>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="purchase_price">Harga Beli Standar / Kulakan (Rp) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" id="purchase_price" name="purchase_price" class="form-control font-mono @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price', $product->purchase_price) }}" required>
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="selling_price">Harga Jual Satuan (Rp) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" id="selling_price" name="selling_price" class="form-control font-mono @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $product->selling_price) }}" required>
                    @error('selling_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="min_stock">Batas Minimum Peringatan (Min Stock) <span class="required">*</span></label>
                    <input type="number" min="0" id="min_stock" name="min_stock" class="form-control font-mono @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', $product->min_stock) }}" required>
                    @error('min_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">Lokasi Rak Bengkel / Etalase</label>
                    <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $product->location) }}">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Keterangan / Spesifikasi Suku Cadang</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="background-color: #f8fafc; border-radius: var(--radius-md); padding: 14px 18px; margin-bottom: 20px; border-left: 4px solid var(--info);">
                <div style="font-size: 0.85rem; color: #475569;">
                    <i class="fa-solid fa-circle-info me-1 text-info"></i>
                    <strong>Catatan Stok:</strong> Jumlah stok produk (saat ini: <strong>{{ $product->stock }} {{ $product->unit }}</strong>) hanya dapat disesuaikan melalui transaksi <strong>Stock-In (Pembelian)</strong> atau <strong>Stock-Out (Penjualan Kasir)</strong> demi menjaga integritas database.
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px;">
                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Perbarui Suku Cadang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
