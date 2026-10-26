@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page_title', 'Tambah Kategori Produk')

@section('topbar_actions')
    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-plus-circle"></i> Form Tambah Kategori
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="code">Kode Kategori <span class="required">*</span></label>
                <input type="text" id="code" name="code" class="form-control font-mono @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: CAT-CVT" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="name">Nama Kategori <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Transmisi CVT & Drivetrain" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Keterangan singkat mengenai kategori...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
