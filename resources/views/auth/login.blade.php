<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HeavyEquip Inventory & Sales Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #f59e0b;
            --primary-dark: #d97706;
            --dark-slate: #0f172a;
            --card-dark: #1e293b;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background: radial-gradient(circle at 10% 20%, #1e293b 0%, #0f172a 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #f1f5f9;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .brand-badge {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #0f172a;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        }
        .login-header h1 {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .login-header p {
            font-size: 0.85rem;
            color: #94a3b8;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
        }
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-group i {
            position: absolute;
            left: 14px;
            color: #64748b;
            font-size: 0.95rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #ffffff;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25);
            background-color: rgba(15, 23, 42, 0.9);
        }
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            border-radius: 10px;
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.45);
        }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }
        .demo-credentials {
            margin-top: 26px;
            padding: 16px;
            background-color: rgba(15, 23, 42, 0.5);
            border-radius: 12px;
            border: 1px dashed rgba(255, 255, 255, 0.15);
            font-size: 0.78rem;
            color: #94a3b8;
        }
        .demo-credentials strong {
            color: #fbbf24;
            display: block;
            margin-bottom: 6px;
        }
        .cred-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="brand-badge">
                <i class="fa-solid fa-truck-monster"></i>
            </div>
            <h1>HEAVY EQUIP</h1>
            <p>Sistem Informasi Inventaris & Penjualan</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="login">Username atau Email</label>
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="login" name="login" class="form-control" value="{{ old('login') }}" placeholder="Masukkan username atau email" required autofocus autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Kata Sandi (Password)</label>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" value="" placeholder="Masukkan password" required autocomplete="current-password">
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; font-size: 0.82rem; color: #94a3b8;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat Saya</span>
                </label>
                <span style="color: #64748b;">Enterprise Access</span>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Masuk ke Sistem
            </button>
        </form>

        <div class="demo-credentials">
            <strong><i class="fa-solid fa-key me-1"></i> Referensi Akun Seeder:</strong>
            <div class="cred-item">
                <span>Admin / Owner:</span>
                <span>admin / admin123</span>
            </div>
            <div class="cred-item">
                <span>Staff Operasional:</span>
                <span>staff1 / staff123</span>
            </div>
            <div class="cred-item">
                <span>Customer / Pembeli:</span>
                <span>customer1 / customer123</span>
            </div>
        </div>
    </div>
</body>
</html>
