<!DOCTYPE html>
<html lang="id">
<head>
        @php $appType = request('app', 'rt'); @endphp
    <link rel="manifest" href="{{ asset('manifest-' . $appType . '.json') }}">
    <meta name="theme-color" content="{{ $appType === 'kelurahan' ? '#7c3aed' : '#059669' }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-' . $appType . '.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Pelayanan Surat Kelurahan Sungai Asam</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; min-height: 100vh; background: #f9fafb; }

        .login-wrapper { display: flex; min-height: 100vh; }

        /* ================================================
           SISI KIRI - BRANDING
           ================================================ */
        .side-left {
            flex: 1;
            background: linear-gradient(135deg, #1e3a8a 0%, #6d28d9 100%);
            color: white;
            padding: 50px 55px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .side-left::before {
            content: '';
            position: absolute;
            top: -150px; right: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.4) 0%, transparent 70%);
            border-radius: 50%;
        }
        .side-left::after {
            content: '';
            position: absolute;
            bottom: -200px; left: -150px;
            width: 550px; height: 550px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.35) 0%, transparent 70%);
            border-radius: 50%;
        }

        .brand { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; margin-bottom: 50px; }
        .brand-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            border: 1px solid rgba(255,255,255,0.25);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        .brand-text h1 { font-size: 19px; font-weight: 800; letter-spacing: -0.3px; margin-bottom: 2px; }
        .brand-text p { font-size: 12.5px; opacity: 0.8; letter-spacing: 0.3px; }

        .hero-text { position: relative; z-index: 1; margin-bottom: 36px; }
        .hero-text h2 {
            font-size: 40px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        .hero-text p {
            font-size: 15px;
            opacity: 0.9;
            line-height: 1.7;
            max-width: 460px;
        }

        .benefits { position: relative; z-index: 1; display: flex; flex-direction: column; gap: 12px; }
        .benefit-item {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.12);
            transition: 0.2s;
        }
        .benefit-item:hover {
            background: rgba(255,255,255,0.14);
            transform: translateX(4px);
        }
        .benefit-icon {
            flex-shrink: 0;
            width: 40px; height: 40px;
            background: linear-gradient(135deg, rgba(251,191,36,0.3) 0%, rgba(245,158,11,0.2) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            border: 1px solid rgba(251,191,36,0.3);
        }
        .benefit-text { font-size: 13.5px; font-weight: 600; }

        /* ================================================
           SISI KANAN - FORM
           ================================================ */
        .side-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
            background: #f9fafb;
            overflow-y: auto;
        }
        .login-form-wrap { width: 100%; max-width: 420px; }

        .login-header { text-align: center; margin-bottom: 28px; }
        .login-header h2 { font-size: 26px; font-weight: 800; color: #111827; margin-bottom: 6px; letter-spacing: -0.5px; }
        .login-header p { font-size: 13.5px; color: #6b7280; }

        .form-group { margin-bottom: 16px; }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
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
        .form-input.error { border-color: #dc2626; }
        .form-hint {
            font-size: 11.5px;
            color: #9ca3af;
            margin-top: 5px;
            line-height: 1.5;
        }
        .error-msg { font-size: 12px; color: #dc2626; margin-top: 5px; }

        /* Password input wrapper */
        .input-wrap { position: relative; }
        .input-wrap .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #9ca3af;
            padding: 4px;
            border-radius: 6px;
            transition: 0.15s;
        }
        .input-wrap .toggle-password:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #4b5563;
            cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }
        .forgot-link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            font-family: inherit;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            color: #9ca3af;
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .btn-register {
            display: block;
            width: 100%;
            padding: 13px;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #2563eb;
            font-size: 14px;
            font-weight: 700;
            transition: 0.15s;
        }
        .btn-register:hover {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .back-home {
            text-align: center;
            margin-top: 22px;
            font-size: 13px;
        }
        .back-home a {
            color: #6b7280;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.15s;
        }
        .back-home a:hover { color: #2563eb; }

        /* ================================================
           ALERT ERROR
           ================================================ */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #dc2626;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-error-icon {
            flex-shrink: 0;
            width: 24px; height: 24px;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }
        .alert-error-text {
            flex: 1;
            font-size: 13px;
            color: #991b1b;
            line-height: 1.5;
        }

        /* ================================================
           RESPONSIVE
           ================================================ */
        @media (max-width: 900px) {
            .login-wrapper { flex-direction: column; }
            .side-left { padding: 36px 30px; min-height: auto; }
            .hero-text h2 { font-size: 28px; }
            .hero-text p { font-size: 14px; }
            .brand { margin-bottom: 30px; }
            .benefits { gap: 10px; }
            .benefit-item { padding: 12px 14px; }
            .benefit-text { font-size: 13px; }
        }
        @media (max-width: 640px) {
            .side-left { padding: 28px 22px; }
            .brand-text h1 { font-size: 16px; }
            .hero-text h2 { font-size: 22px; }
            .side-right { padding: 28px 20px; }
            .login-header h2 { font-size: 22px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- ============ SISI KIRI: BRANDING ============ --}}
    <div class="side-left">

        <div>
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
        </div>

        <div class="benefits">
            <div class="benefit-item">
                <div class="benefit-icon">⚡</div>
                <div class="benefit-text">Proses cepat, surat selesai dalam hitungan jam</div>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">🔒</div>
                <div class="benefit-text">Data Anda aman & hanya bisa diakses petugas berwenang</div>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">📱</div>
                <div class="benefit-text">Pantau status surat kapan saja dari HP</div>
            </div>
        </div>

    </div>

    {{-- ============ SISI KANAN: FORM LOGIN ============ --}}
    <div class="side-right">
        <div class="login-form-wrap">

            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            {{-- ALERT ERROR --}}
            @if ($errors->any())
                <div class="alert-error">
                    <div class="alert-error-icon">!</div>
                    <div class="alert-error-text">
                        @foreach ($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-left:4px solid #10b981; border-radius:10px; padding:14px 16px; margin-bottom:18px; font-size:13px; color:#065f46;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="formLogin">
                @csrf

                {{-- Email / Nomor HP --}}
                <div class="form-group">
                    <label for="email">Email atau Nomor HP</label>
                    <input id="email" type="text" name="email"
                           value="{{ old('email') }}"
                           class="form-input @error('email') error @enderror"
                           placeholder="nama@example.com atau 08123456789"
                           required autofocus autocomplete="username"
                           inputmode="email">
                    <div class="form-hint">
                        💡 Bisa pakai <strong>email</strong> atau <strong>nomor HP</strong> yang terdaftar
                    </div>
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <input id="password" type="password" name="password"
                               class="form-input @error('password') error @enderror"
                               placeholder="••••••••"
                               required autocomplete="current-password"
                               style="padding-right:45px;">
                        <button type="button" class="toggle-password" onclick="togglePassword()" title="Tampilkan/Sembunyikan">
                            👁️
                        </button>
                    </div>
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex-between">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                {{-- Tombol Login --}}
                <button type="submit" class="btn-login" id="btnLogin">
                    <span id="btnText">🚀 Masuk ke Sistem</span>
                </button>

            </form>

            <div class="divider">Belum punya akun?</div>

            <a href="{{ route('register') }}" class="btn-register">
                Daftar sebagai Warga Baru
            </a>

            <div class="back-home">
                <a href="{{ route('landing') }}">← Kembali ke Beranda</a>
            </div>

        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const btn = document.querySelector('.toggle-password');
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
        }
    }

    document.getElementById('formLogin')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnLogin');
        const btnText = document.getElementById('btnText');
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btnText.innerHTML = '⏳ Memproses...';
    });
</script>
    <!-- SERVICE WORKER PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
</body>
</html>