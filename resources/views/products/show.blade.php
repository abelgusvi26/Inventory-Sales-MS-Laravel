@extends('layouts.app')

@section('title', 'Detail Suku Cadang ' . $product->name)
@section('page_title', 'Informasi Detail & Spesifikasi Suku Cadang')

@section('topbar_actions')
    @if(!Auth::user()->isCustomer())
        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Edit Suku Cadang
        </a>
    @endif
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Left Column: Product Specifications & Picture Card -->
    <div>
        <div class="card" style="margin-bottom: 24px;">
            <div style="background-color: #0f172a; border-radius: var(--radius-lg) var(--radius-lg) 0 0; overflow: hidden; position: relative; text-align: center; padding: 10px;">
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" 
                     style="width: 100%; max-height: 250px; object-fit: contain; border-radius: 8px;"
                     onerror="this.src='{{ asset('images/products/placeholder.svg') }}'">
                <div style="position: absolute; top: 12px; right: 12px;">
                    @if($product->stock <= 0)
                        <span class="badge badge-danger">Stok Habis</span>
                    @elseif($product->isLowStock())
                        <span class="badge badge-warning">Stok Rendah</span>
                    @else
                        <span class="badge badge-success">Stok Siap</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 16px;">
                    <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Kode SKU Sparepart</div>
                    <div class="font-mono" style="font-size: 1.15rem; font-weight: 800; color: #0284c7;">{{ $product->sku }}</div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Nama Suku Cadang</div>
                    <div style="font-size: 1.05rem; font-weight: 700; color: #0f172a; line-height: 1.4;">{{ $product->name }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Kategori</div>
                        <strong>{{ $product->category->name ?? '-' }}</strong>
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Merek / Pabrikan</div>
                        <strong>{{ $product->brand ?? 'OEM Universal' }}</strong>
                    </div>
                    <div style="grid-column: span 2;">
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Kompatibel Sepeda Motor</div>
                        <strong style="color: #0284c7;"><i class="fa-solid fa-motorcycle me-1"></i> {{ $product->model_series ?? 'Universal Semua Motor' }}</strong>
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Satuan</div>
                        <strong>{{ $product->unit }}</strong>
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Lokasi Rak Toko</div>
                        <strong>{{ $product->location ?? 'Gudang Utama' }}</strong>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px; border: 1px solid var(--border);">
                    @if(!Auth::user()->isCustomer())
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-size: 0.8rem; color: var(--text-muted);">Harga Kulakan (Beli):</span>
                            <strong class="font-mono">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</strong>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-bottom: {{ !Auth::user()->isCustomer() ? '10px' : '0' }};">
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Harga Jual Satuan:</span>
                        <strong class="font-mono" style="color: #059669; font-size: 1.2rem;">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</strong>
                    </div>
                    @if(!Auth::user()->isCustomer())
                        <div style="display: flex; justify-content: space-between; border-top: 1px dashed var(--border); padding-top: 8px;">
                            <span style="font-size: 0.8rem; color: var(--text-muted);">Estimasi Margin Laba:</span>
                            @php
                                $margin = $product->selling_price - $product->purchase_price;
                                $marginPercent = $product->purchase_price > 0 ? ($margin / $product->purchase_price) * 100 : 0;
                            @endphp
                            <strong class="font-mono" style="color: #0284c7;">
                                Rp {{ number_format($margin, 0, ',', '.') }} ({{ number_format($marginPercent, 1) }}%)
                            </strong>
                        </div>
                    @endif
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div style="background: #f1f5f9; padding: 12px; border-radius: 8px;">
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Stok Fisik Tersedia</div>
                        <div style="font-size: 1.35rem; font-weight: 800; color: #0f172a;">
                            {{ $product->stock }} <span style="font-size: 0.8rem; font-weight: 500;">{{ $product->unit }}</span>
                        </div>
                    </div>
                    <div style="background: #f1f5f9; padding: 12px; border-radius: 8px;">
                        <div style="font-size: 0.72rem; color: var(--text-muted);">Batas Minimum Alert</div>
                        <div style="font-size: 1.35rem; font-weight: 800; color: #64748b;">
                            {{ $product->min_stock }} <span style="font-size: 0.8rem; font-weight: 500;">{{ $product->unit }}</span>
                        </div>
                    </div>
                </div>

                @if($product->description)
                <div>
                    <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Deskripsi Suku Cadang</div>
                    <p style="font-size: 0.85rem; color: #475569; margin-top: 4px; line-height: 1.5;">{{ $product->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Transaction History for this Product (Staff & Admin) / Information for Customer -->
    <div>
        @if(!Auth::user()->isCustomer())
            <!-- Recent Purchases (Stock In History) -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-arrow-down-long text-success"></i>
                        Riwayat Kulakan Masuk (Stock-In)
                    </div>
                    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-cart-plus"></i> Beli Stok Ini
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. PO</th>
                                <th>Tanggal</th>
                                <th>Distributor / Supplier</th>
                                <th>Kuantitas</th>
                                <th>Harga Beli</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPurchases as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $item->purchase) }}" class="font-mono" style="font-weight: 600; color: #d97706; text-decoration: none;">
                                        {{ $item->purchase->reference_no }}
                                    </a>
                                </td>
                                <td>{{ $item->purchase->purchase_date->format('d/m/Y') }}</td>
                                <td>{{ $item->purchase->supplier->name ?? '-' }}</td>
                                <td><strong style="color: #059669;">+{{ $item->quantity }} {{ $product->unit }}</strong></td>
                                <td class="font-mono">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                                <td class="font-mono"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">
                                    Belum ada catatan pembelian masuk untuk produk ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Sales (Stock Out History) -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-arrow-up-long text-danger"></i>
                        Riwayat Penjualan Kasir (Stock-Out)
                    </div>
                    @if($product->stock > 0)
                        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-cash-register"></i> Jual Produk Ini
                        </a>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Faktur</th>
                                <th>Tanggal</th>
                                <th>Pelanggan / Bengkel</th>
                                <th>Kuantitas</th>
                                <th>Harga Jual</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $item->sale) }}" class="font-mono" style="font-weight: 600; color: #0284c7; text-decoration: none;">
                                        {{ $item->sale->invoice_no }}
                                    </a>
                                </td>
                                <td>{{ $item->sale->sale_date->format('d/m/Y') }}</td>
                                <td>{{ $item->sale->customer_name }}</td>
                                <td><strong style="color: #ef4444;">-{{ $item->quantity }} {{ $product->unit }}</strong></td>
                                <td class="font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="font-mono"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">
                                    Belum ada transaksi penjualan kasir untuk produk ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Customer View: Motorcycle Sparepart Information & Purchase Inquiries -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-circle-check text-success"></i> Informasi Pemesanan & Ketersediaan Suku Cadang Motor
                    </div>
                </div>
                <div class="card-body">
                    <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 18px; margin-bottom: 20px;">
                        <h4 style="color: #166534; font-size: 0.95rem; margin-bottom: 6px;">
                            <i class="fa-solid fa-motorcycle me-1"></i> Stok Siap Pasang & Kirim
                        </h4>
                        <p style="font-size: 0.85rem; color: #15803d; line-height: 1.5;">
                            Suku cadang motor ini tersedia di etalase toko kami dengan kuantitas fisik <strong>{{ $product->stock }} {{ $product->unit }}</strong>.
                            Siap dipasang langsung di bengkel kami atau dikirim ke alamat Anda.
                        </p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
                        <div style="border: 1px solid var(--border); padding: 14px; border-radius: 8px;">
                            <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Garansi & Kualitas</div>
                            <div style="font-size: 0.9rem; font-weight: 700; color: #0f172a; margin-top: 4px;">100% Genuine & Racing Original</div>
                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">Terjamin presisi dan awet untuk pemakaian harian.</div>
                        </div>
                        <div style="border: 1px solid var(--border); padding: 14px; border-radius: 8px;">
                            <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Metode Pembayaran</div>
                            <div style="font-size: 0.9rem; font-weight: 700; color: #0f172a; margin-top: 4px;">Tunai (Kasir), QRIS & Transfer Bank</div>
                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px;">Struk resmi & nota bengkel otomatis tercetak.</div>
                        </div>
                    </div>

                    <div style="padding: 18px; background-color: #f8fafc; border-radius: 8px; border: 1px dashed var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
                        <div>
                            <strong style="color: #334155; font-size: 0.85rem;"><i class="fa-solid fa-headset me-1 text-primary"></i> Layanan CS Bengkel:</strong>
                            <div style="font-size: 0.82rem; color: #64748b; margin-top: 4px;">
                                WhatsApp / Telepon Hotline Kasir MotoParts: <strong>0812-3456-7890</strong>
                            </div>
                        </div>
                        @if($product->stock > 0)
                            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-cart-plus me-1"></i> Pesan Suku Cadang Ini
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
