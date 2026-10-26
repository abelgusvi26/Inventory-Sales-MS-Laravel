<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MotoParts Pro - Toko Suku Cadang Sepeda Motor Terlengkap & Terpercaya</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #ef4444;
            --primary-dark: #dc2626;
            --accent: #38bdf8;
            --dark: #090d16;
            --card-bg: #111827;
            --border: rgba(255, 255, 255, 0.1);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: var(--dark);
            color: #f3f4f6;
            min-height: 100vh;
            overflow-x: hidden;
        }
        /* Navbar */
        .navbar {
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            background: rgba(9, 13, 22, 0.85);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff;
        }
        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }
        .brand-text h1 {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .brand-text p {
            font-size: 0.72rem;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #e5e7eb;
            border: 1px solid var(--border);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        /* Hero */
        .hero {
            max-width: 1200px;
            margin: 60px auto 40px;
            padding: 0 24px;
            text-align: center;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 999px;
            color: #f87171;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 24px;
            text-transform: uppercase;
        }
        .hero h2 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.2;
            margin-bottom: 20px;
            background: linear-gradient(180deg, #ffffff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.1rem;
            color: #94a3b8;
            max-width: 680px;
            margin: 0 auto 36px;
            line-height: 1.6;
        }

        /* Parts Showcase Grid */
        .showcase {
            max-width: 1200px;
            margin: 40px auto 80px;
            padding: 0 24px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 28px;
        }
        .section-header h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
        }
        .section-header p {
            color: #94a3b8;
            font-size: 0.88rem;
            margin-top: 4px;
        }
        .grid-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }
        .card-sparepart {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
        }
        .card-sparepart:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 30px rgba(0,0,0,0.4);
            border-color: rgba(239, 68, 68, 0.4);
        }
        .card-img-wrap {
            height: 180px;
            background: #0f172a;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }
        .card-img-wrap img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .card-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(15, 23, 42, 0.85);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #38bdf8;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .card-brand {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .card-specs {
            font-size: 0.78rem;
            color: #64748b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .card-footer-price {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 14px;
        }
        .price-label {
            font-size: 0.72rem;
            color: #94a3b8;
        }
        .price-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.15rem;
            font-weight: 800;
            color: #10b981;
        }

        /* Features */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 0 24px;
        }
        .feature-box {
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            text-align: left;
        }
        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 18px;
        }
        .feature-box h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }
        .feature-box p {
            font-size: 0.85rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 30px;
            text-align: center;
            font-size: 0.84rem;
            color: #64748b;
        }

        @media (max-width: 768px) {
            .navbar { padding: 16px 20px; }
            .hero h2 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <header class="navbar">
        <a href="{{ url('/') }}" class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <div class="brand-text">
                <h1>MOTOPARTS PRO</h1>
                <p>Suku Cadang & Aksesoris Motor</p>
            </div>
        </a>

        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fa-solid fa-gauge"></i> Masuk ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Login Aplikasi
                </a>
            @endauth
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge">
            <i class="fa-solid fa-check-circle"></i> Suku Cadang Motor 100% Genuine & Racing Berkualitas
        </div>
        <h2>Pusat Penjualan & Inventaris Suku Cadang Sepeda Motor</h2>
        <p>
            Solusi lengkap kebutuhan suku cadang motor matic, bebek, dan sport. Menyediakan transmisi CVT, kampas rem, piston kit, oli sintetis, kelistrikan hingga suspensi racing terpercaya.
        </p>
        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 12px 28px; font-size: 0.95rem;">
                <i class="fa-solid fa-motorcycle"></i> Akses Portal Penjualan
            </a>
            <a href="#katalog" class="btn btn-secondary" style="padding: 12px 28px; font-size: 0.95rem;">
                <i class="fa-solid fa-boxes-stacked"></i> Lihat Katalog Komponen
            </a>
        </div>
    </section>

    <!-- Sparepart Showcase Section -->
    <section class="showcase" id="katalog">
        <div class="section-header">
            <div>
                <h3>Katalog Suku Cadang Unggulan</h3>
                <p>Komponen berkualitas tinggi dilengkapi visual suku cadang dan spesifikasi detail</p>
            </div>
            <a href="{{ route('login') }}" class="btn btn-secondary" style="font-size: 0.8rem;">
                Lihat Semua Suku Cadang <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid-cards">
            <!-- Item 1: V-Belt CVT -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">TRANSMISI CVT</span>
                    <img src="{{ asset('images/products/vbelt_cvt.jpg') }}" alt="V-Belt CVT Honda Beat">
                </div>
                <div class="card-body">
                    <div class="card-brand">Honda Genuine Parts (AHM)</div>
                    <div class="card-title">V-Belt & Roller Set Beat FI / ESP</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> Beat FI, Scoopy ESP, Street
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 145.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>

            <!-- Item 2: Brake Pad -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">PENGEREMAN</span>
                    <img src="{{ asset('images/products/brake_pad.jpg') }}" alt="Kampas Rem Daytona">
                </div>
                <div class="card-body">
                    <div class="card-brand">Daytona Racing</div>
                    <div class="card-title">Kampas Rem Cakram Ultra Force Keramik</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> Vario 125/150/160, PCX, Beat
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 75.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>

            <!-- Item 3: Piston Kit -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">MESIN 4-VALVE</span>
                    <img src="{{ asset('images/products/piston_kit.jpg') }}" alt="Piston Kit Honda Vario 160">
                </div>
                <div class="card-body">
                    <div class="card-brand">Honda Genuine Parts (AHM)</div>
                    <div class="card-title">Piston Kit Set Standar Vario 160 / PCX 160</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> Mesin eSP+ 160cc 4-Katup
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 235.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>

            <!-- Item 4: Oli Mesin Motul -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">PELUMAS SINTETIS</span>
                    <img src="{{ asset('images/products/oli_mesin.jpg') }}" alt="Oli Motul Scooter Expert">
                </div>
                <div class="card-body">
                    <div class="card-brand">Motul Lubricants</div>
                    <div class="card-title">Motul Scooter Expert 4T 10W-40 1 Liter</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> Semua Matic NMAX, Vario, Aerox
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 105.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>

            <!-- Item 5: Aki GS Astra -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">KELISTRIKAN</span>
                    <img src="{{ asset('images/products/aki_motor.jpg') }}" alt="Aki GS Astra GTZ-5S">
                </div>
                <div class="card-body">
                    <div class="card-brand">GS Astra</div>
                    <div class="card-title">Aki Kering Bebas Perawatan GTZ-5S 12V</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> Beat, Scoopy, Mio, Vixion
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 245.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>

            <!-- Item 6: Shock Tabung RCB -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">SUSPENSI RACING</span>
                    <img src="{{ asset('images/products/shockbreaker.jpg') }}" alt="Shock Tabung RCB">
                </div>
                <div class="card-body">
                    <div class="card-brand">Racing Boy (RCB)</div>
                    <div class="card-title">Shockbreaker Tabung VD Series 330mm Click</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> Vario 125/150, Beat, Scoopy
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 1.650.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>

            <!-- Item 7: Gear Set SSS -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">RANTAI & GEAR</span>
                    <img src="{{ asset('images/products/gear_set.jpg') }}" alt="Gear Set SSS Gold">
                </div>
                <div class="card-body">
                    <div class="card-brand">SSS Sprocket</div>
                    <div class="card-title">Gear Set Paket Rantai SSS Racing Gold 428</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> CBR150R, CB150R, MX King
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 335.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>

            <!-- Item 8: Ban Tubeless Maxxis -->
            <div class="card-sparepart">
                <div class="card-img-wrap">
                    <span class="card-badge">BAN TUBELESS</span>
                    <img src="{{ asset('images/products/ban_motor.jpg') }}" alt="Ban Maxxis Victra Soft Compound">
                </div>
                <div class="card-body">
                    <div class="card-brand">Maxxis Tire</div>
                    <div class="card-title">Ban Motor Victra S98 Soft Compound 90/80-14</div>
                    <div class="card-specs">
                        <i class="fa-solid fa-motorcycle"></i> Ring 14 Matic Standar & Modif
                    </div>
                    <div class="card-footer-price">
                        <div>
                            <div class="price-label">Harga Eceran</div>
                            <div class="price-value">Rp 330.000</div>
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 0.76rem;">Beli</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section class="features-grid">
        <div class="feature-box">
            <div class="feature-icon">
                <i class="fa-solid fa-camera"></i>
            </div>
            <h4>Visual Suku Cadang Lengkap</h4>
            <p>Setiap suku cadang dilengkapi gambar representatif beresolusi tinggi memudahkan identifikasi part motor yang tepat.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <h4>Kasir & Stock-Out Otomatis</h4>
            <p>Mencatat transaksi penjualan kasir secara atomic dengan kalkulasi kembalian dan pemotongan stok otomatis.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon">
                <i class="fa-solid fa-bell"></i>
            </div>
            <h4>Early Warning Min-Stock</h4>
            <p>Peringatan otomatis saat stok busi, oli, atau kampas rem mencapai batas minimum demi ketersediaan stok servis.</p>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} <strong>MotoParts Pro</strong> — Sistem Informasi Penjualan & Inventaris Suku Cadang Sepeda Motor. All rights reserved.</p>
    </footer>
</body>
</html>
