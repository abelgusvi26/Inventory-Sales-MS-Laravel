@extends('layouts.app')

@section('title', 'Transaksi Pembelian Stok (Purchase)')
@section('page_title', 'Daftar Pembelian Masuk (Stock-In)')

@section('topbar_actions')
    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-cart-plus"></i> Tambah Pembelian (Stock-In)
    </a>
@endsection

@section('content')
<!-- Filter bar -->
<div class="filter-card">
    <form action="{{ route('purchases.index') }}" method="GET" class="filter-grid">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Cari PO / Pemasok</label>
            <input type="text" name="search" class="form-control font-mono" placeholder="No. PO atau nama supplier..." value="{{ $search }}">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Pemasok</label>
            <select name="supplier_id" class="form-select">
                <option value="">-- Semua Pemasok --</option>
                @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ $supplierId == $sup->id ? 'selected' : '' }}>
                        {{ $sup->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai (Aktif)</option>
                <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-dolly"></i>
            Riwayat Pembelian & Stock-In ({{ $purchases->total() }})
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Dokumen PO</th>
                    <th>Tanggal Beli</th>
                    <th>Pemasok / Supplier</th>
                    <th>Petugas Input</th>
                    <th>Item Produk</th>
                    <th>Total Pembelian</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $p)
                <tr>
                    <td>
                        <a href="{{ route('purchases.show', $p) }}" class="font-mono" style="font-weight: 700; color: #d97706; text-decoration: none;">
                            {{ $p->reference_no }}
                        </a>
                    </td>
                    <td>{{ $p->purchase_date->format('d/m/Y') }}</td>
                    <td>
                        <strong style="color: #0f172a;">{{ $p->supplier->name ?? 'Pemasok Terhapus' }}</strong>
                        @if($p->supplier && $p->supplier->company_name)
                            <div style="font-size: 0.75rem; color: #64748b;">{{ $p->supplier->company_name }}</div>
                        @endif
                    </td>
                    <td>
                        <span style="font-size: 0.84rem;">{{ $p->user->name ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $p->items->count() }} Macam Produk</span>
                    </td>
                    <td class="font-mono" style="font-weight: 700; color: #0f172a;">
                        Rp {{ number_format($p->total_amount, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($p->status === 'completed')
                            <span class="badge badge-success">
                                <i class="fa-solid fa-circle-check"></i> Selesai
                            </span>
                        @else
                            <span class="badge badge-danger">
                                <i class="fa-solid fa-ban"></i> Dibatalkan
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('purchases.show', $p) }}" class="btn btn-sm btn-secondary" title="Lihat Detail & Invoice">
                            <i class="fa-regular fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 35px; color: var(--text-muted);">
                        Tidak ditemukan data transaksi pembelian sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($purchases->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
            {{ $purchases->links() }}
        </div>
    @endif
</div>
@endsection
