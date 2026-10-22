@extends('layouts.app')

@section('title', 'Pemasok & Distributor')
@section('page_title', 'Master Data Pemasok (Suppliers)')

@section('topbar_actions')
    <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Pemasok
    </a>
@endsection

@section('content')
<div class="filter-card">
    <form action="{{ route('suppliers.index') }}" method="GET" class="filter-grid" style="grid-template-columns: 2fr 1fr;">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Cari Pemasok</label>
            <input type="text" name="search" class="form-control" placeholder="Cari nama PT, sales, email, nomor telepon..." value="{{ $search }}">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-truck-ramp-box"></i>
            Daftar Mitra Pemasok Terdaftar ({{ $suppliers->total() }})
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kontak & Perusahaan</th>
                    <th>Kontak / Telp</th>
                    <th>Alamat</th>
                    <th>Total Transaksi PO</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $sup)
                <tr>
                    <td class="font-mono"><strong>{{ $sup->code }}</strong></td>
                    <td>
                        <strong style="color: #0f172a;">{{ $sup->name }}</strong>
                        @if($sup->company_name)
                            <div style="font-size: 0.78rem; color: #64748b;">
                                <i class="fa-regular fa-building me-1"></i> {{ $sup->company_name }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 0.82rem;">
                            <i class="fa-solid fa-phone me-1 text-muted"></i> {{ $sup->phone ?? '-' }}
                        </div>
                        @if($sup->email)
                            <div style="font-size: 0.76rem; color: #64748b;">
                                <i class="fa-regular fa-envelope me-1"></i> {{ $sup->email }}
                            </div>
                        @endif
                    </td>
                    <td style="color: #475569; font-size: 0.82rem; max-width: 250px;">
                        {{ $sup->address ?? '-' }}
                    </td>
                    <td>
                        <a href="{{ route('purchases.index', ['supplier_id' => $sup->id]) }}" class="badge badge-info" style="text-decoration: none;">
                            {{ $sup->purchases_count }} Pembelian
                        </a>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('suppliers.edit', $sup) }}" class="btn btn-sm btn-secondary" title="Edit Pemasok">
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </a>
                        @if(Auth::user()->isAdmin())
                            <form action="{{ route('suppliers.destroy', $sup) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pemasok ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pemasok">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                        Belum ada data pemasok terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
            {{ $suppliers->links() }}
        </div>
    @endif
</div>
@endsection
