@extends('layouts.app')

@section('title', 'Faktur Penjualan ' . $sale->invoice_no)
@section('page_title', 'Rincian Faktur Penjualan (Stock-Out)')

@section('topbar_actions')
    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <button onclick="window.print()" class="btn btn-sm btn-primary">
        <i class="fa-solid fa-print"></i> Cetak Faktur
    </button>
@endsection

@section('content')
<div class="card" style="max-width: 950px; margin: 0 auto;">
    <div class="card-header" style="background-color: #fafbfc; padding: 22px 28px;">
        <div>
            <div style="font-size: 0.76rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Sales Invoice / Faktur Penjualan</div>
            <h2 class="font-mono" style="font-size: 1.45rem; font-weight: 800; color: #0284c7; margin-top: 2px;">
                {{ $sale->invoice_no }}
            </h2>
        </div>
        <div>
            @if($sale->status === 'completed')
                <span class="badge badge-success" style="font-size: 0.85rem; padding: 6px 14px;">
                    <i class="fa-solid fa-circle-check"></i> Transaksi Berhasil (Lunas)
                </span>
            @else
                <span class="badge badge-danger" style="font-size: 0.85rem; padding: 6px 14px;">
                    <i class="fa-solid fa-ban"></i> Transaksi Dibatalkan (Stok Dipulihkan)
                </span>
            @endif
        </div>
    </div>

    @if($sale->isCancelled())
        <div class="alert alert-danger" style="margin: 20px 28px 0;">
            <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
            <div>
                <strong>Informasi Pembatalan:</strong> Transaksi penjualan ini dibatalkan oleh <strong>{{ $sale->cancelledByUser->name ?? 'Admin' }}</strong> pada {{ $sale->cancelled_at ? $sale->cancelled_at->format('d/m/Y H:i') : '-' }}.
                <div style="margin-top: 4px; font-style: italic;">"{{ $sale->cancellation_reason }}"</div>
            </div>
        </div>
    @endif

    <div class="card-body" style="padding: 28px;">
        <!-- Two column info -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 28px; border-bottom: 1px solid var(--border); padding-bottom: 24px;">
            <div>
                <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Ditujukan Kepada (Customer):</h4>
                <div style="font-size: 1.1rem; font-weight: 700; color: #0f172a;">{{ $sale->customer_name }}</div>
                <div style="font-size: 0.84rem; color: #64748b; margin-top: 6px;">
                    <div><i class="fa-solid fa-phone me-1"></i> {{ $sale->customer_phone ?? 'Tidak ada data telepon' }}</div>
                    <div>
                        <i class="fa-solid fa-wallet me-1"></i> Metode Pembayaran:
                        <strong>
                            @if($sale->payment_method === 'cash')
                                Tunai (Cash)
                            @elseif($sale->payment_method === 'transfer')
                                Transfer Bank
                            @else
                                Tempo / Invoice Proyek
                            @endif
                        </strong>
                    </div>
                </div>
            </div>

            <div>
                <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Detail Pengeluaran:</h4>
                <div style="font-size: 0.85rem; line-height: 1.8;">
                    <div><strong>Tanggal Faktur:</strong> {{ $sale->sale_date->format('d F Y') }}</div>
                    <div><strong>Kasir / Petugas:</strong> {{ $sale->user->name ?? '-' }} ({{ ucfirst($sale->user->role ?? 'staff') }})</div>
                    <div><strong>Waktu Transaksi:</strong> {{ $sale->created_at->format('d/m/Y H:i:s') }}</div>
                    @if($sale->notes)
                        <div style="margin-top: 6px; padding: 8px 12px; background-color: #f8fafc; border-radius: 6px; border: 1px solid var(--border);">
                            <span style="font-size: 0.75rem; color: var(--text-muted); display: block;">Catatan / Pengiriman:</span>
                            {{ $sale->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sold Items Table -->
        <h4 style="font-size: 0.88rem; text-transform: uppercase; color: #334155; font-weight: 700; margin-bottom: 14px;">
            Rincian Barang & Suku Cadang Terjual
        </h4>
        <div class="table-responsive">
            <table class="table" style="border: 1px solid var(--border);">
                <thead>
                    <tr style="background-color: #f8fafc;">
                        <th>No</th>
                        <th>Kode SKU</th>
                        <th>Nama Produk & Merek</th>
                        <th>Kategori</th>
                        <th style="text-align: right;">Kuantitas</th>
                        <th style="text-align: right;">Harga Satuan (Rp)</th>
                        <th style="text-align: right;">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $idx => $item)
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
                            Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right;" class="font-mono">
                            <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #fafbfc;">
                        <td colspan="6" style="text-align: right; font-weight: 800; font-size: 0.95rem; padding: 14px 18px;">
                            TOTAL NILAI PENJUALAN:
                        </td>
                        <td style="text-align: right; font-weight: 800; font-size: 1.25rem; color: #059669; padding: 14px 18px;" class="font-mono">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($sale->paid_amount > 0)
                    <tr>
                        <td colspan="6" style="text-align: right; color: var(--text-muted); font-size: 0.85rem;">
                            Nominal Dibayarkan:
                        </td>
                        <td style="text-align: right; font-weight: 600;" class="font-mono">
                            Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: right; color: var(--text-muted); font-size: 0.85rem;">
                            Kembalian:
                        </td>
                        <td style="text-align: right; font-weight: 600; color: #0284c7;" class="font-mono">
                            Rp {{ number_format($sale->change_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        <!-- Cancellation Section (Admin Only & When Completed) -->
        @if(Auth::user()->isAdmin() && $sale->isCompleted())
            <div style="margin-top: 36px; padding: 22px; background-color: #fffaf0; border: 1px solid #fed7aa; border-radius: var(--radius-md);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                    <div>
                        <div style="font-weight: 700; color: #9a3412;">
                            <i class="fa-solid fa-shield-halved me-1"></i> Otoritas Admin: Pembatalan Transaksi Penjualan
                        </div>
                        <p style="font-size: 0.8rem; color: #7c2d12; margin-top: 4px;">
                            Membatalkan penjualan ini akan otomatis <strong>mengembalikan kuantitas barang ke stok gudang</strong>. Data faktur akan berstatus 'cancelled' sebagai bukti audit log.
                        </p>
                    </div>

                    <form action="{{ route('sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan penjualan ini? Stok fisik produk akan otomatis dipulihkan kembali.');" style="display: flex; gap: 10px; align-items: center;">
                        @csrf
                        <input type="text" name="cancellation_reason" class="form-control form-control-sm" placeholder="Alasan pembatalan..." required style="min-width: 250px;">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-ban"></i> Batalkan Faktur & Pulihkan Stok
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
