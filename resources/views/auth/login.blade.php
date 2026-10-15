<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Pelayanan Surat Kelurahan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            background: #f9fafb;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ============ SISI KIRI (Branding) ============ */
        .side-left {
            flex: 1;
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            color: white;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .side-left::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .side-left::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
        }
        .brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .brand-text h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .brand-text p {
            font-size: 13px;
            opacity: 0.85;
        }

        .hero-text {
            position: relative;
            z-index: 1;
            padding: 40px 0;
        }
        .hero-text h2 {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 16px;
        }
        .hero-text p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.7;
            max-width: 480px;
        }

        .features {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .feature-icon {
            font-size: 22px;
            flex-shrink: 0;
        }
        .feature-text {
            font-size: 14px;
            font-weight: 500;
        }

        /* ============ SISI KANAN (Form Login) ============ */
        .side-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            background: #f9fafb;
        }

        .login-form-wrap {
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-header .icon-mobile {
            display: none;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 14px;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin: 0 auto 16px;
        }
        .login-header h2 {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        .login-header p {
            font-size: 14px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            background: white;
            transition: 0.2s;
            color: #111827;
        }
        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
        }
        .form-input.error {
            border-color: #dc2626;
        }

        .error-msg {
            font-size: 12px;
            color: #dc2626;
            margin-top: 4px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }
        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #4b5563;
            cursor: pointer;
        }
        .checkbox-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #2563eb;
        }
        .forgot-link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            font-family: inherit;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #9ca3af;
            font-size: 12px;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .register-box {
            text-align: center;
            padding: 16px;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #4b5563;
        }
        .register-box a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }
        .register-box a:hover {
            text-decoration: underline;
        }

        .back-home {
            text-align: center;
            margin-top: 24px;
        }
        .back-home a {
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .back-home a:hover {
            color: #2563eb;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 900px) {
            .login-wrapper { flex-direction: column; }
            .side-left {
                padding: 40px 30px;
                min-height: auto;
            }
            .hero-text h2 { font-size: 28px; }
            .hero-text p { font-size: 14px; }
            .features { display: none; }
            .hero-text { padding: 24px 0; }
        }

        @media (max-width: 640px) {
            .side-left {
                padding: 30px 20px;
            }
            .brand-text h1 { font-size: 16px; }
            .hero-text h2 { font-size: 22px; }
            .side-right { padding: 30px 20px; }
            .login-header .icon-mobile { display: flex; }
            .login-header h2 { font-size: 22px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- ============ SISI KIRI: BRANDING ============ --}}
    <div class="side-left">
        <div class="brand">
            <div class="brand-icon">📄</div>
            <div class="brand-text">
                <h1>Pelayanan Surat</h1>
                <p>Kelurahan Sungai Asam</p>
            </div>
        </div>

        <div class="hero-text">
            <h2>Ajukan Surat<br>Tanpa Antre,<br>Kapan Saja</h2>
            <p>
                Sistem pelayanan surat online untuk warga Kelurahan Sungai Asam.
                Ajukan Surat Keterangan Domisili, SKTM, atau SKU — semua dari rumah.
            </p>
        </div>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">⚡</div>
                <div class="feature-text">Proses cepat, surat selesai dalam hitungan jam</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🔒</div>
                <div class="feature-text">Data Anda aman & hanya bisa diakses petugas berwenang</div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">📱</div>
                <div class="feature-text">Pantau status surat kapan saja dari HP</div>
            </div>
        </div>
    </div>

    {{-- ============ SISI KANAN: FORM LOGIN ============ --}}
    <div class="side-right">
        <div class="login-form-wrap">

            <div class="login-header">
                <div class="icon-mobile">📄</div>
                <h2>Selamat Datang</h2>
                <p>Silakan login untuk melanjutkan</p>
            </div>

                        {{-- Session status: sukses daftar / reset password --}}
            @if (session('success'))
                <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-left:4px solid #10b981; color:#065f46; padding:14px 16px; border-radius:8px; font-size:13.5px; margin-bottom:16px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:26px; height:26px; background:#10b981; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:bold;">
                        ✓
                    </div>
                    <div style="line-height:1.5;">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-left:4px solid #10b981; color:#065f46; padding:14px 16px; border-radius:8px; font-size:13.5px; margin-bottom:16px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- EMAIL --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-input @error('email') error @enderror"
                           placeholder="nama@example.com"
                           required autofocus autocomplete="username">
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input id="password" type="password" name="password"
                           class="form-input @error('password') error @enderror"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- OPTIONS --}}
                <div class="form-options">
                    <label class="checkbox-wrap">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
                    @endif
                </div>

                {{-- SUBMIT --}}
                <button type="submit" class="btn-login">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="divider">Belum punya akun?</div>

            <div class="register-box">
                <a href="{{ route('register') }}">Daftar sebagai Warga Baru</a>
            </div>

            <div class="back-home">
                <a href="{{ route('landing') }}">← Kembali ke Beranda</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>