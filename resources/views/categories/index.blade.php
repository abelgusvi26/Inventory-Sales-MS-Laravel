@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('page_title', 'Master Data Kategori Produk')

@section('topbar_actions')
    <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Kategori
    </a>
@endsection

@section('content')
<div class="filter-card">
    <form action="{{ route('categories.index') }}" method="GET" class="filter-grid" style="grid-template-columns: 2fr 1fr;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Cari Kategori</label>
            <input type="text" name="search" class="form-control" placeholder="Cari kode, nama kategori, deskripsi..." value="{{ $search }}">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-tags"></i>
            Daftar Kategori Produk ({{ $categories->total() }})
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Kategori</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Produk</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td class="font-mono"><strong>{{ $cat->code }}</strong></td>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td style="color: #64748b; font-size: 0.84rem;">{{ $cat->description ?? '-' }}</td>
                    <td>
                        <a href="{{ route('products.index', ['category_id' => $cat->id]) }}" class="badge badge-info" style="text-decoration: none;">
                            {{ $cat->products_count }} Produk Terhubung
                        </a>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-secondary" title="Edit Kategori">
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </a>
                        @if(Auth::user()->isAdmin())
                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Kategori">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">
                        Belum ada kategori produk terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
