@extends('layouts.app')

@section('title', Auth::user()->isCustomer() ? 'Riwayat Pesanan Saya' : 'Transaksi Penjualan (Sales)')
@section('page_title', Auth::user()->isCustomer() ? 'Daftar & Riwayat Pemesanan Suku Cadang' : 'Daftar Transaksi Penjualan (Stock-Out)')

@section('topbar_actions')
    @if(Auth::user()->isCustomer())
        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-cart-plus me-1"></i> Buat Pesanan Baru
        </a>
    @else
        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-cart-shopping me-1"></i> Kasir / Transaksi Penjualan Baru
        </a>
    @endif
@endsection

@section('content')
<!-- Filter bar -->
<div class="filter-card">
    <form action="{{ route('sales.index') }}" method="GET" class="filter-grid">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Cari Invoice / Pelanggan</label>
            <input type="text" name="search" class="form-control" placeholder="No. Invoice, nama customer, telepon..." value="{{ $search }}">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Metode Pembayaran</label>
            <select name="payment_method" class="form-select">
                <option value="">-- Semua Metode --</option>
                <option value="cash" {{ $paymentMethod == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                <option value="transfer" {{ $paymentMethod == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="tempo" {{ $paymentMethod == 'tempo' ? 'selected' : '' }}>Tempo / Invoice</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Status Transaksi</label>
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
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
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-receipt"></i>
            Riwayat Penjualan & Faktur Keluar ({{ $sales->total() }})
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Faktur Invoice</th>
                    <th>Tanggal Jual</th>
                    <th>Nama Pelanggan</th>
                    <th>Metode Bayar</th>
                    <th>Petugas Kasir</th>
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $s)
                <tr>
                    <td>
                        <a href="{{ route('sales.show', $s) }}" class="font-mono" style="font-weight: 700; color: #0284c7; text-decoration: none;">
                            {{ $s->invoice_no }}
                        </a>
                    </td>
                    <td>{{ $s->sale_date->format('d/m/Y') }}</td>
                    <td>
                        <strong style="color: #0f172a;">{{ $s->customer_name }}</strong>
                        @if($s->customer_phone)
                            <div style="font-size: 0.75rem; color: #64748b;">
                                <i class="fa-solid fa-phone me-1"></i> {{ $s->customer_phone }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($s->payment_method === 'cash')
                            <span class="badge badge-success">Tunai (Cash)</span>
                        @elseif($s->payment_method === 'transfer')
                            <span class="badge badge-info">Transfer Bank</span>
                        @else
                            <span class="badge badge-warning">Tempo / Kredit</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size: 0.84rem;">{{ $s->user->name ?? '-' }}</span>
                    </td>
                    <td class="font-mono" style="font-weight: 700; color: #059669;">
                        Rp {{ number_format($s->total_amount, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($s->status === 'completed')
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
                        <a href="{{ route('sales.show', $s) }}" class="btn btn-sm btn-secondary" title="Lihat Faktur">
                            <i class="fa-regular fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 35px; color: var(--text-muted);">
                        Tidak ditemukan data transaksi penjualan sesuai filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sales->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end;">
            {{ $sales->links() }}
        </div>
    @endif
</div>
@endsection
