@extends('layouts.app')

@section('title', 'Tambah Pemasok')
@section('page_title', 'Registrasi Pemasok Baru')

@section('topbar_actions')
    <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-truck-moving"></i> Form Tambah Pemasok (Supplier)
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="code">Kode Pemasok <span class="required">*</span></label>
                    <input type="text" id="code" name="code" class="form-control font-mono @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: SUP-AHM" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="name">Nama Sales / PIC Kontak <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Sales AHM Depot / PT Astra Honda Motor" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="company_name">Nama Resmi Perusahaan (PT / CV)</label>
                <input type="text" id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" placeholder="Contoh: PT Astra Honda Motor Parts Depot">
                @error('company_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="sales@supplier.co.id">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="021-xxxxxxx / 0812xxxxxx">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Gudang / Kantor Pemasok</label>
                <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pemasok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
