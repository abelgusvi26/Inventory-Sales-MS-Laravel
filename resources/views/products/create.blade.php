@extends('layouts.app')

@section('title', 'Tambah Suku Cadang Motor Baru')
@section('page_title', 'Registrasi Suku Cadang Sepeda Motor')

@section('topbar_actions')
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-motorcycle text-primary"></i>
            Formulir Tambah Suku Cadang Motor
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="sku">Kode SKU / Part Number <span class="required">*</span></label>
                    <input type="text" id="sku" name="sku" class="form-control font-mono @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Contoh: CVT-VBLT-BEAT / 23100-K44-V01" required>
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Kategori Komponen</label>
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
                <label class="form-label" for="name">Nama Lengkap Suku Cadang <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: V-Belt & Roller Kit Beat FI / ESP AHM" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="brand">Merek / Pabrikan</label>
                    <input type="text" id="brand" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="AHM / YGP / Daytona / RCB / Motul">
                    @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="model_series">Kompatibel Sepeda Motor</label>
                    <input type="text" id="model_series" name="model_series" class="form-control @error('model_series') is-invalid @enderror" value="{{ old('model_series') }}" placeholder="Beat / Vario 160 / NMAX 155 / Scoopy">
                    @error('model_series')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="unit">Satuan (Unit) <span class="required">*</span></label>
                    <input type="text" id="unit" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', 'pcs') }}" placeholder="pcs / set / botol / pasang" required>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Upload Gambar Suku Cadang -->
            <div class="form-group" style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px dashed var(--border);">
                <label class="form-label" for="image" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-image text-primary"></i> Foto / Gambar Suku Cadang Motor (Opsional)
                </label>
                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 4px;">
                    Format file didukung: JPG, PNG, WEBP, SVG. Maksimal ukuran: 2 MB.
                </span>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="purchase_price">Harga Beli Standar / Kulakan (Rp) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" id="purchase_price" name="purchase_price" class="form-control font-mono @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price', 0) }}" required>
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="selling_price">Harga Jual Eceran Kasir (Rp) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" id="selling_price" name="selling_price" class="form-control font-mono @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', 0) }}" required>
                    @error('selling_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="stock">Stok Awal Fisik <span class="required">*</span></label>
                    <input type="number" min="0" id="stock" name="stock" class="form-control font-mono @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="min_stock">Batas Minimum Peringatan (Min Stock) <span class="required">*</span></label>
                    <input type="number" min="0" id="min_stock" name="min_stock" class="form-control font-mono @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', 5) }}" required>
                    @error('min_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">Lokasi Rak Bengkel / Etalase</label>
                    <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="Rak A-01 / Etalase Depan">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Keterangan / Spesifikasi Teknis Suku Cadang</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Kesesuaian tahun motor, material bahan, kelengkapan isi dus...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px;">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Suku Cadang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
