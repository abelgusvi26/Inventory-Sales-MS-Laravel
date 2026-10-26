@extends('layouts.app')

@section('title', 'Dashboard Ringkasan Bisnis')
@section('page_title', 'Ringkasan Eksekutif & Operasional')

@section('topbar_actions')
    @if(!Auth::user()->isCustomer())
        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-cart-plus"></i> Transaksi Penjualan
        </a>
        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-secondary">
            <i class="fa-solid fa-boxes-packing"></i> Pembelian Stok
        </a>
    @else
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-boxes-stacked"></i> Jelajahi Suku Cadang
        </a>
        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary">
            <i class="fa-solid fa-receipt"></i> Pesanan Saya
        </a>
    @endif
@endsection

@section('content')
<style>
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .metric-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        padding: 22px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .metric-icon {
        width: 52px;
        height: 52px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .metric-data h3 {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .metric-data .value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
    }
    .metric-data .subtext {
        font-size: 0.74rem;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .icon-amber { background: #fef3c7; color: #d97706; }
    .icon-emerald { background: #d1fae5; color: #059669; }
    .icon-blue { background: #e0f2fe; color: #0284c7; }
    .icon-rose { background: #ffe4e6; color: #e11d48; }

    .dashboard-two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 1024px) {
        .dashboard-two-col {
            grid-template-columns: 1fr;
        }
    }
</style>

@if(Auth::user()->isCustomer())
    <!-- Customer Specific Dashboard View -->
    <div class="card" style="margin-bottom: 24px; position: relative; overflow: hidden; background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%), url('{{ asset('images/dashboard_banner.jpg') }}') center/cover no-repeat; color: white; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
        <div class="card-body" style="padding: 24px 28px; backdrop-filter: blur(4px);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div>
                    <span class="user-role-badge role-customer" style="margin-bottom: 10px; background: rgba(239, 68, 68, 0.25); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4);">PORTAL BENGKEL & KONSUMEN MOTOR</span>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #ffffff; margin-top: 6px; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h2>
                    <p style="color: #cbd5e1; font-size: 0.88rem; margin-top: 4px; max-width: 600px;">
                        Di portal MotoParts Pro ini Anda dapat menelusuri katalog suku cadang sepeda motor secara realtime dengan foto asli beresolusi tinggi serta memantau riwayat faktur transaksi Anda.
                    </p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-motorcycle"></i> Lihat Katalog Suku Cadang Motor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="metrics-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="metric-card">
            <div class="metric-icon icon-emerald">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="metric-data">
                <h3>Total Suku Cadang Tersedia</h3>
                <div class="value font-mono">{{ $totalProducts }} <span style="font-size: 0.85rem; font-weight: 500;">Item</span></div>
                <div class="subtext">{{ $totalCategories }} kategori suku cadang</div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon icon-blue">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div class="metric-data">
                <h3>Riwayat Pembelian Anda</h3>
                <div class="value font-mono">
                    {{ \App\Models\Sale::where(function($q) {
                        $u = Auth::user();
                        $q->where('customer_name', 'like', "%{$u->name}%")
                          ->orWhere('customer_name', 'like', "%{$u->username}%")
                          ->when($u->phone, function($sq) use ($u) {
                              return $sq->orWhere('customer_phone', $u->phone);
                          });
                    })->count() }} <span style="font-size: 0.85rem; font-weight: 500;">Faktur</span>
                </div>
                <div class="subtext"><a href="{{ route('sales.index') }}" style="color: inherit; text-decoration: underline;">Lihat semua riwayat faktur</a></div>
            </div>
        </div>
    </div>
@else
<!-- Stat Cards: All aggregate calculated from DB -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-icon icon-emerald">
            <i class="fa-solid fa-cash-register"></i>
        </div>
        <div class="metric-data">
            <h3>Penjualan Hari Ini</h3>
            <div class="value font-mono">Rp {{ number_format($salesTodayTotal, 0, ',', '.') }}</div>
            <div class="subtext">{{ $salesTodayCount }} transaksi tuntas</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon icon-amber">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="metric-data">
            <h3>Penjualan Bulan Ini</h3>
            <div class="value font-mono">Rp {{ number_format($salesMonthTotal, 0, ',', '.') }}</div>
            <div class="subtext">{{ $salesMonthCount }} transaksi bulan ini</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon icon-blue">
            <i class="fa-solid fa-truck-ramp-box"></i>
        </div>
        <div class="metric-data">
            <h3>Pembelian Bulan Ini</h3>
            <div class="value font-mono">Rp {{ number_format($purchasesMonthTotal, 0, ',', '.') }}</div>
            <div class="subtext">{{ $purchasesMonthCount }} pesanan masuk</div>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon {{ $lowStockCount > 0 ? 'icon-rose' : 'icon-emerald' }}">
            <i class="fa-solid fa-warehouse"></i>
        </div>
        <div class="metric-data">
            <h3>Peringatan Stok Rendah</h3>
            <div class="value" style="{{ $lowStockCount > 0 ? 'color: var(--danger);' : '' }}">
                {{ $lowStockCount }} <span style="font-size: 0.85rem; font-weight: 500;">Produk</span>
            </div>
            <div class="subtext">
                <a href="{{ route('products.low-stock') }}" style="color: inherit; text-decoration: underline;">
                    Lihat detail & restock
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Valuation & Modern Workshop Banner Card -->
<div class="card" style="margin-bottom: 24px; position: relative; overflow: hidden; background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.9) 100%), url('{{ asset('images/dashboard_banner.jpg') }}') center/cover no-repeat; color: white; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
    <div class="card-body" style="padding: 24px 28px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 20px; backdrop-filter: blur(4px);">
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 999px; margin-bottom: 8px;">
                <i class="fa-solid fa-motorcycle text-danger"></i>
                <span style="font-size: 0.72rem; font-weight: 700; color: #fca5a5; text-transform: uppercase;">Showroom & Gudang MotoParts</span>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #ffffff;">Valuasi Aset Suku Cadang Motor</h3>
            <div class="font-mono" style="font-size: 1.85rem; font-weight: 800; color: #fbbf24; margin-top: 4px; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                Rp {{ number_format($inventoryAssetValue, 0, ',', '.') }}
            </div>
        </div>
        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
            <div style="background: rgba(15, 23, 42, 0.75); padding: 12px 16px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase;">Suku Cadang</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #ffffff;"><i class="fa-solid fa-box text-warning me-1"></i> {{ $totalProducts }} SKU</div>
            </div>
            <div style="background: rgba(15, 23, 42, 0.75); padding: 12px 16px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase;">Kategori</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #ffffff;"><i class="fa-solid fa-tags text-info me-1"></i> {{ $totalCategories }} Jenis</div>
            </div>
            <div style="background: rgba(15, 23, 42, 0.75); padding: 12px 16px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase;">Pemasok Resmi</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #ffffff;"><i class="fa-solid fa-truck text-success me-1"></i> {{ $totalSuppliers }} Mitra</div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Table Alert (if any) -->
@if($lowStockProducts->count() > 0)
<div class="card" style="border-left: 4px solid var(--danger);">
    <div class="card-header" style="background-color: #fff8f8;">
        <div class="card-title" style="color: #991b1b;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Perhatian: Suku Cadang Mendekati / Habis Stok
        </div>
        <a href="{{ route('products.low-stock') }}" class="btn btn-sm btn-outline-danger">
            Lihat Semua ({{ $lowStockCount }})
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">Foto</th>
                    <th>SKU</th>
                    <th>Nama Suku Cadang</th>
                    <th>Kategori</th>
                    <th>Sisa Stok</th>
                    <th>Batas Minimum</th>
                    <th>Lokasi Rak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $prod)
                <tr>
                    <td>
                        <img src="{{ $prod->imageUrl() }}" alt="{{ $prod->name }}" 
                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border); background-color: #0f172a;"
                             onerror="this.src='{{ asset('images/products/placeholder.svg') }}'">
                    </td>
                    <td class="font-mono"><strong>{{ $prod->sku }}</strong></td>
                    <td>
                        <strong style="color: #0f172a;">{{ $prod->name }}</strong>
                        <div style="font-size: 0.72rem; color: #64748b;">{{ $prod->brand ?? '-' }} | Motor: {{ $prod->model_series ?? 'Universal' }}</div>
                    </td>
                    <td><span class="badge badge-secondary">{{ $prod->category->name ?? '-' }}</span></td>
                    <td>
                        @if($prod->stock <= 0)
                            <span class="badge badge-danger">HABIS (0 {{ $prod->unit }})</span>
                        @else
                            <span class="badge badge-warning">{{ $prod->stock }} {{ $prod->unit }}</span>
                        @endif
                    </td>
                    <td>{{ $prod->min_stock }} {{ $prod->unit }}</td>
                    <td>{{ $prod->location ?? '-' }}</td>
                    <td>
                        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary" title="Beli Stok">
                            <i class="fa-solid fa-cart-plus"></i> Restock
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endif

<!-- Two Column Section: Recent Sales & Recent Purchases -->
<div class="dashboard-two-col">
    <!-- Recent Sales -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-receipt text-success"></i>
                Penjualan Terbaru
            </div>
            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                    <tr>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="font-mono" style="font-weight: 600; color: #0284c7; text-decoration: none;">
                                {{ $sale->invoice_no }}
                            </a>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">
                                {{ $sale->sale_date->format('d M Y') }}
                            </div>
                        </td>
                        <td>{{ $sale->customer_name }}</td>
                        <td class="font-mono" style="font-weight: 700;">
                            Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($sale->status === 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Belum ada transaksi penjualan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(!Auth::user()->isCustomer())
    <!-- Recent Purchases -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-truck-moving text-warning"></i>
                Pembelian Terbaru
            </div>
            <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. PO</th>
                        <th>Supplier</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPurchases as $purchase)
                    <tr>
                        <td>
                            <a href="{{ route('purchases.show', $purchase) }}" class="font-mono" style="font-weight: 600; color: #d97706; text-decoration: none;">
                                {{ $purchase->reference_no }}
                            </a>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">
                                {{ $purchase->purchase_date->format('d M Y') }}
                            </div>
                        </td>
                        <td>{{ $purchase->supplier->name ?? '-' }}</td>
                        <td class="font-mono" style="font-weight: 700;">
                            Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($purchase->status === 'completed')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 30px;">
                            Belum ada transaksi pembelian
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- Customer Quick Help & Categories -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-motorcycle text-primary"></i>
                Kategori Suku Cadang Motor Populer
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="card-body">
            <p style="font-size: 0.84rem; color: #64748b; margin-bottom: 16px;">
                Cari komponen spesifik sesuai kebutuhan servis motor Anda:
            </p>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('products.index') }}" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #1e293b; border: 1px solid var(--border);">
                    <span><i class="fa-solid fa-gears me-2 text-danger"></i> Transmisi CVT & Drivetrain</span>
                    <i class="fa-solid fa-chevron-right text-muted"></i>
                </a>
                <a href="{{ route('products.index') }}" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #1e293b; border: 1px solid var(--border);">
                    <span><i class="fa-solid fa-circle-dot me-2 text-primary"></i> Sistem Pengereman (Brake Pad / Disc)</span>
                    <i class="fa-solid fa-chevron-right text-muted"></i>
                </a>
                <a href="{{ route('products.index') }}" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #1e293b; border: 1px solid var(--border);">
                    <span><i class="fa-solid fa-oil-can me-2 text-warning"></i> Oli Mesin 4T & Pelumas Matic</span>
                    <i class="fa-solid fa-chevron-right text-muted"></i>
                </a>
                <a href="{{ route('products.index') }}" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #1e293b; border: 1px solid var(--border);">
                    <span><i class="fa-solid fa-car-battery me-2 text-info"></i> Kelistrikan & Aki Kering (MF)</span>
                    <i class="fa-solid fa-chevron-right text-muted"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
