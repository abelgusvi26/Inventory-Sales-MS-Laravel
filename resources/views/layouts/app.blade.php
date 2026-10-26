<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - MotoParts Pro (Suku Cadang Motor)</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #ef4444; /* Racing Red for Motorcycle Parts */
            --primary-dark: #dc2626;
            --primary-light: #fee2e2;
            --secondary: #0ea5e9;
            --dark-bg: #090d16;
            --sidebar-bg: #0f172a;
            --card-bg: #ffffff;
            --body-bg: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --radius-lg: 14px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 14px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #cbd5e1;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 50;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.3rem;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .sidebar-brand .brand-text h2 {
            font-size: 1.05rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .sidebar-brand .brand-text p {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            padding: 20px 14px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-category {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            padding: 12px 14px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .nav-item i {
            width: 20px;
            font-size: 1rem;
            text-align: center;
        }

        .nav-item:hover {
            color: #ffffff;
            background-color: rgba(255,255,255,0.06);
            transform: translateX(3px);
        }

        .nav-item.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.25) 0%, rgba(245, 158, 11, 0.08) 100%);
            border-left: 3px solid var(--primary);
            font-weight: 600;
        }

        .nav-item.active i {
            color: var(--primary);
        }

        .nav-badge {
            margin-left: auto;
            background-color: var(--danger);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: rgba(0,0,0,0.15);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .user-info {
            flex-grow: 1;
            overflow: hidden;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #f1f5f9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            display: inline-block;
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
        }

        .role-admin {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }

        .role-staff {
            background-color: rgba(14, 165, 233, 0.2);
            color: #38bdf8;
            border: 1px solid rgba(14, 165, 233, 0.4);
        }

        .role-customer {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 270px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 6px rgba(0,0,0,0.03);
        }

        .topbar-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-title h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .content-area {
            padding: 30px 32px 50px;
            flex-grow: 1;
        }

        /* Card and Table Components */
        .card {
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 24px;
        }

        /* Alerts */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.9rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert-error, .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background-color: #fffbeb;
            color: #92400e;
            border-left: 4px solid var(--warning);
        }

        /* Modern Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-size: 0.86rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #0f172a;
            font-weight: 700;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-outline-danger {
            background-color: transparent;
            border-color: #fca5a5;
            color: #ef4444;
        }

        .btn-outline-danger:hover {
            background-color: #ef4444;
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.78rem;
            border-radius: var(--radius-sm);
        }

        /* Forms & Inputs */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-control, .form-select {
            width: 100%;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background-color: #ffffff;
            color: #1e293b;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--danger);
            background-color: #fffaf0;
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.78rem;
            margin-top: 5px;
            display: block;
        }

        /* Tables */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.86rem;
        }

        .table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            padding: 12px 18px;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table td {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: middle;
        }

        .table tr:hover td {
            background-color: #fbfcfe;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-secondary {
            background-color: #f1f5f9;
            color: #475569;
        }

        /* Monospace for Codes / Currency */
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Filter bar */
        .filter-card {
            background-color: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            padding: 18px 24px;
            margin-bottom: 24px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            align-items: end;
        }

        /* Pagination custom */
        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-icon">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <div class="brand-text">
                <h2>MOTOPARTS PRO</h2>
                <p>Sparepart Sepeda Motor</p>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-category">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            @if(Auth::user()->isCustomer())
                <div class="menu-category">Pesanan & Belanja</div>
                <a href="{{ route('sales.create') }}" class="nav-item {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Pesan Suku Cadang</span>
                </a>
                <a href="{{ route('sales.index') }}" class="nav-item {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Riwayat Pesanan</span>
                </a>

                <div class="menu-category">E-Katalog Suku Cadang</div>
                <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.index') || request()->routeIs('products.show') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Katalog Produk</span>
                </a>
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i>
                    <span>Kategori Produk</span>
                </a>
            @else
                <div class="menu-category">Transaksi Gudang</div>
                <a href="{{ route('purchases.index') }}" class="nav-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-dolly"></i>
                    <span>Pembelian / Stock-In</span>
                </a>
                <a href="{{ route('sales.index') }}" class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Penjualan / Stock-Out</span>
                </a>

                <div class="menu-category">Master Data Produk</div>
                <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.index') || request()->routeIs('products.create') || request()->routeIs('products.show') || request()->routeIs('products.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Katalog Produk</span>
                </a>
                <a href="{{ route('products.low-stock') }}" class="nav-item {{ request()->routeIs('products.low-stock') ? 'active' : '' }}">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Peringatan Stok Rendah</span>
                    @php
                        $lowStockCountBadge = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count();
                    @endphp
                    @if($lowStockCountBadge > 0)
                        <span class="nav-badge">{{ $lowStockCountBadge }}</span>
                    @endif
                </a>
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i>
                    <span>Kategori Produk</span>
                </a>
                <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                    <span>Pemasok / Supplier</span>
                </a>

                @if(Auth::user()->isAdmin())
                    <div class="menu-category">Administrasi</div>
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                @endif
            @endif

            <div class="menu-category">Panduan & Teknis</div>
            <a href="{{ route('documentation') }}" class="nav-item {{ request()->routeIs('documentation') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i>
                <span>Dokumentasi & ERD</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <span class="user-role-badge {{ Auth::user()->isAdmin() ? 'role-admin' : (Auth::user()->isCustomer() ? 'role-customer' : 'role-staff') }}">
                    {{ strtoupper(Auth::user()->role) }}
                </span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary" title="Keluar dari sistem" style="background: rgba(255,255,255,0.08); color: #cbd5e1; border:none;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-title">
                <h1>@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="topbar-actions">
                <span style="font-size: 0.82rem; color: var(--text-muted);">
                    <i class="fa-regular fa-calendar-days me-1"></i> {{ date('d F Y') }}
                </span>
                @yield('topbar_actions')
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check fa-lg"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-xmark fa-lg"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                    <div>
                        <strong>Terdapat kesalahan pengisian data:</strong>
                        <ul style="margin-left: 20px; margin-top: 4px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
