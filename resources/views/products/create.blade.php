@extends('layouts.app')

@section('title', 'Tambah Produk Baru')
@section('page_title', 'Registrasi Produk & Suku Cadang')

@section('topbar_actions')
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-box-open"></i>
            Formulir Tambah Produk Alat Berat
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="sku">Kode SKU Produk <span class="required">*</span></label>
                    <input type="text" id="sku" name="sku" class="form-control font-mono @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Contoh: GET-BT-PC200" required>
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Kategori Produk</label>
                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                <label class="form-label" for="name">Nama Lengkap Produk / Suku Cadang <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Bucket Tooth PC200 Tiger Type" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="brand">Merek / Pabrikan</label>
                    <input type="text" id="brand" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="Komatsu / CAT / Berco">
                    @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="model_series">Model / Seri Mesin</label>
                    <input type="text" id="model_series" name="model_series" class="form-control @error('model_series') is-invalid @enderror" value="{{ old('model_series') }}" placeholder="PC200-8 / CAT 320D">
                    @error('model_series')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="unit">Satuan (Unit) <span class="required">*</span></label>
                    <input type="text" id="unit" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', 'pcs') }}" placeholder="pcs / set / unit / drum" required>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="purchase_price">Harga Beli Satuan (Rp) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" id="purchase_price" name="purchase_price" class="form-control font-mono @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price', 0) }}" required>
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="selling_price">Harga Jual Satuan (Rp) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" id="selling_price" name="selling_price" class="form-control font-mono @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', 0) }}" required>
                    @error('selling_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="stock">Stok Awal <span class="required">*</span></label>
                    <input type="number" min="0" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="min_stock">Batas Minimum Stok (Alert) <span class="required">*</span></label>
                    <input type="number" min="0" id="min_stock" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', 5) }}" required>
                    @error('min_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">Lokasi Rak / Bin Gudang</label>
                    <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="Contoh: Rak A-03">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Keterangan Tambahan / Spesifikasi Teknis</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Catatan dimensi, material, spesifikasi oli/part...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px;">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
