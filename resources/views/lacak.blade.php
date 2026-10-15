<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Surat - Pelayanan Kelurahan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f9fafb; color: #111827; line-height: 1.6; }
        a { text-decoration: none; color: inherit; }
        .container { max-width: 720px; margin: 0 auto; padding: 0 20px; }
        .navbar { background: white; border-bottom: 1px solid #e5e7eb; padding: 16px 0; }
        .navbar-inner { display: flex; justify-content: space-between; align-items: center; }
        .logo { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 16px; color: #1e40af; }
        .logo-icon { width: 32px; height: 32px; background: linear-gradient(135deg, #2563eb, #7c3aed); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; }
        .btn-back { font-size: 13px; color: #6b7280; padding: 8px 16px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .btn-back:hover { background: #f9fafb; }
        .content { padding: 40px 0 60px; }
        .card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); overflow: hidden; }
        .card-header { padding: 32px; text-align: center; color: white; }
        .card-header.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .card-header.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .card-header.danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .card-header .icon { font-size: 56px; margin-bottom: 8px; }
        .card-header h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
        .card-header p { font-size: 13px; opacity: 0.9; }
        .card-body { padding: 32px; }
        .row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .row:last-child { border-bottom: none; }
        .row .lbl { color: #6b7280; }
        .row .val { font-weight: 600; text-align: right; }
        .row .val.mono { font-family: monospace; }
        .timeline { margin-top: 24px; padding-left: 28px; position: relative; }
        .timeline-item { position: relative; padding-bottom: 20px; border-left: 2px solid #e5e7eb; padding-left: 24px; }
        .timeline-item:last-child { border-left: 2px solid transparent; }
        .timeline-item.done { border-left-color: #10b981; }
        .timeline-item.reject { border-left-color: #dc2626; }
        .timeline-item::before { content: ''; position: absolute; left: -9px; top: 0; width: 16px; height: 16px; border-radius: 50%; background: #e5e7eb; border: 3px solid white; box-shadow: 0 0 0 2px #e5e7eb; }
        .timeline-item.done::before { background: #10b981; box-shadow: 0 0 0 2px #10b981; }
        .timeline-item.reject::before { background: #dc2626; box-shadow: 0 0 0 2px #dc2626; }
        .timeline-item h4 { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
        .timeline-item .time { font-size: 12px; color: #9ca3af; }
        .alert-box { padding: 14px 18px; border-radius: 10px; margin-top: 16px; font-size: 13px; }
        .alert-box.info { background: #eff6ff; border-left: 4px solid #2563eb; color: #1e40af; }
        .alert-box.danger { background: #fef2f2; border-left: 4px solid #dc2626; color: #991b1b; }
        .btn-primary { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; border-radius: 8px; font-weight: 600; font-size: 14px; margin-top: 20px; }
        .btn-primary:hover { background: #1d4ed8; }
        .search-box { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin-bottom: 24px; }
        .search-box form { display: flex; gap: 8px; }
        .search-box input { flex: 1; padding: 12px 16px; border: 1px solid #e5e7eb; border-radius: 8px; font-family: monospace; font-size: 14px; }
        .search-box button { padding: 12px 24px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .search-box button:hover { background: #1d4ed8; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="container navbar-inner">
            <div class="logo">
                <div class="logo-icon">📄</div>
                <span>Pelayanan Surat Kelurahan</span>
            </div>
            <a href="{{ route('landing') }}" class="btn-back">← Beranda</a>
        </div>
    </nav>

    <div class="content">
        <div class="container">

            <div class="search-box">
                <form method="GET" action="{{ route('lacak') }}">
                    <input type="text" name="nomor" value="{{ request('nomor') }}"
                           placeholder="Masukkan nomor pengajuan (contoh: SD-20261008-ABC123)" required>
                    <button type="submit">🔍 Lacak</button>
                </form>
            </div>

            @if ($dicari && $surat)
                @php
                    $cfg = [
                        'diajukan_ke_rt'     => ['class' => 'warning', 'icon' => '⏳', 'title' => 'Menunggu Verifikasi RT',   'sub' => 'Pengajuan Anda sudah masuk ke RT'],
                        'diverifikasi_rt'    => ['class' => 'warning', 'icon' => '📋', 'title' => 'Diverifikasi RT',          'sub' => 'Sedang diproses oleh petugas kelurahan'],
                        'diproses_kelurahan' => ['class' => 'warning', 'icon' => '⚙️', 'title' => 'Sedang Diproses',           'sub' => 'Surat Anda sedang diproses kelurahan'],
                        'selesai'            => ['class' => 'success', 'icon' => '✓',  'title' => 'Surat Selesai',            'sub' => 'Surat Anda siap diambil / diunduh'],
                        'ditolak_rt'         => ['class' => 'danger',  'icon' => '✕',  'title' => 'Ditolak oleh RT',           'sub' => 'Silakan periksa alasan di bawah'],
                        'ditolak_kelurahan'  => ['class' => 'danger',  'icon' => '✕',  'title' => 'Ditolak oleh Kelurahan',    'sub' => 'Silakan periksa alasan di bawah'],
                    ];
                    $c = $cfg[$surat->status] ?? ['class' => 'warning', 'icon' => '·', 'title' => $surat->status, 'sub' => ''];
                @endphp

                <div class="card">
                    <div class="card-header {{ $c['class'] }}">
                        <div class="icon">{{ $c['icon'] }}</div>
                        <h1>{{ $c['title'] }}</h1>
                        <p>{{ $c['sub'] }}</p>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <span class="lbl">Nomor Pengajuan</span>
                            <span class="val mono">{{ $surat->nomor_pengajuan }}</span>
                        </div>
                        <div class="row">
                            <span class="lbl">Nomor Surat</span>
                            <span class="val mono">{{ $surat->nomor_surat ?? '—' }}</span>
                        </div>
                        <div class="row">
                            <span class="lbl">Jenis Layanan</span>
                            <span class="val">{{ $surat->keperluan }}</span>
                        </div>
                        <div class="row">
                            <span class="lbl">Tanggal Diajukan</span>
                            <span class="val">{{ $surat->created_at->format('d F Y, H:i') }} WIB</span>
                        </div>
                        <div class="row">
                            <span class="lbl">Pemohon</span>
                            <span class="val">{{ $surat->warga->name }}</span>
                        </div>

                        {{-- ALASAN PENOLAKAN --}}
                        @if ($surat->status === 'ditolak_rt' && $surat->catatan_rt)
                            <div class="alert-box danger">
                                <strong>Alasan ditolak RT:</strong><br>
                                {{ $surat->catatan_rt }}
                            </div>
                        @endif
                        @if ($surat->status === 'ditolak_kelurahan' && $surat->catatan_kelurahan)
                            <div class="alert-box danger">
                                <strong>Alasan ditolak Kelurahan:</strong><br>
                                {{ $surat->catatan_kelurahan }}
                            </div>
                        @endif

                        {{-- TIMELINE --}}
                        <h3 style="font-size:15px; font-weight:700; margin-top:28px; margin-bottom:16px;">Riwayat Proses</h3>

                        <div class="timeline">
                            <div class="timeline-item done">
                                <h4>Surat Diajukan</h4>
                                <div class="time">{{ $surat->created_at->format('d F Y, H:i') }} WIB</div>
                            </div>

                            @if ($surat->rt_verified_at)
                                @php $reject = $surat->status === 'ditolak_rt'; @endphp
                                <div class="timeline-item {{ $reject ? 'reject' : 'done' }}">
                                    <h4>{{ $reject ? 'Ditolak RT' : 'Diverifikasi RT' }}</h4>
                                    <div class="time">{{ $surat->rt_verified_at->format('d F Y, H:i') }} WIB</div>
                                </div>
                            @endif

                            @if ($surat->kelurahan_processed_at)
                                @php $reject = $surat->status === 'ditolak_kelurahan'; @endphp
                                <div class="timeline-item {{ $reject ? 'reject' : 'done' }}">
                                    <h4>
                                        @if ($reject) Ditolak Kelurahan
                                        @elseif ($surat->status === 'selesai') Surat Selesai
                                        @else Diproses Kelurahan
                                        @endif
                                    </h4>
                                    <div class="time">{{ $surat->kelurahan_processed_at->format('d F Y, H:i') }} WIB</div>
                                </div>
                            @endif
                        </div>

                        @if ($surat->status === 'selesai')
                            <div class="alert-box info">
                                <strong>✓ Surat Anda sudah selesai.</strong><br>
                                Silakan datang ke kelurahan untuk mengambil surat fisik, atau login ke akun Anda
                                untuk mengunduh versi PDF-nya.
                            </div>
                        @endif

                        <div style="text-align:center;">
                            <a href="{{ route('login') }}" class="btn-primary">Login untuk Lihat Detail →</a>
                        </div>
                    </div>
                </div>

            @elseif ($dicari && !$surat)
                <div class="card">
                    <div class="card-header danger">
                        <div class="icon">✕</div>
                        <h1>Surat Tidak Ditemukan</h1>
                        <p>Nomor pengajuan tidak terdaftar di sistem kami</p>
                    </div>
                    <div class="card-body" style="text-align:center;">
                        <p style="color:#6b7280; font-size:14px; margin-bottom:16px;">
                            Nomor pengajuan <strong class="mono">{{ request('nomor') }}</strong> tidak ditemukan.
                            Pastikan nomor yang Anda masukkan sudah benar.
                        </p>
                        <a href="{{ route('lacak') }}" class="btn-primary">Coba Lagi</a>
                    </div>
                </div>
            @endif

        </div>
    </div>

</body>
</html>