@extends('layouts.app')

@section('title', 'Detail Pembelian ' . $purchase->reference_no)
@section('page_title', 'Rincian Dokumen Pembelian Stok')

@section('topbar_actions')
    <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
@endsection

@section('content')
<div class="card" style="max-width: 950px; margin: 0 auto;">
    <div class="card-header" style="background-color: #fafbfc; padding: 22px 28px;">
        <div>
            <div style="font-size: 0.76rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Purchase Order</div>
            <h2 class="font-mono" style="font-size: 1.45rem; font-weight: 800; color: #d97706; margin-top: 2px;">
                {{ $purchase->reference_no }}
            </h2>
        </div>
        <div>
            @if($purchase->status === 'completed')
                <span class="badge badge-success" style="font-size: 0.85rem; padding: 6px 14px;">
                    <i class="fa-solid fa-circle-check"></i> Transaksi Selesai (Stok Aktif)
                </span>
            @else
                <span class="badge badge-danger" style="font-size: 0.85rem; padding: 6px 14px;">
                    <i class="fa-solid fa-ban"></i> Dibatalkan (Stok Dikembalikan)
                </span>
            @endif
        </div>
    </div>

    @if($purchase->isCancelled())
        <div class="alert alert-danger" style="margin: 20px 28px 0;">
            <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
            <div>
                <strong>Informasi Pembatalan:</strong> Transaksi ini dibatalkan oleh <strong>{{ $purchase->cancelledByUser->name ?? 'Admin' }}</strong> pada {{ $purchase->cancelled_at ? $purchase->cancelled_at->format('d/m/Y H:i') : '-' }}.
                <div style="margin-top: 4px; font-style: italic;">"{{ $purchase->cancellation_reason }}"</div>
            </div>
        </div>
    @endif

    <div class="card-body" style="padding: 28px;">
        <!-- Two column info -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 28px; border-bottom: 1px solid var(--border); padding-bottom: 24px;">
            <div>
                <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Detail Pemasok (Supplier):</h4>
                <div style="font-size: 1.05rem; font-weight: 700; color: #0f172a;">{{ $purchase->supplier->name ?? 'Pemasok Terhapus' }}</div>
                @if($purchase->supplier && $purchase->supplier->company_name)
                    <div style="font-size: 0.86rem; color: #475569;">{{ $purchase->supplier->company_name }}</div>
                @endif
                <div style="font-size: 0.82rem; color: #64748b; margin-top: 6px;">
                    <div><i class="fa-solid fa-phone me-1"></i> {{ $purchase->supplier->phone ?? '-' }}</div>
                    <div><i class="fa-regular fa-envelope me-1"></i> {{ $purchase->supplier->email ?? '-' }}</div>
                    <div><i class="fa-solid fa-location-dot me-1"></i> {{ $purchase->supplier->address ?? '-' }}</div>
                </div>
            </div>

            <div>
                <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Informasi Transaksi:</h4>
                <div style="font-size: 0.85rem; line-height: 1.8;">
                    <div><strong>Tanggal Transaksi:</strong> {{ $purchase->purchase_date->format('d F Y') }}</div>
                    <div><strong>Petugas Penerima:</strong> {{ $purchase->user->name ?? '-' }} ({{ ucfirst($purchase->user->role ?? 'staff') }})</div>
                    <div><strong>Waktu Input Sistem:</strong> {{ $purchase->created_at->format('d/m/Y H:i:s') }}</div>
                    @if($purchase->notes)
                        <div style="margin-top: 6px; padding: 8px 12px; background-color: #f8fafc; border-radius: 6px; border: 1px solid var(--border);">
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Catatan Dokumen:</span>
                            {{ $purchase->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Purchase Items Table -->
        <h4 style="font-size: 0.88rem; text-transform: uppercase; color: #334155; font-weight: 700; margin-bottom: 14px;">
            Rincian Barang & Suku Cadang Diterima
        </h4>
        <div class="table-responsive">
            <table class="table" style="border: 1px solid var(--border);">
                <thead>
                    <tr style="background-color: #f8fafc;">
                        <th>No</th>
                        <th>Kode SKU</th>
                        <th>Nama Produk / Suku Cadang</th>
                        <th>Kategori</th>
                        <th style="text-align: right;">Kuantitas</th>
                        <th style="text-align: right;">Harga Satuan (Rp)</th>
                        <th style="text-align: right;">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td class="font-mono"><strong>{{ $item->product->sku ?? '-' }}</strong></td>
                        <td>
                            <strong>{{ $item->product->name ?? 'Produk Terhapus' }}</strong>
                            @if($item->product && $item->product->brand)
                                <span style="font-size: 0.75rem; color: #64748b;">({{ $item->product->brand }})</span>
                            @endif
                        </td>
                        <td><span class="badge badge-secondary">{{ $item->product->category->name ?? '-' }}</span></td>
                        <td style="text-align: right;" class="font-mono">
                            <strong>{{ $item->quantity }}</strong> {{ $item->product->unit ?? 'pcs' }}
                        </td>
                        <td style="text-align: right;" class="font-mono">
                            Rp {{ number_format($item->unit_cost, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right;" class="font-mono" style="font-weight: 700;">
                            <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #fafbfc;">
                        <td colspan="6" style="text-align: right; font-weight: 800; font-size: 0.95rem; padding: 18px;">
                            TOTAL KESELURUHAN PEMBELIAN:
                        </td>
                        <td style="text-align: right; font-weight: 800; font-size: 1.25rem; color: #0f172a; padding: 18px;" class="font-mono">
                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Cancellation Section (Admin Only & When Completed) -->
        @if(Auth::user()->isAdmin() && $purchase->isCompleted())
            <div style="margin-top: 36px; padding: 22px; background-color: #fffaf0; border: 1px solid #fed7aa; border-radius: var(--radius-md);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    <div>
                        <div style="font-weight: 700; color: #9a3412;">
                            <i class="fa-solid fa-shield-halved me-1"></i> Otoritas Admin: Pembatalan Transaksi Pembelian
                        </div>
                        <p style="font-size: 0.8rem; color: #7c2d12; margin-top: 4px;">
                            Membatalkan transaksi ini akan otomatis <strong>mengurangi kembali stok</strong> produk yang telah di-stock in. Sistem akan menolak jika stok produk telah terjual dan tidak mencukupi.
                        </p>
                    </div>

                    <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin membatalkan transaksi pembelian ini? Stok gudang akan otomatis dikurangi kembali.');" style="display: flex; gap: 10px; align-items: center;">
                        @csrf
                        <input type="text" name="cancellation_reason" class="form-control form-control-sm" placeholder="Alasan pembatalan..." required style="min-width: 250px;">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-ban"></i> Batalkan PO
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
