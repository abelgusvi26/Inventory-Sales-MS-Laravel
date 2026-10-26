@extends('layouts.app')

@section('title', 'Dokumentasi Sistem & ERD')
@section('page_title', 'Arsitektur, Relasi Database (ERD) & Panduan Sistem')

@section('topbar_actions')
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
@endsection

@section('content')
<style>
    .doc-section {
        margin-bottom: 30px;
    }
    .erd-box {
        background: #0f172a;
        color: #f8fafc;
        border-radius: var(--radius-lg);
        padding: 24px;
        overflow-x: auto;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.85rem;
        line-height: 1.6;
    }
    .schema-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-bottom: 16px;
    }
    .schema-card h4 {
        color: var(--primary);
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .schema-table {
        width: 100%;
        font-size: 0.82rem;
        border-collapse: collapse;
    }
    .schema-table th, .schema-table td {
        padding: 6px 10px;
        border-bottom: 1px solid var(--border);
        text-align: left;
    }
    .schema-table th {
        color: var(--text-muted);
        font-weight: 600;
    }
    .badge-pk {
        background: #ef4444;
        color: white;
        font-size: 0.65rem;
        padding: 2px 5px;
        border-radius: 4px;
        font-weight: bold;
    }
    .badge-fk {
        background: #0284c7;
        color: white;
        font-size: 0.65rem;
        padding: 2px 5px;
        border-radius: 4px;
        font-weight: bold;
    }
    .rule-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: var(--radius-md);
        margin-bottom: 10px;
        border-left: 3px solid var(--primary);
    }
    .rule-item i {
        color: var(--primary);
        margin-top: 3px;
    }
</style>

<!-- Ringkasan Info UTS -->
<div class="card doc-section">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-graduation-cap text-primary"></i>
            Spesifikasi Proyek Terintegrasi (Integrated Laravel Project)
        </div>
        <span class="badge badge-success">UTS Web Programming 2</span>
    </div>
    <div class="card-body">
        <p style="color: var(--text-muted); margin-bottom: 18px; line-height: 1.6;">
            Aplikasi <strong>MotoParts Pro</strong> dibangun untuk memenuhi seluruh ketentuan penilaian proyek tengah semester:
            menerapkan arsitektur <strong>Laravel MVC</strong>, <strong>MySQL DB Migration & Seeding</strong>, <strong>Eloquent Relationships</strong>,
            <strong>Database Transactions (`DB::transaction`)</strong> untuk konsistensi stok atomik, serta <strong>Role-Based Access Control (RBAC)</strong> di sisi server.
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <div style="background: #eff6ff; padding: 14px 18px; border-radius: var(--radius-md); border-left: 4px solid #3b82f6;">
                <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #1d4ed8;">Role Pengguna</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #1e3a8a; margin-top: 4px;">Admin & Staff</div>
                <div style="font-size: 0.75rem; color: #3b82f6; margin-top: 2px;">+ Portal Customer (Retail)</div>
            </div>
            <div style="background: #f0fdf4; padding: 14px 18px; border-radius: var(--radius-md); border-left: 4px solid #22c55e;">
                <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #15803d;">Konsistensi Stok</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #14532d; margin-top: 4px;">Atomic Transaction</div>
                <div style="font-size: 0.75rem; color: #16a34a; margin-top: 2px;">lockForUpdate() & Auto-Rollback</div>
            </div>
            <div style="background: #fefce8; padding: 14px 18px; border-radius: var(--radius-md); border-left: 4px solid #eab308;">
                <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #a16207;">Kalkulasi Keuangan</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #713f12; margin-top: 4px;">Server-Side Calculation</div>
                <div style="font-size: 0.75rem; color: #ca8a04; margin-top: 2px;">Harga & total dari database</div>
            </div>
            <div style="background: #fdf2f8; padding: 14px 18px; border-radius: var(--radius-md); border-left: 4px solid #ec4899;">
                <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #be185d;">Integritas Data</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #831843; margin-top: 4px;">Soft Deletes & FK</div>
                <div style="font-size: 0.75rem; color: #db2777; margin-top: 2px;">Larangan hapus produk bertransaksi</div>
            </div>
        </div>
    </div>
</div>

<!-- ERD & Relasi Database -->
<div class="card doc-section">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-diagram-project text-primary"></i>
            Entity Relationship Diagram (ERD) & Struktur Tabel
        </div>
        <span class="badge badge-info">Relasional MySQL</span>
    </div>
    <div class="card-body">
        <div class="erd-box mb-4">
<pre style="margin: 0; color: #38bdf8; font-size: 0.82rem;">
[USERS] (id, name, email, role: admin|staff|customer)
   │
   ├───────────► (1:N) ──► [PURCHASES] (id, reference_no, supplier_id, user_id, total, status)
   │                             │
   │                             └─► (1:N) ──► [PURCHASE_ITEMS] (id, purchase_id, product_id, qty, unit_cost, subtotal)
   │                                                   │ (N:1)
   │                                                   ▼
   │                                             [PRODUCTS] (id, category_id, sku, name, brand, purchase_price, selling_price, stock, min_stock)
   │                                                   ▲
   │                                                   │ (N:1)
   ├───────────► (1:N) ──► [SALES] (id, invoice_no, user_id, customer_name, total, status)
   │                             │
   │                             └─► (1:N) ──► [SALE_ITEMS] (id, sale_id, product_id, qty, unit_price, subtotal)
   │
[CATEGORIES] ────────► (1:N) ──────────────────────────┘ (mengelompokkan Products)
[SUPPLIERS]   ────────► (1:N) ──────────────────────────┘ (pemasok pada Purchases)
</pre>
        </div>

        <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 14px; color: var(--text-main);">
            <i class="fa-solid fa-link me-1 text-primary"></i> Implementasi Relasi Eloquent (Model Mapping)
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
            <div class="schema-card">
                <h4><i class="fa-solid fa-layer-group"></i> Category & Product</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px;">
                    <code>Category hasMany Product</code><br>
                    <code>Product belongsTo Category</code>
                </p>
                <div style="font-size: 0.75rem; background: #f1f5f9; padding: 6px 10px; border-radius: 4px;">
                    Pencegahan N+1: <code>Product::with('category')->paginate(10)</code>
                </div>
            </div>

            <div class="schema-card">
                <h4><i class="fa-solid fa-dolly"></i> Purchase & Items</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px;">
                    <code>Supplier hasMany Purchase</code><br>
                    <code>Purchase hasMany PurchaseItem</code><br>
                    <code>PurchaseItem belongsTo Product</code>
                </p>
                <div style="font-size: 0.75rem; background: #f1f5f9; padding: 6px 10px; border-radius: 4px;">
                    Penambahan stok: Dieksekusi otomatis saat commit purchase
                </div>
            </div>

            <div class="schema-card">
                <h4><i class="fa-solid fa-cart-shopping"></i> Sale & SaleItems</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px;">
                    <code>User hasMany Sale</code><br>
                    <code>Sale hasMany SaleItem</code><br>
                    <code>SaleItem belongsTo Product</code>
                </p>
                <div style="font-size: 0.75rem; background: #f1f5f9; padding: 6px 10px; border-radius: 4px;">
                    Pengurangan stok: Menggunakan <code>lockForUpdate()</code>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aturan Bisnis & Penanganan Error -->
<div class="card doc-section">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-shield-halved text-primary"></i>
            Business Rules & Integritas Transaksi (Slide 8 & 16)
        </div>
    </div>
    <div class="card-body">
        <div class="rule-item">
            <i class="fa-solid fa-check-circle"></i>
            <div>
                <strong>Atomisitas Transaksi (All or Nothing)</strong>:
                Seluruh simpanan header nota dan item barang dibungkus dalam blok <code>DB::transaction()</code>. Jika satu baris item bermasalah atau stok kurang, seluruh operasi akan otomatis di-rollback tanpa ada data menggantung.
            </div>
        </div>

        <div class="rule-item">
            <i class="fa-solid fa-check-circle"></i>
            <div>
                <strong>Validasi Stok & Pencegahan Overselling</strong>:
                Sebelum memotong stok penjualan, sistem mengunci baris produk dengan <code>lockForUpdate()</code> dan mengecek apakah <code>$product->stock >= $qty</code>. Jika tidak mencukupi, sistem langsung melempar <code>ValidationException</code> dan membatalkan pesanan.
            </div>
        </div>

        <div class="rule-item">
            <i class="fa-solid fa-check-circle"></i>
            <div>
                <strong>Integritas Perhitungan Harga</strong>:
                Harga satuan (*unit price*) dan subtotal diambil langsung dari tabel produk di database server, bukan mempercayai input form dari browser untuk mencegah manipulasi harga dari client-side.
            </div>
        </div>

        <div class="rule-item">
            <i class="fa-solid fa-check-circle"></i>
            <div>
                <strong>Mekanisme Pembatalan Transaksi</strong>:
                Transaksi yang keliru tidak boleh diedit atau dihapus sembarangan. Admin dapat melakukan pembatalan resmi (*cancellation*) disertai alasan, dan sistem otomatis mengembalikan stok barang ke saldo gudang secara konsisten.
            </div>
        </div>
    </div>
</div>

<!-- Kredensial Akun Uji Coba -->
<div class="card doc-section">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-key text-primary"></i>
            Akun Demo Pengujian (Seeders)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Role Akses</th>
                        <th>Username</th>
                        <th>Password Default</th>
                        <th>Hak Akses & Otorisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge badge-warning">Admin / Owner</span></td>
                        <td><code>admin</code></td>
                        <td><code>admin123</code></td>
                        <td>Akses penuh ke semua modul, manajemen akun user, dan hak membatalkan transaksi</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-info">Kasir / Staff</span></td>
                        <td><code>staff1</code> / <code>staff2</code></td>
                        <td><code>staff123</code></td>
                        <td>Input pembelian gudang, kasir penjualan POS, katalog produk (tidak bisa kelola role)</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-success">Pelanggan / Bengkel</span></td>
                        <td><code>customer1</code> / <code>customer2</code></td>
                        <td><code>customer123</code></td>
                        <td>Akses katalog suku cadang, membuat pesanan mandiri, dan melihat riwayat belanja pribadi</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
