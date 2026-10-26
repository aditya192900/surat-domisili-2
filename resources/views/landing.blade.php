<!DOCTYPE html>
<html lang="id">
<head>
    @php $appType = request('app', 'warga'); @endphp
<link rel="manifest" href="{{ asset('manifest-' . $appType . '.json') }}">
<meta name="theme-color" content="{{ $appType === 'rt' ? '#059669' : ($appType === 'kelurahan' ? '#7c3aed' : '#2563eb') }}">
<link rel="apple-touch-icon" href="{{ asset('images/icon-' . $appType . '.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pelayanan Surat Online - Kelurahan Sungai Asam</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f9fafb; color: #111827; line-height: 1.6; }
        a { text-decoration: none; color: inherit; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* NAVBAR */
        .navbar { background: white; border-bottom: 1px solid #e5e7eb; padding: 16px 0; position: sticky; top: 0; z-index: 100; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .navbar-inner { display: flex; justify-content: space-between; align-items: center; }
        .logo { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 18px; color: #1e40af; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #2563eb, #7c3aed); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        .btn-login { padding: 10px 24px; background: #2563eb; color: white; border-radius: 8px; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .btn-login:hover { background: #1d4ed8; }

        /* HERO */
        .hero { background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%); color: white; padding: 80px 0; position: relative; overflow: hidden; }
        .hero::before { content: ''; position: absolute; top: -50%; right: -20%; width: 600px; height: 600px; background: rgba(255,255,255,0.05); border-radius: 50%; }
        .hero::after { content: ''; position: absolute; bottom: -50%; left: -10%; width: 400px; height: 400px; background: rgba(255,255,255,0.05); border-radius: 50%; }
        .hero-inner { position: relative; z-index: 1; text-align: center; }
        .hero h1 { font-size: 42px; font-weight: 800; margin-bottom: 16px; line-height: 1.2; }
        .hero p { font-size: 18px; opacity: 0.9; max-width: 700px; margin: 0 auto 32px; }
        .hero-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-primary { padding: 14px 32px; background: white; color: #1e40af; border-radius: 10px; font-weight: 600; font-size: 15px; transition: 0.2s; box-shadow: 0 4px 14px rgba(0,0,0,0.15); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.25); }
        .btn-secondary { padding: 14px 32px; background: rgba(255,255,255,0.15); color: white; border: 2px solid rgba(255,255,255,0.5); border-radius: 10px; font-weight: 600; font-size: 15px; transition: 0.2s; }
        .btn-secondary:hover { background: rgba(255,255,255,0.25); }

        /* ============ PENGUMUMAN ============ */
        .pengumuman-section { background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); padding: 70px 0; position: relative; overflow: hidden; }
        .pengumuman-section::before { content: ''; position: absolute; top: -80px; right: -80px; width: 300px; height: 300px; background: rgba(139, 92, 246, 0.06); border-radius: 50%; }
        .pengumuman-section::after { content: ''; position: absolute; bottom: -60px; left: -60px; width: 240px; height: 240px; background: rgba(139, 92, 246, 0.06); border-radius: 50%; }
        .pengumuman-header { text-align: center; margin-bottom: 42px; position: relative; z-index: 1; }
        .pengumuman-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; background: white; border-radius: 20px; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.15); margin-bottom: 16px; }
        .pengumuman-badge span:first-child { font-size: 18px; }
        .pengumuman-badge span:last-child { font-size: 12px; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 0.5px; }
        .pengumuman-header h2 { font-size: 30px; font-weight: 800; color: #111827; margin-bottom: 10px; }
        .pengumuman-header p { font-size: 15px; color: #6b7280; max-width: 600px; margin: 0 auto; }
        .pengumuman-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; position: relative; z-index: 1; }
        .pengumuman-card { background: white; border-radius: 16px; padding: 24px; display: block; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #f3f4f6; transition: 0.25s; position: relative; overflow: hidden; }
        .pengumuman-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: linear-gradient(180deg, #8b5cf6, #7c3aed); }
        .pengumuman-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(139, 92, 246, 0.15); border-color: #ddd6fe; }
        .pengumuman-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; flex-wrap: wrap; }
        .pengumuman-kategori { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .pengumuman-tanggal { font-size: 11.5px; color: #9ca3af; }
        .pengumuman-card h3 { font-size: 17px; font-weight: 700; color: #111827; margin-bottom: 10px; line-height: 1.4; }
        .pengumuman-card p { font-size: 13.5px; color: #6b7280; margin-bottom: 16px; line-height: 1.6; }
        .pengumuman-more { font-size: 13px; color: #7c3aed; font-weight: 600; }
        .pengumuman-empty { text-align: center; padding: 40px 20px; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); max-width: 500px; margin: 0 auto; position: relative; z-index: 1; }
        .pengumuman-empty-icon { font-size: 48px; margin-bottom: 12px; }

        /* CEK NIK */
        .cek-nik-section { background: linear-gradient(135deg, #f0f9ff 0%, #ede9fe 100%); padding: 60px 0; position: relative; overflow: hidden; }
        .cek-nik-section::before { content: ''; position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(37, 99, 235, 0.05); border-radius: 50%; }
        .cek-nik-wrapper { max-width: 640px; margin: 0 auto; background: white; border-radius: 20px; padding: 40px 36px; box-shadow: 0 20px 50px rgba(37, 99, 235, 0.12); position: relative; z-index: 1; border: 1px solid #e0e7ff; }
        .cek-nik-header { text-align: center; margin-bottom: 28px; }
        .cek-nik-icon { display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color: white; border-radius: 16px; font-size: 30px; margin-bottom: 16px; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); }
        .cek-nik-header h2 { font-size: 24px; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .cek-nik-header p { font-size: 14px; color: #6b7280; line-height: 1.6; max-width: 480px; margin: 0 auto; }
        .cek-nik-form { margin-bottom: 20px; }
        .cek-nik-input-group { display: flex; gap: 10px; background: #f9fafb; padding: 8px; border-radius: 14px; border: 2px solid #e5e7eb; transition: 0.2s; }
        .cek-nik-input-group:focus-within { border-color: #2563eb; background: white; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
        .cek-nik-input-group input { flex: 1; padding: 14px 18px; border: none; outline: none; font-size: 15px; font-family: monospace; letter-spacing: 2px; background: transparent; color: #111827; border-radius: 8px; }
        .cek-nik-input-group input::placeholder { letter-spacing: 0.5px; color: #9ca3af; font-family: 'Figtree', sans-serif; }
        .cek-nik-input-group button { padding: 14px 24px; background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; font-family: inherit; transition: 0.2s; white-space: nowrap; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3); }
        .cek-nik-input-group button:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4); }
        .cek-nik-input-group button:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .cek-nik-hint { font-size: 12px; color: #9ca3af; text-align: center; margin-top: 12px; }
        .cek-nik-loading { text-align: center; padding: 24px; color: #6b7280; font-size: 13px; }
        .cek-nik-loading .spinner { width: 40px; height: 40px; border: 4px solid #e0e7ff; border-top-color: #2563eb; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 12px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .cek-result-card { border-radius: 16px; padding: 24px; animation: fadeInUp 0.3s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .cek-result-found { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1.5px solid #a7f3d0; }
        .cek-result-notfound { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 1.5px solid #fecaca; }
        .cek-result-registered { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1.5px solid #fcd34d; }
        .cek-result-header { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
        .cek-result-icon { flex-shrink: 0; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; }
        .cek-result-found .cek-result-icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .cek-result-notfound .cek-result-icon { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .cek-result-registered .cek-result-icon { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .cek-result-title { font-size: 16px; font-weight: 800; margin-bottom: 2px; }
        .cek-result-found .cek-result-title { color: #065f46; }
        .cek-result-notfound .cek-result-title { color: #991b1b; }
        .cek-result-registered .cek-result-title { color: #78350f; }
        .cek-result-message { font-size: 13px; line-height: 1.6; }
        .cek-result-found .cek-result-message { color: #047857; }
        .cek-result-notfound .cek-result-message { color: #7f1d1d; }
        .cek-result-registered .cek-result-message { color: #92400e; }
        .cek-data-table { background: white; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .cek-data-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 13px; gap: 12px; }
        .cek-data-row:last-child { border-bottom: none; }
        .cek-data-label { color: #6b7280; font-weight: 500; }
        .cek-data-value { color: #111827; font-weight: 600; text-align: right; }
        .cek-action-btn { display: block; width: 100%; text-align: center; padding: 14px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 14px; transition: 0.2s; margin-top: 8px; }
        .btn-lanjut-daftar { background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color: white; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3); }
        .btn-lanjut-daftar:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4); }
        .btn-login-disini { background: white; color: #1e40af; border: 1.5px solid #bfdbfe; }
        .btn-login-disini:hover { background: #eff6ff; }

        /* STATISTIK */
        .stats-bar { background: white; padding: 30px 0; border-bottom: 1px solid #e5e7eb; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; max-width: 900px; margin: 0 auto; }
        .stat-item { text-align: center; padding: 16px; border-radius: 12px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .stat-item .num { font-size: 32px; font-weight: 800; background: linear-gradient(135deg, #2563eb, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; }
        .stat-item .num .unit { font-size: 16px; font-weight: 600; }
        .stat-item .lbl { font-size: 12px; color: #6b7280; margin-top: 6px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }

        /* LACAK */
        .lacak-box { background: linear-gradient(135deg, #eff6ff 0%, #f5f3ff 100%); padding: 50px 0; }
        .lacak-inner { max-width: 640px; margin: 0 auto; text-align: center; }
        .lacak-inner h3 { font-size: 22px; font-weight: 700; margin-bottom: 8px; }
        .lacak-inner p { color: #6b7280; font-size: 14px; margin-bottom: 20px; }
        .lacak-form { display: flex; gap: 8px; background: white; padding: 8px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .lacak-form input { flex: 1; padding: 12px 16px; border: none; outline: none; font-size: 14px; font-family: monospace; border-radius: 6px; }
        .lacak-form button { padding: 12px 24px; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px; transition: 0.2s; }
        .lacak-form button:hover { background: #1d4ed8; }

        /* SECTIONS */
        .section { padding: 70px 0; }
        .section-alt { background: white; }
        .section-title { text-align: center; margin-bottom: 48px; }
        .section-title h2 { font-size: 30px; font-weight: 700; color: #111827; margin-bottom: 10px; }
        .section-title p { color: #6b7280; font-size: 15px; max-width: 600px; margin: 0 auto; }

        /* CARDS LAYANAN */
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 14px; padding: 28px; transition: 0.25s; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); border-color: #c7d2fe; }
        .card-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 18px; }
        .card h3 { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
        .card p { color: #6b7280; font-size: 14px; margin-bottom: 14px; }
        .card ul { list-style: none; font-size: 13px; color: #4b5563; }
        .card ul li { padding: 4px 0; }
        .card ul li::before { content: '✓ '; color: #10b981; font-weight: 700; }

        /* KEUNGGULAN */
        .keunggulan-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .keunggulan-item { text-align: center; padding: 26px 20px; background: white; border-radius: 14px; border: 1px solid #e5e7eb; transition: 0.2s; }
        .keunggulan-item:hover { border-color: #c7d2fe; transform: translateY(-3px); }
        .keunggulan-icon { font-size: 40px; margin-bottom: 12px; }
        .keunggulan-item h4 { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
        .keunggulan-item p { font-size: 13px; color: #6b7280; }

        /* ALUR */
        .steps { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .step { text-align: center; }
        .step-number { width: 56px; height: 56px; background: linear-gradient(135deg, #2563eb, #7c3aed); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 700; margin: 0 auto 16px; box-shadow: 0 6px 18px rgba(37,99,235,0.3); }
        .step h4 { font-size: 15px; font-weight: 700; margin-bottom: 6px; }
        .step p { font-size: 13px; color: #6b7280; }

        /* FAQ */
        .faq-list { max-width: 800px; margin: 0 auto; }
        .faq-item { background: white; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 12px; overflow: hidden; transition: 0.2s; }
        .faq-item[open] { border-color: #c7d2fe; box-shadow: 0 4px 14px rgba(0,0,0,0.06); }
        .faq-item summary { padding: 18px 22px; cursor: pointer; font-weight: 600; font-size: 15px; list-style: none; display: flex; justify-content: space-between; align-items: center; }
        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary::after { content: '+'; font-size: 24px; color: #2563eb; font-weight: 400; transition: 0.2s; }
        .faq-item[open] summary::after { content: '−'; }
        .faq-item .faq-body { padding: 0 22px 18px; font-size: 14px; color: #4b5563; line-height: 1.7; border-top: 1px solid #f3f4f6; padding-top: 14px; }

        /* INFO */
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px; }
        .info-box { background: white; border-radius: 14px; padding: 24px; border: 1px solid #e5e7eb; }
        .info-box h4 { font-size: 15px; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .info-box p { font-size: 14px; color: #4b5563; line-height: 1.7; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: #dcfce7; color: #166534; }

        /* ============ MAPS ============ */
        .maps-wrapper { margin-top: 32px; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); overflow: hidden; border: 1px solid #e5e7eb; }
        .maps-header { padding: 20px 24px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 14px; }
        .maps-header-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
        .maps-header h4 { font-size: 15px; font-weight: 700; color: #111827; margin: 0; }
        .maps-header p { font-size: 12px; color: #6b7280; margin: 2px 0 0 0; }
        .maps-wrapper iframe { display: block; width: 100%; height: 380px; border: 0; }

        /* FOOTER */
        .footer { background: #111827; color: #9ca3af; padding: 40px 0 20px; margin-top: 40px; }
        .footer-inner { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px; margin-bottom: 30px; }
        .footer h4 { color: white; font-size: 15px; font-weight: 700; margin-bottom: 14px; }
        .footer p, .footer li { font-size: 13px; line-height: 1.8; }
        .footer ul { list-style: none; }
        .footer-bottom { border-top: 1px solid #1f2937; padding-top: 20px; text-align: center; font-size: 12px; }

        /* FLOATING WA */
        .wa-float { position: fixed; bottom: 24px; right: 24px; width: 60px; height: 60px; background: #25d366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; box-shadow: 0 8px 24px rgba(37,211,102,0.4); z-index: 999; transition: 0.2s; animation: waPulse 2s infinite; }
        .wa-float:hover { transform: scale(1.1); box-shadow: 0 10px 30px rgba(37,211,102,0.6); }
        @keyframes waPulse { 0% { box-shadow: 0 0 0 0 rgba(37,211,102,0.6); } 70% { box-shadow: 0 0 0 16px rgba(37,211,102,0); } 100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); } }

        /* RESPONSIVE */
        @media (max-width: 640px) {
            .hero h1 { font-size: 30px; }
            .hero p { font-size: 15px; }
            .btn-primary, .btn-secondary { padding: 12px 24px; font-size: 14px; }
            .section { padding: 50px 0; }
            .section-title h2 { font-size: 24px; }
            .stat-item .num { font-size: 24px; }
            .lacak-form { flex-direction: column; }
            .wa-float { width: 50px; height: 50px; font-size: 24px; bottom: 16px; right: 16px; }
            .cek-nik-wrapper { padding: 30px 22px; }
            .cek-nik-input-group { flex-direction: column; }
            .cek-nik-input-group button { width: 100%; }
            .pengumuman-header h2 { font-size: 24px; }
            .pengumuman-section { padding: 50px 0; }
            .maps-wrapper iframe { height: 300px; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="container navbar-inner">
            <div class="logo">
                <div class="logo-icon">📄</div>
                <span>Pelayanan Surat Kelurahan</span>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-login">📊 Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
            @endauth
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero">
        <div class="container hero-inner">
            <h1>Ajukan Surat Keterangan<br>Tanpa Antre, Kapan Saja</h1>
            <p>
                Sistem pelayanan surat online Kelurahan Sungai Asam. Ajukan Surat Keterangan Domisili, SKTM, atau SKU
                dari rumah, lalu RT dan kelurahan akan memprosesnya.
            </p>

            @auth
                <div class="hero-buttons">
                    <a href="{{ route('dashboard') }}" class="btn-primary">📊 Ke Dashboard Saya</a>
                    <a href="#alur" class="btn-secondary">📖 Lihat Alur</a>
                </div>
                <div style="margin-top:24px; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:50px; backdrop-filter:blur(10px);">
                    <span style="font-size:16px;">👋</span>
                    <span style="font-size:13px; color:white; opacity:0.95;">
                        Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>!
                    </span>
                </div>
            @else
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn-primary">🚀 Daftar Sekarang — Gratis</a>
                    <a href="{{ route('login') }}" class="btn-secondary">🔑 Login</a>
                    <a href="#alur" class="btn-secondary">📖 Lihat Alur</a>
                </div>
                <div style="margin-top:24px; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:50px; backdrop-filter:blur(10px);">
                    <span style="font-size:16px;">ℹ️</span>
                    <span style="font-size:13px; color:white; opacity:0.95;">
                        Belum punya akun? Cukup <strong>daftar 1 menit</strong>, langsung bisa ajukan surat.
                    </span>
                </div>
            @endauth
        </div>
    </section>

    {{-- ============ PENGUMUMAN ============ --}}
    @if(isset($pengumuman) && $pengumuman->count() > 0)
    <section class="pengumuman-section" id="pengumuman">
        <div class="container">
            <div class="pengumuman-header">
                <div class="pengumuman-badge">
                    <span>📢</span>
                    <span>Pengumuman Terbaru</span>
                </div>
                <h2>Informasi dari Kelurahan</h2>
                <p>Update terbaru seputar kegiatan dan pelayanan kelurahan</p>
            </div>

            <div class="pengumuman-grid">
                @foreach($pengumuman as $item)
                    @php $badge = $item->badgeKategori(); @endphp
                    <a href="{{ route('pengumuman.show', $item->id) }}" class="pengumuman-card">
                        <div class="pengumuman-meta">
                            <span class="pengumuman-kategori" style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }};">
                                {{ $badge['icon'] }} {{ $badge['label'] }}
                            </span>
                            <span class="pengumuman-tanggal">
                                {{ $item->tanggal_mulai->translatedFormat('d M Y') }}
                            </span>
                        </div>

                        <h3>{{ $item->judul }}</h3>

                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 120) }}</p>

                        <div class="pengumuman-more">Baca selengkapnya →</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CEK NIK --}}
    <section class="cek-nik-section" id="cek-nik">
        <div class="container">
            <div class="cek-nik-wrapper">
                <div class="cek-nik-header">
                    <div class="cek-nik-icon">🆔</div>
                    <h2>Cek NIK Anda</h2>
                    <p>
                        Masukkan NIK Anda untuk memastikan data sudah terdaftar oleh Ketua RT.
                        Jika terdaftar, Anda bisa lanjut membuat akun pelayanan.
                    </p>
                </div>

                <form id="formCekNik" class="cek-nik-form" onsubmit="return false;">
                    <div class="cek-nik-input-group">
                        <input type="text" id="inputNik"
                               placeholder="Masukkan 16 digit NIK Anda"
                               maxlength="16" pattern="[0-9]{16}" inputmode="numeric"
                               autocomplete="off">
                        <button type="button" id="btnCekNik" onclick="cekNik()">
                            🔍 Cek NIK
                        </button>
                    </div>
                    <div class="cek-nik-hint">
                        💡 Belum tahu NIK Anda? Lihat di KTP atau Kartu Keluarga.
                    </div>
                </form>

                <div id="cekLoading" style="display:none;" class="cek-nik-loading">
                    <div class="spinner"></div>
                    <div>Sedang memeriksa data...</div>
                </div>

                <div id="cekResult" style="display:none;"></div>
            </div>
        </div>
    </section>

    {{-- STATISTIK LIVE --}}
    <section class="stats-bar">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="num">{{ $statistik['total'] }}</div>
                    <div class="lbl">Total Pengajuan</div>
                </div>
                <div class="stat-item">
                    <div class="num">{{ $statistik['selesai'] }}</div>
                    <div class="lbl">Surat Selesai</div>
                </div>
                <div class="stat-item">
                    <div class="num">{{ $statistik['bulan_ini'] }}</div>
                    <div class="lbl">Bulan Ini</div>
                </div>
                <div class="stat-item">
                    <div class="num">{{ $statistik['rata_rata'] }}<span class="unit"> jam</span></div>
                    <div class="lbl">Rata-rata Proses</div>
                </div>
            </div>
        </div>
    </section>

    {{-- CEK STATUS SURAT --}}
    <section class="lacak-box">
        <div class="container">
            <div class="lacak-inner">
                <h3>🔍 Lacak Status Surat Anda</h3>
                <p>Masukkan nomor pengajuan untuk melihat status terkini tanpa perlu login.</p>
                <form method="GET" action="{{ route('lacak') }}" class="lacak-form">
                    <input type="text" name="nomor" placeholder="Contoh: SD-20261008-ABC123"
                           required pattern="SD-.*" title="Nomor pengajuan diawali dengan SD-">
                    <button type="submit">Cek Status</button>
                </form>
                <p style="font-size:12px; color:#9ca3af; margin-top:10px;">
                    Nomor pengajuan bisa dilihat di halaman "Pengajuan Surat Saya" setelah login.
                </p>
            </div>
        </div>
    </section>

       {{-- LAYANAN --}}
       {{-- LAYANAN --}}
    <section class="section section-alt" id="layanan">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Surat yang Tersedia</h2>
                <p>Pilih jenis surat yang Anda butuhkan. Semua proses dilakukan secara online.</p>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:24px;">
                
                <!-- 1. SURAT KETERANGAN TIDAK MAMPU (SKTM) -->
                <div style="background:white; border:1px solid #e5e7eb; border-radius:14px; padding:28px; transition:0.25s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'; this.style.borderColor='#c7d2fe'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e5e7eb'">
                    <div style="width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:18px; background:#fef3c7; color:#b45309;">🤝</div>
                    <h3 style="font-size:18px; font-weight:700; margin-bottom:10px; color:#111827;">Surat Keterangan Tidak Mampu (SKTM)</h3>
                    <p style="color:#6b7280; font-size:13.5px; margin-bottom:16px; line-height:1.5;">Untuk keperluan beasiswa, bantuan sosial, atau keringanan biaya rumah sakit.</p>
                    <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Persyaratan:</div>
                    <ul style="list-style:none; font-size:13px; color:#4b5563; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Pengantar dari RT</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Fotokopi KK dan KTP</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Bukti lunas PBB</li>
                    </ul>
                </div>

                <!-- 2. PENGANTAR NIKAH -->
                <div style="background:white; border:1px solid #e5e7eb; border-radius:14px; padding:28px; transition:0.25s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'; this.style.borderColor='#c7d2fe'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e5e7eb'">
                    <div style="width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:18px; background:#fce7f3; color:#be185d;">💍</div>
                    <h3 style="font-size:18px; font-weight:700; margin-bottom:10px; color:#111827;">Pengantar Nikah</h3>
                    <p style="color:#6b7280; font-size:13.5px; margin-bottom:16px; line-height:1.5;">Untuk keperluan administrasi pernikahan di KUA.</p>
                    <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Persyaratan:</div>
                    <ul style="list-style:none; font-size:13px; color:#4b5563; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Pengantar dari RT</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Bukti lunas PBB</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Fotokopi KTP & KK (yang bersangkutan, orang tua, & orang tua calon pasangan)</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Pas foto ukuran 3x4 sebanyak 3 lembar</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Surat keterangan kesehatan</li>
                    </ul>
                </div>

                <!-- 3. SURAT PERNYATAAN AHLI WARIS -->
                <div style="background:white; border:1px solid #e5e7eb; border-radius:14px; padding:28px; transition:0.25s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'; this.style.borderColor='#c7d2fe'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e5e7eb'">
                    <div style="width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:18px; background:#e0e7ff; color:#4338ca;">📜</div>
                    <h3 style="font-size:18px; font-weight:700; margin-bottom:10px; color:#111827;">Surat Pernyataan Ahli Waris</h3>
                    <p style="color:#6b7280; font-size:13.5px; margin-bottom:16px; line-height:1.5;">Untuk keperluan pembagian atau penentuan ahli waris.</p>
                    <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Persyaratan:</div>
                    <ul style="list-style:none; font-size:13px; color:#4b5563; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Akta kematian</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Fotokopi KTP & KK para ahli waris</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Pengantar dari RT</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Fotokopi KTP 2 (dua) orang saksi</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Bukti lunas PBB tahun berjalan</li>
                    </ul>
                </div>

                <!-- 4. SURAT KETERANGAN DOMISILI -->
                <div style="background:white; border:1px solid #e5e7eb; border-radius:14px; padding:28px; transition:0.25s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'; this.style.borderColor='#c7d2fe'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e5e7eb'">
                    <div style="width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:18px; background:#dbeafe; color:#1e40af;">🏠</div>
                    <h3 style="font-size:18px; font-weight:700; margin-bottom:10px; color:#111827;">Surat Keterangan Domisili</h3>
                    <p style="color:#6b7280; font-size:13.5px; margin-bottom:16px; line-height:1.5;">Untuk keterangan tempat tinggal, pembuatan rekening bank, KTP, dan lainnya.</p>
                    <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Persyaratan:</div>
                    <ul style="list-style:none; font-size:13px; color:#4b5563; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Pengantar dari RT</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Fotokopi KTP dan KK</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Bukti lunas PBB tahun 2026</li>
                    </ul>
                </div>

                <!-- 5. SURAT KETERANGAN BELUM MENIKAH -->
                <div style="background:white; border:1px solid #e5e7eb; border-radius:14px; padding:28px; transition:0.25s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'; this.style.borderColor='#c7d2fe'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e5e7eb'">
                    <div style="width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:18px; background:#f3e8ff; color:#7e22ce;">👤</div>
                    <h3 style="font-size:18px; font-weight:700; margin-bottom:10px; color:#111827;">Surat Keterangan Belum Menikah</h3>
                    <p style="color:#6b7280; font-size:13.5px; margin-bottom:16px; line-height:1.5;">Untuk keterangan status belum menikah secara resmi.</p>
                    <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Persyaratan:</div>
                    <ul style="list-style:none; font-size:13px; color:#4b5563; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Pengantar dari RT</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Fotokopi KTP dan KK</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Bukti lunas PBB tahun 2026 / tahun berjalan</li>
                    </ul>
                </div>

                <!-- 6. SURAT KETERANGAN BELUM MEMILIKI RUMAH -->
                <div style="background:white; border:1px solid #e5e7eb; border-radius:14px; padding:28px; transition:0.25s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'; this.style.borderColor='#c7d2fe'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e5e7eb'">
                    <div style="width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:26px; margin-bottom:18px; background:#d1fae5; color:#065f46;">🏡</div>
                    <h3 style="font-size:18px; font-weight:700; margin-bottom:10px; color:#111827;">Surat Keterangan Belum Memiliki Rumah</h3>
                    <p style="color:#6b7280; font-size:13.5px; margin-bottom:16px; line-height:1.5;">Untuk keterangan status belum memiliki tempat tinggal pribadi.</p>
                    <div style="font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Persyaratan:</div>
                    <ul style="list-style:none; font-size:13px; color:#4b5563; padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Pengantar dari RT</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Fotokopi KTP dan KK</li>
                        <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:700;">✓</span> Bukti lunas PBB tahun 2026 / tahun berjalan</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>
    
    {{-- KEUNGGULAN --}}
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Kenapa Pakai Layanan Online?</h2>
                <p>Lebih cepat, transparan, dan bisa dipantau kapan saja.</p>
            </div>

            <div class="keunggulan-grid">
                <div class="keunggulan-item">
                    <div class="keunggulan-icon">⚡</div>
                    <h4>Cepat</h4>
                    <p>Surat selesai dalam hitungan jam, tanpa perlu antre lama di kantor.</p>
                </div>
                <div class="keunggulan-item">
                    <div class="keunggulan-icon">🔒</div>
                    <h4>Aman</h4>
                    <p>Data Anda dienkripsi dan hanya bisa diakses petugas yang berwenang.</p>
                </div>
                <div class="keunggulan-item">
                    <div class="keunggulan-icon">🏠</div>
                    <h4>Tanpa Antre</h4>
                    <p>Ajukan dari rumah kapan saja, 24 jam sehari, 7 hari seminggu.</p>
                </div>
                <div class="keunggulan-item">
                    <div class="keunggulan-icon">📱</div>
                    <h4>Real-time</h4>
                    <p>Pantau status surat Anda kapan saja, di mana saja, dari HP.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ALUR --}}
    <section class="section section-alt" id="alur">
        <div class="container">
            <div class="section-title">
                <h2>Alur Pengajuan Surat</h2>
                <p>Hanya 4 langkah mudah, surat Anda siap diambil.</p>
            </div>

            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h4>Warga Mengajukan</h4>
                    <p>Login, pilih jenis surat, upload KTP & KK, lalu kirim pengajuan</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h4>Verifikasi RT</h4>
                    <p>Ketua RT memverifikasi kebenaran data dan domisili Anda</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h4>Proses Kelurahan</h4>
                    <p>Petugas kelurahan memproses dan menerbitkan surat</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h4>Surat Selesai</h4>
                    <p>Anda menerima notifikasi dan bisa mengambil surat</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Pertanyaan yang Sering Diajukan</h2>
                <p>Belum jelas? Mungkin jawabannya ada di bawah ini.</p>
            </div>

            <div class="faq-list">
                <details class="faq-item">
                    <summary>Berapa lama proses pengajuan surat?</summary>
                    <div class="faq-body">
                        Rata-rata <strong>kurang dari 1 hari kerja</strong> setelah diverifikasi RT. Kalau semua berkas lengkap,
                        surat bisa selesai dalam hitungan jam saja.
                    </div>
                </details>
                <details class="faq-item">
                    <summary>Apakah saya harus datang ke kelurahan?</summary>
                    <div class="faq-body">
                        Untuk <strong>pengajuan, tidak perlu</strong> — cukup dari HP atau komputer Anda.
                        Namun untuk <strong>pengambilan surat fisik</strong> yang sudah ditandatangani, Anda perlu datang
                        ke kelurahan atau melalui RT setempat.
                    </div>
                </details>
                <details class="faq-item">
                    <summary>Bagaimana kalau surat saya ditolak?</summary>
                    <div class="faq-body">
                        Anda akan menerima <strong>notifikasi + alasan penolakan</strong> di halaman "Pengajuan Surat Saya".
                        Perbaiki sesuai saran, lalu ajukan ulang. Tidak ada biaya tambahan.
                    </div>
                </details>
                <details class="faq-item">
                    <summary>Apakah bisa cek status surat tanpa login?</summary>
                    <div class="faq-body">
                        Bisa! Cukup masukkan <strong>nomor pengajuan</strong> di kolom "Lacak Status Surat" di halaman ini.
                    </div>
                </details>
                <details class="faq-item">
                    <summary>Bagaimana cara mendaftar akun?</summary>
                    <div class="faq-body">
                        Cek dulu NIK Anda di section <strong>"Cek NIK"</strong> di halaman ini. Kalau NIK sudah terdaftar oleh RT,
                        Anda bisa lanjut daftar akun. Kalau belum, hubungi Ketua RT untuk input data Anda.
                    </div>
                </details>
                <details class="faq-item">
                    <summary>Apakah data KTP & KK saya aman?</summary>
                    <div class="faq-body">
                        Ya. Dokumen Anda disimpan di <strong>storage privat</strong> yang tidak bisa diakses publik.
                        Hanya petugas berwenang (RT sesuai wilayah Anda + petugas kelurahan) yang bisa melihatnya.
                    </div>
                </details>
                <details class="faq-item">
                    <summary>Bagaimana kalau lupa password?</summary>
                    <div class="faq-body">
                        Klik <strong>"Lupa Password"</strong> di halaman login. Anda akan menerima tautan reset melalui email.
                    </div>
                </details>
                <details class="faq-item">
                    <summary>Apakah layanan ini gratis?</summary>
                    <div class="faq-body">
                        <strong>100% gratis.</strong> Semua layanan surat keterangan di kelurahan tidak dipungut biaya.
                    </div>
                </details>
            </div>
        </div>
    </section>

    {{-- INFO & KONTAK --}}
    <section class="section section-alt">
        <div class="container">
            <div class="section-title">
                <h2>Informasi & Kontak</h2>
                <p>Butuh bantuan? Hubungi kami melalui kontak di bawah ini.</p>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <h4>🕒 Jam Operasional</h4>
                    <p>
                        <strong>Senin - Jumat:</strong><br>
                        08.00 - 15.00 WIB<br><br>
                        <span class="badge">Pelayanan Online 24 Jam</span>
                    </p>
                </div>
                <div class="info-box">
                    <h4>📍 Alamat Kelurahan</h4>
                    <p>
                        Jl. Sungai Asam No. 1<br>
                        Kecamatan Danau Sipin, Kota Jambi<br>
                        Kode Pos 36124
                    </p>
                </div>
                <div class="info-box">
                    <h4>📞 Kontak & Bantuan</h4>
                    <p>
                        Telp: (0741) 123456<br>
                        WhatsApp: 0812-3456-7890<br>
                        Email: kelurahan.sungaiasam@jambikota.go.id
                    </p>
                </div>
            </div>

            {{-- ============ PETA LOKASI ============ --}}
            <div class="maps-wrapper">
                <div class="maps-header">
                    <div class="maps-header-icon">📍</div>
                    <div>
                        <h4>Lokasi Kantor Kelurahan</h4>
                        <p>Jl. Sungai Asam No. 1, Kecamatan Danau Sipin, Kota Jambi</p>
                    </div>
                </div>
                {{-- GANTI src DI BAWAH DENGAN KODE EMBED DARI GOOGLE MAPS KAMU --}}
             <iframe
    src="https://maps.google.com/maps?q=-1.5989956,103.6204404&hl=id&z=17&output=embed"
    width="100%"
    height="380"
    style="border:0; display:block;"
    allowfullscreen=""
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade">
</iframe>
            </div>
        </div>
    </section>
<!-- ========================================== -->
<!-- BAGIAN INSTALL APLIKASI PWA (DINAMIS) -->
<!-- ========================================== -->
<section style="padding: 60px 20px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <div style="max-width: 900px; margin: 0 auto; text-align: center;">
        
        <!-- Judul -->
        <div style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 28px;">📲</span>
            <h2 style="font-size: 26px; font-weight: 800; color: #111827; margin: 0;">Install Aplikasi di HP Anda</h2>
        </div>
        <p style="color: #6b7280; font-size: 15px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.6;">
            Akses layanan surat lebih cepat layaknya aplikasi *native* di Android atau iOS. Pilih jenis aplikasi sesuai peran Anda.
        </p>

        <!-- Tombol-tombol -->
        <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">

            <!-- Tombol Aplikasi Warga -->
            <a href="{{ url('/?app=warga') }}" 
               style="display: flex; flex-direction: column; align-items: center; gap: 8px; width: 200px; padding: 24px 20px; background: white; border: 2px solid #eff6ff; border-radius: 16px; text-decoration: none; box-shadow: 0 4px 14px rgba(0,0,0,0.04); transition: 0.2s;"
               onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#bfdbfe'; this.style.boxShadow='0 10px 25px rgba(37,99,235,0.15)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#eff6ff'; this.style.boxShadow='0 4px 14px rgba(0,0,0,0.04)'">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 4px; box-shadow: 0 4px 10px rgba(37,99,235,0.3);">🏠</div>
                <div style="font-weight: 800; font-size: 15px; color: #1e40af;">Aplikasi Warga</div>
                <div style="font-size: 12px; color: #6b7280;">Ajukan & Lacak Surat</div>
            </a>

            <!-- Tombol Aplikasi RT -->
            <a href="{{ url('/login?app=rt') }}" 
               style="display: flex; flex-direction: column; align-items: center; gap: 8px; width: 200px; padding: 24px 20px; background: white; border: 2px solid #ecfdf5; border-radius: 16px; text-decoration: none; box-shadow: 0 4px 14px rgba(0,0,0,0.04); transition: 0.2s;"
               onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#a7f3d0'; this.style.boxShadow='0 10px 25px rgba(16,185,129,0.15)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#ecfdf5'; this.style.boxShadow='0 4px 14px rgba(0,0,0,0.04)'">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981 0%, #047857 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 4px; box-shadow: 0 4px 10px rgba(16,185,129,0.3);">📋</div>
                <div style="font-weight: 800; font-size: 15px; color: #065f46;">Aplikasi RT</div>
                <div style="font-size: 12px; color: #6b7280;">Verifikasi Surat Warga</div>
            </a>

            <!-- Tombol Aplikasi Kelurahan -->
            <a href="{{ url('/login?app=kelurahan') }}" 
               style="display: flex; flex-direction: column; align-items: center; gap: 8px; width: 200px; padding: 24px 20px; background: white; border: 2px solid #f5f3ff; border-radius: 16px; text-decoration: none; box-shadow: 0 4px 14px rgba(0,0,0,0.04); transition: 0.2s;"
               onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#ddd6fe'; this.style.boxShadow='0 10px 25px rgba(139,92,246,0.15)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#f5f3ff'; this.style.boxShadow='0 4px 14px rgba(0,0,0,0.04)'">
                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6 0%, #5b21b6 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 4px; box-shadow: 0 4px 10px rgba(139,92,246,0.3);">🏛️</div>
                <div style="font-weight: 800; font-size: 15px; color: #5b21b6;">Aplikasi Kelurahan</div>
                <div style="font-size: 12px; color: #6b7280;">Proses & Terbitkan Surat</div>
            </a>

        </div>

        <!-- Info cara install -->
        <div style="margin-top: 30px; display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: white; border-radius: 20px; border: 1px dashed #cbd5e1;">
            <span style="font-size: 16px;">💡</span>
            <span style="font-size: 12.5px; color: #64748b;">Klik tombol di atas, lalu tekan <strong>"Install"</strong> pada browser Anda (Chrome/Safari).</span>
        </div>

    </div>
</section>
    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div>
                    <h4>📄 Pelayanan Surat Kelurahan</h4>
                    <p>Sistem pelayanan surat online untuk warga Kelurahan Sungai Asam. Cepat, transparan, dan dapat dipantau kapan saja.</p>
                </div>
                <div>
                    <h4>Layanan</h4>
                    <ul>
                        <li>Surat Keterangan Domisili</li>
                        <li>Surat Keterangan Tidak Mampu</li>
                        <li>Surat Keterangan Usaha</li>
                    </ul>
                </div>
                <div>
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="#layanan" style="color:#9ca3af;">Layanan</a></li>
                        <li><a href="#alur" style="color:#9ca3af;">Alur Pengajuan</a></li>
                        <li><a href="{{ route('lacak') }}" style="color:#9ca3af;">Lacak Surat</a></li>
                        <li><a href="{{ route('login') }}" style="color:#9ca3af;">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Kontak</h4>
                    <ul>
                        <li>Telp: (0741) 123456</li>
                        <li>Email: kelurahan.sungaiasam@jambikota.go.id</li>
                        <li>Senin-Jumat: 08.00-15.00</li>
                    </ul>
                </div>
            </div>
                           <div class="footer-bottom">
                © {{ date('Y') }} Kelurahan Sungai Asam. Sistem Pelayanan Surat Digital.
                <div style="margin-top:6px; font-size:11.5px; color:#6b7280;">
                    Developed by <a href="#" style="color:#a5b4fc; font-weight:700; text-decoration:none;">M. Aditya Herdyan</a>
                </div>
            </div>
            </div>
        </div>
    </footer>

    {{-- FLOATING WHATSAPP --}}
    <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20pelayanan%20surat"
       target="_blank" class="wa-float" title="Chat WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32" height="32">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    {{-- SCRIPT CEK NIK --}}
    <script>
        function cekNik() {
            const input = document.getElementById('inputNik');
            const btn = document.getElementById('btnCekNik');
            const loading = document.getElementById('cekLoading');
            const result = document.getElementById('cekResult');
            const nik = input.value.trim();

            if (!nik) {
                alert('Silakan masukkan NIK terlebih dahulu.');
                input.focus();
                return;
            }
            if (!/^\d{16}$/.test(nik)) {
                alert('NIK harus terdiri dari 16 digit angka.');
                input.focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '⏳ Memeriksa...';
            loading.style.display = 'block';
            result.style.display = 'none';

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
                loading.style.display = 'none';
                btn.disabled = false;
                btn.innerHTML = '🔍 Cek NIK';

                let html = '';

                if (data.status === 'found') {
                    html = `
                        <div class="cek-result-card cek-result-found">
                            <div class="cek-result-header">
                                <div class="cek-result-icon">✓</div>
                                <div>
                                    <div class="cek-result-title">${data.title}</div>
                                    <div class="cek-result-message">${data.message}</div>
                                </div>
                            </div>
                            <div class="cek-data-table">
                                <div class="cek-data-row">
                                    <span class="cek-data-label">Nama Lengkap</span>
                                    <span class="cek-data-value">${data.data.nama}</span>
                                </div>
                                <div class="cek-data-row">
                                    <span class="cek-data-label">Jenis Kelamin</span>
                                    <span class="cek-data-value">${data.data.jenis_kelamin}</span>
                                </div>
                                <div class="cek-data-row">
                                    <span class="cek-data-label">Tempat / Tgl Lahir</span>
                                    <span class="cek-data-value">${data.data.tempat_lahir}, ${data.data.tanggal_lahir}</span>
                                </div>
                                <div class="cek-data-row">
                                    <span class="cek-data-label">Umur</span>
                                    <span class="cek-data-value">${data.data.umur}</span>
                                </div>
                                <div class="cek-data-row">
                                    <span class="cek-data-label">Alamat</span>
                                    <span class="cek-data-value">${data.data.alamat}</span>
                                </div>
                                <div class="cek-data-row">
                                    <span class="cek-data-label">RT / RW</span>
                                    <span class="cek-data-value">${data.data.rt} / ${data.data.rw}</span>
                                </div>
                            </div>
                            <a href="${data.register_url}" class="cek-action-btn btn-lanjut-daftar">
                                🚀 Lanjutkan Pendaftaran Akun →
                            </a>
                        </div>
                    `;
                } else if (data.status === 'already_registered') {
                    html = `
                        <div class="cek-result-card cek-result-registered">
                            <div class="cek-result-header">
                                <div class="cek-result-icon">⚠️</div>
                                <div>
                                    <div class="cek-result-title">${data.title}</div>
                                    <div class="cek-result-message">${data.message}</div>
                                </div>
                            </div>
                            <div class="cek-data-table">
                                <div class="cek-data-row">
                                    <span class="cek-data-label">Nama Terdaftar</span>
                                    <span class="cek-data-value">${data.nama}</span>
                                </div>
                            </div>
                            <a href="${data.login_url}" class="cek-action-btn btn-login-disini">
                                🔑 Login Sekarang →
                            </a>
                        </div>
                    `;
                } else {
                    html = `
                        <div class="cek-result-card cek-result-notfound">
                            <div class="cek-result-header">
                                <div class="cek-result-icon">✕</div>
                                <div>
                                    <div class="cek-result-title">${data.title}</div>
                                    <div class="cek-result-message">${data.message}</div>
                                </div>
                            </div>
                            <div style="margin-top:16px; padding:12px 16px; background:white; border-radius:10px; font-size:12.5px; color:#7f1d1d; line-height:1.6;">
                                <strong>Langkah selanjutnya:</strong><br>
                                1. Hubungi Ketua RT setempat<br>
                                2. Minta RT untuk mendaftarkan data Anda di sistem<br>
                                3. Setelah terdaftar, coba cek NIK kembali
                            </div>
                        </div>
                    `;
                }

                result.innerHTML = html;
                result.style.display = 'block';
            })
            .catch(err => {
                loading.style.display = 'none';
                btn.disabled = false;
                btn.innerHTML = '🔍 Cek NIK';
                alert('Terjadi kesalahan. Silakan coba lagi.');
                console.error(err);
            });
        }

        document.getElementById('inputNik')?.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
        });

        document.getElementById('inputNik')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cekNik();
            }
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