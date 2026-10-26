<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Pelayanan Surat Kelurahan Sungai Asam</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; min-height: 100vh; background: #f9fafb; }

        .register-wrapper { display: flex; min-height: 100vh; }

        /* ================================================
           SISI KIRI - BRANDING & BENEFIT
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

        .brand { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; margin-bottom: 40px; }
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

        .hero-text { position: relative; z-index: 1; margin-bottom: 32px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            font-size: 11.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 18px;
        }
        .hero-text h2 { font-size: 36px; font-weight: 800; line-height: 1.15; letter-spacing: -0.8px; margin-bottom: 14px; }
        .hero-text h2 span {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-text p { font-size: 14.5px; opacity: 0.9; line-height: 1.7; max-width: 460px; }

        .benefits { position: relative; z-index: 1; display: flex; flex-direction: column; gap: 12px; margin-bottom: 28px; }
        .benefit-item {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 14px 18px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.12);
            transition: 0.2s;
        }
        .benefit-item:hover { background: rgba(255,255,255,0.14); transform: translateX(4px); }
        .benefit-icon {
            flex-shrink: 0; width: 40px; height: 40px;
            background: linear-gradient(135deg, rgba(251,191,36,0.3) 0%, rgba(245,158,11,0.2) 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            border: 1px solid rgba(251,191,36,0.3);
        }
        .benefit-text h4 { font-size: 13.5px; font-weight: 700; margin-bottom: 3px; }
        .benefit-text p { font-size: 12px; opacity: 0.75; line-height: 1.5; }

        .mini-stats {
            position: relative; z-index: 1;
            display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;
            padding-top: 22px;
            border-top: 1px solid rgba(255,255,255,0.12);
        }
        .mini-stat { text-align: center; }
        .mini-stat .num { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 2px; }
        .mini-stat .lbl { font-size: 10.5px; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

        /* ================================================
           SISI KANAN - FORM
           ================================================ */
        .side-right {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 30px;
            background: #f9fafb;
            overflow-y: auto;
        }
        .register-form-wrap { width: 100%; max-width: 460px; }

        .register-header { text-align: center; margin-bottom: 24px; }
        .register-header h2 { font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 6px; }
        .register-header p { font-size: 13.5px; color: #6b7280; }

        .master-data-box {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1.5px solid #a7f3d0;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 18px;
        }
        .master-data-title {
            font-size: 12.5px; font-weight: 700; color: #065f46;
            margin-bottom: 10px;
            display: flex; align-items: center; gap: 6px;
        }
        .master-data-row {
            display: flex; justify-content: space-between;
            font-size: 13px; padding: 5px 0;
            border-bottom: 1px solid #d1fae5;
        }
        .master-data-row:last-child { border-bottom: none; }
        .master-data-label { color: #047857; font-weight: 500; }
        .master-data-value { color: #065f46; font-weight: 700; text-align: right; }

        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block; font-size: 13px; font-weight: 600;
            color: #374151; margin-bottom: 6px;
        }
        .form-group label .req { color: #dc2626; }
        .form-input {
            width: 100%;
            padding: 11px 14px;
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
        .form-input.success { border-color: #10b981; background: #f0fdf4; }
        .error-msg { font-size: 12px; color: #dc2626; margin-top: 4px; }

        /* 🆕 NIK INPUT GROUP */
        .nik-input-group {
            display: flex;
            gap: 8px;
            align-items: stretch;
        }
        .nik-input-group .form-input {
            flex: 1;
            font-family: monospace;
            letter-spacing: 1.5px;
            font-size: 15px;
        }
        .btn-cek-nik {
            padding: 11px 20px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
            transition: 0.2s;
        }
        .btn-cek-nik:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37,99,235,0.4);
        }
        .btn-cek-nik:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* 🆕 HASIL CEK NIK */
        .cek-result {
            margin-top: 10px;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 13px;
            animation: slideDown 0.3s ease-out;
            display: none;
        }
        .cek-result.show { display: block; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .cek-result.found {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1.5px solid #10b981;
        }
        .cek-result.notfound {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1.5px solid #ef4444;
        }
        .cek-result.warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1.5px solid #f59e0b;
        }
        .cek-result-header {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 8px;
        }
        .cek-result-icon {
            font-size: 20px;
            line-height: 1;
            flex-shrink: 0;
        }
        .cek-result-title {
            font-weight: 800;
            font-size: 13.5px;
            margin-bottom: 2px;
        }
        .cek-result.found .cek-result-title { color: #065f46; }
        .cek-result.notfound .cek-result-title { color: #991b1b; }
        .cek-result.warning .cek-result-title { color: #78350f; }
        .cek-result-message {
            font-size: 12.5px;
            line-height: 1.5;
        }
        .cek-result.found .cek-result-message { color: #047857; }
        .cek-result.notfound .cek-result-message { color: #7f1d1d; }
        .cek-result.warning .cek-result-message { color: #92400e; }
        .cek-result-data {
            background: white;
            border-radius: 8px;
            padding: 10px 12px;
            margin-top: 10px;
            font-size: 12px;
        }
        .cek-result-data-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            gap: 10px;
        }
        .cek-result-data-label { color: #6b7280; }
        .cek-result-data-value { font-weight: 700; color: #111827; text-align: right; }

        /* Syarat & Ketentuan */
        .syarat-box {
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px 16px;
            margin: 18px 0;
            transition: 0.2s;
        }
        .syarat-box.checked { background: #eff6ff; border-color: #93c5fd; }
        .syarat-box.error { background: #fef2f2; border-color: #fecaca; }
        .syarat-checkbox {
            display: flex; align-items: flex-start; gap: 10px;
            cursor: pointer;
        }
        .syarat-checkbox input[type="checkbox"] {
            width: 18px; height: 18px;
            accent-color: #2563eb;
            cursor: pointer;
            margin-top: 1px;
            flex-shrink: 0;
        }
        .syarat-label { font-size: 13px; color: #374151; line-height: 1.5; }
        .syarat-link {
            color: #2563eb; font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
            text-decoration-style: dotted;
            text-underline-offset: 2px;
        }
        .syarat-link:hover { color: #1d4ed8; }

        .btn-register {
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
        .btn-register:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        .btn-register:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 22px 0;
            color: #9ca3af; font-size: 12px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #e5e7eb;
        }
        .login-box {
            text-align: center;
            padding: 16px;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #4b5563;
        }
        .login-box a { color: #2563eb; font-weight: 600; text-decoration: none; }
        .login-box a:hover { text-decoration: underline; }
        .back-home { text-align: center; margin-top: 20px; }
        .back-home a {
            font-size: 13px; color: #6b7280;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .back-home a:hover { color: #2563eb; }

        /* MODAL */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center; justify-content: center;
            padding: 20px;
            animation: fadeIn 0.2s ease;
        }
        .modal-overlay.active { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 600px;
            width: 100%;
            max-height: 85vh;
            display: flex; flex-direction: column;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header {
            padding: 22px 26px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            display: flex; justify-content: space-between; align-items: center;
            flex-shrink: 0;
        }
        .modal-header h3 { font-size: 17px; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 8px; }
        .modal-close {
            background: rgba(255,255,255,0.15);
            border: none; color: white;
            width: 32px; height: 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 20px;
            display: flex; align-items: center; justify-content: center;
            transition: 0.2s;
        }
        .modal-close:hover { background: rgba(255,255,255,0.25); }
        .modal-body {
            padding: 24px 26px;
            overflow-y: auto;
            font-size: 13.5px;
            line-height: 1.7;
            color: #374151;
        }
        .modal-body h4 { font-size: 14px; font-weight: 700; color: #111827; margin: 18px 0 8px 0; }
        .modal-body h4:first-child { margin-top: 0; }
        .modal-body p { margin-bottom: 10px; }
        .modal-body ul { margin: 8px 0 12px 22px; padding: 0; }
        .modal-body li { margin-bottom: 6px; }
        .modal-footer {
            padding: 16px 26px;
            border-top: 1px solid #f3f4f6;
            display: flex; gap: 10px; justify-content: flex-end;
            flex-shrink: 0;
            background: #fafbfc;
        }
        .btn-modal-cancel {
            padding: 10px 20px;
            background: white;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            color: #374151;
            font-family: inherit;
            transition: 0.2s;
        }
        .btn-modal-cancel:hover { background: #f9fafb; }
        .btn-modal-agree {
            padding: 10px 22px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: 0.2s;
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        .btn-modal-agree:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(37,99,235,0.4); }

        @media (max-width: 900px) {
            .register-wrapper { flex-direction: column; }
            .side-left { padding: 40px 30px; min-height: auto; }
            .hero-text h2 { font-size: 26px; }
            .hero-text p { font-size: 13.5px; }
            .benefits { gap: 10px; }
            .benefit-item { padding: 12px 14px; }
        }
        @media (max-width: 640px) {
            .side-left { padding: 30px 22px; }
            .brand-text h1 { font-size: 16px; }
            .hero-text h2 { font-size: 22px; }
            .side-right { padding: 30px 20px; }
            .register-header h2 { font-size: 22px; }
            .mini-stats { grid-template-columns: 1fr 1fr 1fr; gap: 6px; }
            .mini-stat .num { font-size: 18px; }
            .modal-content { max-height: 90vh; }
            .modal-header { padding: 18px 20px; }
            .modal-body { padding: 20px; }
            .modal-footer { padding: 14px 20px; flex-direction: column; }
            .modal-footer button { width: 100%; }
            .nik-input-group { flex-direction: column; }
            .btn-cek-nik { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

<div class="register-wrapper">

    {{-- ============ SISI KIRI: BRANDING & BENEFIT ============ --}}
    <div class="side-left">

        <div>
            <div class="brand">
                <div class="brand-icon">📄</div>
                <div class="brand-text">
                    <h1>Pelayanan Surat</h1>
                    <p>KELURAHAN SUNGAI ASAM</p>
                </div>
            </div>

            <div class="hero-text">
                <div class="hero-badge">✨ GRATIS & MUDAH</div>
                <h2>
                    Daftar Sekali,<br>
                    <span>Nikmati Kemudahan</span><br>
                    Layanan Surat Digital
                </h2>
                <p>
                    Cukup daftar sekali, Anda bisa mengajukan Surat Keterangan Domisili, SKTM, dan SKU
                    kapan saja dari rumah — tanpa antre, tanpa ribet.
                </p>
            </div>

            <div class="benefits">
                <div class="benefit-item">
                    <div class="benefit-icon">⚡</div>
                    <div class="benefit-text">
                        <h4>Proses Cepat</h4>
                        <p>Surat selesai dalam hitungan jam, bukan berhari-hari</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">🔒</div>
                    <div class="benefit-text">
                        <h4>Data Terlindungi</h4>
                        <p>Dokumen Anda terenkripsi dan hanya bisa diakses petugas berwenang</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">📱</div>
                    <div class="benefit-text">
                        <h4>Pantau Real-time</h4>
                        <p>Cek status surat kapan saja langsung dari HP Anda</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mini-stats">
            <div class="mini-stat">
                <div class="num">100%</div>
                <div class="lbl">Gratis</div>
            </div>
            <div class="mini-stat">
                <div class="num">24/7</div>
                <div class="lbl">Online</div>
            </div>
            <div class="mini-stat">
                <div class="num">3</div>
                <div class="lbl">Jenis Surat</div>
            </div>
        </div>

    </div>

    {{-- ============ SISI KANAN: FORM ============ --}}
    <div class="side-right">
        <div class="register-form-wrap">

            <div class="register-header">
                <h2>Buat Akun Baru</h2>
                <p>Lengkapi data untuk mendaftar akun pelayanan</p>
            </div>

            @if (isset($masterData) && $masterData)
                <div class="master-data-box">
                    <div class="master-data-title">
                        ✓ NIK Terverifikasi oleh RT
                    </div>
                    <div class="master-data-row">
                        <span class="master-data-label">Nama</span>
                        <span class="master-data-value">{{ $masterData->nama }}</span>
                    </div>
                    <div class="master-data-row">
                        <span class="master-data-label">Jenis Kelamin</span>
                        <span class="master-data-value">{{ $masterData->jenisKelaminLabel() }}</span>
                    </div>
                    <div class="master-data-row">
                        <span class="master-data-label">TTL</span>
                        <span class="master-data-value">{{ $masterData->tempat_lahir }}, {{ $masterData->tanggalLahirFormat() }}</span>
                    </div>
                    <div class="master-data-row">
                        <span class="master-data-label">Alamat</span>
                        <span class="master-data-value">{{ Str::limit($masterData->alamat, 30) }}</span>
                    </div>
                    <div class="master-data-row">
                        <span class="master-data-label">RT / RW</span>
                        <span class="master-data-value">{{ $masterData->rt }} / {{ $masterData->rw }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:10px; padding:14px 16px; margin-bottom:16px;">
                    <ul style="margin:0; padding-left:18px; font-size:13px; color:#991b1b; line-height:1.7;">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="formRegister">
                @csrf

                {{-- NIK + TOMBOL CEK --}}
                <div class="form-group">
                    <label for="nik">NIK (16 digit) <span class="req">*</span></label>

                    <div class="nik-input-group">
                        <input id="nik" type="text" name="nik" value="{{ old('nik', $nik ?? '') }}"
                               class="form-input @error('nik') error @enderror"
                               placeholder="3201010101010001"
                               maxlength="16" pattern="[0-9]{16}" inputmode="numeric"
                               required autofocus autocomplete="off">
                        <button type="button" class="btn-cek-nik" id="btnCekNik" onclick="cekNikRegister()">
                            🔍 Cek NIK
                        </button>
                    </div>

                    <div style="font-size:11.5px; color:#6b7280; margin-top:6px; line-height:1.5;">
                        💡 Klik <strong>Cek NIK</strong> untuk memastikan NIK Anda sudah terdaftar oleh Ketua RT.
                    </div>

                    {{-- HASIL CEK NIK --}}
                    <div class="cek-result" id="cekResult"></div>

                    @error('nik') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- EMAIL --}}
                <div class="form-group">
                    <label for="email">Email <span class="req">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-input @error('email') error @enderror"
                           placeholder="nama@example.com"
                           required autocomplete="username">
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- NO HP --}}
                <div class="form-group">
                    <label for="phone">
                        No. HP / WhatsApp
                        <span style="font-size:11px; color:#9ca3af; font-weight:400;">(opsional)</span>
                    </label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                           class="form-input @error('phone') error @enderror"
                           placeholder="081234567890">
                    @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="form-group">
                    <label for="password">Kata Sandi <span class="req">*</span></label>
                    <input id="password" type="password" name="password"
                           class="form-input @error('password') error @enderror"
                           placeholder="Min. 8 karakter"
                           required autocomplete="new-password">
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- KONFIRMASI --}}
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi <span class="req">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="form-input"
                           placeholder="Ulangi kata sandi"
                           required autocomplete="new-password">
                </div>

                {{-- SYARAT & KETENTUAN --}}
                <div class="syarat-box @error('syarat') error @enderror" id="syaratBox">
                    <label class="syarat-checkbox">
                        <input type="checkbox" name="syarat" id="cbSyarat" value="1"
                               {{ old('syarat') ? 'checked' : '' }}>
                        <span class="syarat-label">
                            Saya telah membaca dan menyetujui
                            <span class="syarat-link" onclick="bukaModalSyarat(event)">Syarat & Ketentuan</span>
                            serta <a href="javascript:void(0)" class="syarat-link" onclick="bukaModalPrivasi(event)">Kebijakan Privasi</a>
                            yang berlaku pada sistem pelayanan surat Kelurahan Sungai Asam.
                        </span>
                    </label>
                </div>
                @error('syarat') <div class="error-msg" style="margin-top:-12px; margin-bottom:12px;">{{ $message }}</div> @enderror

                <button type="submit" class="btn-register" id="btnRegister" disabled>
                    Daftar Sekarang
                </button>
            </form>

            <div class="divider">Sudah punya akun?</div>

            <div class="login-box">
                <a href="{{ route('login') }}">Login di Sini</a>
            </div>

            <div class="back-home">
                <a href="{{ route('landing') }}">← Kembali ke Beranda</a>
            </div>

        </div>
    </div>

</div>

{{-- MODAL SYARAT & KETENTUAN --}}
<div class="modal-overlay" id="modalSyarat">
    <div class="modal-content">
        <div class="modal-header">
            <h3>📜 Syarat & Ketentuan</h3>
            <button type="button" class="modal-close" onclick="tutupModal('modalSyarat')">&times;</button>
        </div>
        <div class="modal-body">
            <p><strong>Terakhir diperbarui:</strong> {{ date('d F Y') }}</p>

            <h4>1. Ketentuan Umum</h4>
            <p>Dengan mendaftar dan menggunakan layanan Sistem Pelayanan Surat Kelurahan Sungai Asam, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.</p>

            <h4>2. Persyaratan Pendaftaran</h4>
            <ul>
                <li>Pendaftar adalah warga yang sah dan berdomisili di wilayah RT yang bersangkutan.</li>
                <li>NIK (Nomor Induk Kependudukan) harus sudah terdaftar dan diverifikasi oleh Ketua RT setempat.</li>
                <li>Data yang diisi harus sesuai dengan dokumen resmi (KTP/KK) dan tidak boleh dipalsukan.</li>
                <li>Satu NIK hanya boleh terdaftar dalam satu akun pelayanan.</li>
            </ul>

            <h4>3. Tanggung Jawab Pengguna</h4>
            <ul>
                <li>Menjaga kerahasiaan email dan kata sandi akun.</li>
                <li>Tidak membagikan akses akun kepada pihak lain.</li>
                <li>Menggunakan layanan sesuai peruntukan (kepentingan surat-menyurat resmi).</li>
                <li>Segera melaporkan ke kelurahan jika akun diduga disalahgunakan.</li>
            </ul>

            <h4>4. Penggunaan Data Pribadi</h4>
            <p>Data pribadi yang Anda berikan akan digunakan untuk:</p>
            <ul>
                <li>Proses verifikasi identitas dan domisili.</li>
                <li>Penerbitan surat keterangan yang sah.</li>
                <li>Keperluan administrasi pelayanan publik di lingkungan kelurahan.</li>
                <li>Komunikasi terkait status pengajuan surat Anda.</li>
            </ul>

            <h4>5. Larangan</h4>
            <p>Pengguna dilarang:</p>
            <ul>
                <li>Menggunakan data atau identitas orang lain tanpa izin.</li>
                <li>Mengunggah dokumen palsu atau tidak sah.</li>
                <li>Melakukan tindakan yang merugikan sistem atau pengguna lain.</li>
                <li>Menggunakan layanan untuk tujuan ilegal.</li>
            </ul>

            <h4>6. Sanksi</h4>
            <p>Pelanggaran terhadap syarat & ketentuan dapat mengakibatkan:</p>
            <ul>
                <li>Penonaktifan akun secara permanen.</li>
                <li>Pembatalan surat yang telah diterbitkan.</li>
                <li>Pelaporan kepada pihak berwenang jika terjadi tindak pidana.</li>
            </ul>

            <h4>7. Perubahan Ketentuan</h4>
            <p>Kelurahan Sungai Asam berhak mengubah syarat & ketentuan ini sewaktu-waktu. Perubahan akan diinformasikan melalui halaman ini. Penggunaan layanan setelah perubahan berarti Anda menyetujui ketentuan yang baru.</p>

            <h4>8. Kontak</h4>
            <p>Jika ada pertanyaan, hubungi:</p>
            <ul>
                <li>Email: kelurahan.sungaiasam@jambikota.go.id</li>
                <li>Telepon: (0741) 123456</li>
                <li>WhatsApp: 0812-3456-7890</li>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="tutupModal('modalSyarat')">Tutup</button>
            <button type="button" class="btn-modal-agree" onclick="setujuDanTutup('modalSyarat')">✓ Saya Setuju</button>
        </div>
    </div>
</div>

{{-- MODAL KEBIJAKAN PRIVASI --}}
<div class="modal-overlay" id="modalPrivasi">
    <div class="modal-content">
        <div class="modal-header">
            <h3>🔒 Kebijakan Privasi</h3>
            <button type="button" class="modal-close" onclick="tutupModal('modalPrivasi')">&times;</button>
        </div>
        <div class="modal-body">
            <p><strong>Terakhir diperbarui:</strong> {{ date('d F Y') }}</p>

            <h4>1. Data yang Dikumpulkan</h4>
            <p>Kami mengumpulkan data berikut untuk keperluan pelayanan:</p>
            <ul>
                <li>Data identitas: NIK, nama lengkap, jenis kelamin, tempat & tanggal lahir.</li>
                <li>Data kontak: email, nomor HP/WhatsApp.</li>
                <li>Data dokumen: scan KTP, scan KK, pas foto.</li>
                <li>Data aktivitas: riwayat pengajuan surat.</li>
            </ul>

            <h4>2. Penggunaan Data</h4>
            <p>Data Anda hanya digunakan untuk:</p>
            <ul>
                <li>Verifikasi kebenaran identitas dan domisili.</li>
                <li>Proses penerbitan surat keterangan.</li>
                <li>Arsip pelayanan di lingkungan kelurahan.</li>
            </ul>

            <h4>3. Perlindungan Data</h4>
            <p>Kami berkomitmen melindungi data Anda melalui:</p>
            <ul>
                <li>Enkripsi data saat transmisi (HTTPS) dan penyimpanan.</li>
                <li>Akses terbatas hanya untuk petugas berwenang (RT & Kelurahan).</li>
                <li>Audit log aktivitas akses data untuk akuntabilitas.</li>
                <li>Penyimpanan dokumen sensitif di storage privat yang tidak dapat diakses publik.</li>
            </ul>

            <h4>4. Tidak Dijual ke Pihak Ketiga</h4>
            <p>Kami <strong>TIDAK</strong> menjual, menyewakan, atau membagikan data pribadi Anda kepada pihak ketiga tanpa izin, kecuali diwajibkan oleh hukum.</p>

            <h4>5. Hak Anda</h4>
            <ul>
                <li>Melihat dan mengunduh data pribadi Anda.</li>
                <li>Memperbarui data melalui halaman profil.</li>
                <li>Meminta penghapusan akun (dengan syarat tertentu).</li>
                <li>Mengajukan keberatan atas penggunaan data.</li>
            </ul>

            <h4>6. Retensi Data</h4>
            <p>Data disimpan selama Anda aktif menggunakan layanan, atau sesuai ketentuan pengarsipan dokumen negara.</p>

            <h4>7. Kontak</h4>
            <p>Jika ada pertanyaan tentang privasi, hubungi:</p>
            <ul>
                <li>Email: kelurahan.sungaiasam@jambikota.go.id</li>
                <li>Telepon: (0741) 123456</li>
            </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="tutupModal('modalPrivasi')">Tutup</button>
            <button type="button" class="btn-modal-agree" onclick="setujuDanTutup('modalPrivasi')">✓ Saya Setuju</button>
        </div>
    </div>
</div>

<script>
    // Auto-format NIK
    document.getElementById('nik')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 16);
        // Sembunyikan hasil cek kalau NIK diubah
        hideCekResult();
    });

    // 🆕 AUTO CHECK setelah 16 digit diisi (dengan debounce)
    let autoCheckTimer = null;
    document.getElementById('nik')?.addEventListener('input', function() {
        if (this.value.length === 16) {
            clearTimeout(autoCheckTimer);
            autoCheckTimer = setTimeout(function() {
                cekNikRegister();
            }, 500); // Tunggu 500ms setelah user berhenti mengetik
        }
    });

    // Enter di input NIK → cek
    document.getElementById('nik')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            cekNikRegister();
        }
    });

    // 🆕 FUNGSI CEK NIK
    function cekNikRegister() {
        const input = document.getElementById('nik');
        const btn = document.getElementById('btnCekNik');
        const result = document.getElementById('cekResult');
        const nik = input.value.trim();

        // Validasi dasar
        if (!nik) {
            showCekResult('warning', '⚠️', 'NIK Belum Diisi', 'Silakan masukkan NIK 16 digit terlebih dahulu.');
            input.focus();
            return;
        }
        if (!/^\d{16}$/.test(nik)) {
            showCekResult('warning', '⚠️', 'NIK Tidak Valid', 'NIK harus terdiri dari 16 digit angka.');
            input.focus();
            return;
        }

        // Loading state
        btn.disabled = true;
        btn.innerHTML = '⏳ Memeriksa...';
        result.classList.remove('show');

        fetch('{{ route("cek-nik") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ nik: nik })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '🔍 Cek NIK';

            if (data.status === 'found') {
                // NIK terdaftar & belum punya akun
                input.classList.add('success');
                input.classList.remove('error');

                let dataHtml = '<div class="cek-result-data">';
                if (data.data) {
                    dataHtml += `<div class="cek-result-data-row"><span class="cek-result-data-label">Nama</span><span class="cek-result-data-value">${data.data.nama || '-'}</span></div>`;
                    dataHtml += `<div class="cek-result-data-row"><span class="cek-result-data-label">RT / RW</span><span class="cek-result-data-value">${data.data.rt || '-'} / ${data.data.rw || '-'}</span></div>`;
                }
                dataHtml += '</div>';

                showCekResult('found', '✅', 'NIK Terdaftar oleh RT',
                    'NIK Anda sudah terdaftar. Silakan lanjutkan mengisi form di bawah.' + dataHtml);

            } else if (data.status === 'already_registered') {
                // NIK sudah terdaftar & sudah punya akun
                input.classList.remove('success', 'error');

                let dataHtml = '<div class="cek-result-data">';
                dataHtml += `<div class="cek-result-data-row"><span class="cek-result-data-label">Nama Terdaftar</span><span class="cek-result-data-value">${data.nama || '-'}</span></div>`;
                dataHtml += '</div>';

                showCekResult('warning', '⚠️', 'NIK Sudah Punya Akun',
                    'NIK ini sudah terdaftar sebagai akun pelayanan. Silakan login menggunakan akun yang sudah ada.' + dataHtml);

                // Tambahkan tombol login
                setTimeout(() => {
                    const r = document.getElementById('cekResult');
                    const btnLogin = document.createElement('a');
                    btnLogin.href = '{{ route("login") }}';
                    btnLogin.style.cssText = 'display:inline-block; margin-top:10px; padding:8px 18px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700;';
                    btnLogin.textContent = '🔑 Login Sekarang';
                    r.appendChild(btnLogin);
                }, 100);

            } else {
                // NIK tidak terdaftar
                input.classList.add('error');
                input.classList.remove('success');

                showCekResult('notfound', '❌', 'NIK Tidak Terdaftar',
                    'NIK Anda belum terdaftar oleh Ketua RT. Silakan hubungi Ketua RT setempat untuk mendaftarkan NIK Anda terlebih dahulu.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '🔍 Cek NIK';
            console.error(err);
            showCekResult('warning', '⚠️', 'Gagal Memeriksa',
                'Terjadi kesalahan saat memeriksa NIK. Silakan coba lagi.');
        });
    }

    // Helper: tampilkan hasil cek
    function showCekResult(type, icon, title, message) {
        const result = document.getElementById('cekResult');
        result.className = 'cek-result show ' + type;
        result.innerHTML = `
            <div class="cek-result-header">
                <div class="cek-result-icon">${icon}</div>
                <div>
                    <div class="cek-result-title">${title}</div>
                    <div class="cek-result-message">${message}</div>
                </div>
            </div>
        `;
    }

    function hideCekResult() {
        const result = document.getElementById('cekResult');
        result.classList.remove('show');
        document.getElementById('nik').classList.remove('success', 'error');
    }

    // Modal
    function bukaModalSyarat(e) { e.preventDefault(); e.stopPropagation(); document.getElementById('modalSyarat').classList.add('active'); }
    function bukaModalPrivasi(e) { e.preventDefault(); e.stopPropagation(); document.getElementById('modalPrivasi').classList.add('active'); }
    function tutupModal(id) { document.getElementById(id).classList.remove('active'); }

    function setujuDanTutup(modalId) {
        document.getElementById('cbSyarat').checked = true;
        updateSyaratUI();
        tutupModal(modalId);
    }

    document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(function(m) {
                m.classList.remove('active');
            });
        }
    });

    // Checkbox syarat
    const cbSyarat = document.getElementById('cbSyarat');
    const btnRegister = document.getElementById('btnRegister');
    const syaratBox = document.getElementById('syaratBox');

    function updateSyaratUI() {
        if (cbSyarat.checked) {
            syaratBox.classList.add('checked');
            btnRegister.disabled = false;
        } else {
            syaratBox.classList.remove('checked');
            btnRegister.disabled = true;
        }
    }

    cbSyarat?.addEventListener('change', updateSyaratUI);
    updateSyaratUI();

    // Submit
    document.getElementById('formRegister')?.addEventListener('submit', function(e) {
        if (!cbSyarat.checked) {
            e.preventDefault();
            alert('Anda harus menyetujui Syarat & Ketentuan terlebih dahulu.');
            return;
        }
        btnRegister.disabled = true;
        btnRegister.innerHTML = '⏳ Memproses...';
    });
</script>

</body>
</html>