<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat - {{ $nomor }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #ede9fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #111827;
        }

        .card {
            background: white;
            border-radius: 20px;
            max-width: 560px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        /* HEADER */
        .card-header {
            padding: 32px 30px 28px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -40px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .header-icon {
            font-size: 56px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
            display: inline-block;
            line-height: 1;
        }
        .header-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }
        .header-sub {
            font-size: 13px;
            opacity: 0.95;
            line-height: 1.5;
            position: relative;
            z-index: 1;
        }

        /* VARIANTS */
        .header-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .header-info    { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); }
        .header-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .header-danger  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .header-neutral { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }

        /* BODY */
        .card-body {
            padding: 26px 30px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            gap: 16px;
        }
        .row:last-child { border-bottom: none; }
        .row-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
            flex-shrink: 0;
        }
        .row-value {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            text-align: right;
            word-break: break-word;
        }
        .row-value.mono {
            font-family: monospace;
            font-size: 13px;
            color: #1e40af;
        }

        /* INFO BOX */
        .info-box {
            margin-top: 20px;
            padding: 16px 18px;
            border-radius: 12px;
            font-size: 13.5px;
            line-height: 1.65;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .info-box-icon {
            flex-shrink: 0;
            font-size: 22px;
            line-height: 1;
        }
        .info-box-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-left: 4px solid #10b981;
            color: #065f46;
        }
        .info-box-info {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }
        .info-box-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left: 4px solid #f59e0b;
            color: #78350f;
        }
        .info-box-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #dc2626;
            color: #991b1b;
        }

        /* TIMELINE MINI */
        .steps {
            margin-top: 24px;
            padding: 20px 22px;
            background: #fafbfc;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
        }
        .steps-title {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 16px;
        }
        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 8px 0;
            position: relative;
        }
        .step:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 32px;
            width: 2px;
            height: calc(100% - 16px);
            background: #e5e7eb;
        }
        .step.done:not(:last-child)::before {
            background: #10b981;
        }
        .step-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            z-index: 1;
        }
        .step-dot.done { background: #10b981; }
        .step-dot.current { background: #3b82f6; animation: pulse 2s infinite; }
        .step-dot.pending { background: #e5e7eb; color: #9ca3af; }
        .step-dot.rejected { background: #dc2626; }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59,130,246,0.6); }
            50% { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
        }
        .step-body {
            flex: 1;
            padding-top: 2px;
        }
        .step-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }
        .step-title.pending { color: #9ca3af; font-weight: 500; }
        .step-date {
            font-size: 11.5px;
            color: #9ca3af;
        }
        .step.done .step-title { color: #065f46; }

        /* FOOTER */
        .card-footer {
            padding: 20px 30px;
            background: #fafbfc;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.6;
        }
        .card-footer strong {
            color: #374151;
            font-weight: 700;
        }

        @media (max-width: 480px) {
            .card-body { padding: 20px 22px; }
            .card-header { padding: 26px 22px 22px; }
            .header-icon { font-size: 46px; }
            .header-title { font-size: 18px; }
            .header-sub { font-size: 12px; }
        }
    </style>
</head>
<body>

@php
    // Tentukan config berdasarkan status
    $cfg = match ($surat ? $surat->status : 'not_found') {
        'diajukan_ke_rt' => [
            'header'  => 'header-warning',
            'icon'    => '⏳',
            'title'   => 'MENUNGGU VERIFIKASI RT',
            'sub'     => 'Surat masih dalam antrian verifikasi Ketua RT',
            'boxType' => 'info-box-warning',
            'boxIcon' => '💡',
            'boxText' => 'Surat Anda sedang menunggu verifikasi Ketua RT. Setelah diverifikasi, QR Code ini akan menampilkan status siap diambil.',
        ],
        'diverifikasi_rt' => [
            'header'  => 'header-success',
            'icon'    => '✅',
            'title'   => 'SURAT SIAP DIAMBIL',
            'sub'     => 'Sudah diverifikasi RT — silakan ambil surat resmi di kelurahan',
            'boxType' => 'info-box-success',
            'boxIcon' => '🏛️',
            'boxText' => 'Surat Anda <strong>sudah diverifikasi oleh Ketua RT</strong>. Silakan datang ke <strong>Kantor Kelurahan</strong> untuk mengambil surat resmi (dengan tanda tangan & stempel Lurah). Bawa KTP asli dan Surat Pengantar RT.',
        ],
        'diproses_kelurahan' => [
            'header'  => 'header-info',
            'icon'    => '⚙️',
            'title'   => 'SEDANG DIPROSES',
            'sub'     => 'Surat sedang diproses oleh petugas kelurahan',
            'boxType' => 'info-box-info',
            'boxIcon' => '⏱️',
            'boxText' => 'Surat Anda sedang diproses oleh petugas kelurahan. Silakan cek kembali QR Code ini secara berkala untuk melihat status terbaru.',
        ],
        'selesai' => [
            'header'  => 'header-success',
            'icon'    => '🎉',
            'title'   => 'SURAT SELESAI',
            'sub'     => 'Surat resmi siap diambil di kelurahan',
            'boxType' => 'info-box-success',
            'boxIcon' => '✅',
            'boxText' => 'Surat Anda <strong>sudah selesai diproses</strong>. Silakan datang ke <strong>Kantor Kelurahan</strong> untuk mengambil surat resmi. Bawa KTP asli sebagai bukti pengambilan.',
        ],
        'ditolak_rt' => [
            'header'  => 'header-danger',
            'icon'    => '❌',
            'title'   => 'SURAT DITOLAK RT',
            'sub'     => 'Pengajuan ditolak oleh Ketua RT',
            'boxType' => 'info-box-danger',
            'boxIcon' => '⚠️',
            'boxText' => 'Surat Anda ditolak oleh Ketua RT. Silakan hubungi RT setempat untuk informasi lebih lanjut atau memperbaiki data yang diperlukan.',
        ],
        'ditolak_kelurahan' => [
            'header'  => 'header-danger',
            'icon'    => '❌',
            'title'   => 'SURAT DITOLAK KELURAHAN',
            'sub'     => 'Pengajuan ditolak oleh petugas kelurahan',
            'boxType' => 'info-box-danger',
            'boxIcon' => '⚠️',
            'boxText' => 'Surat Anda ditolak oleh petugas kelurahan. Silakan hubungi kelurahan untuk informasi lebih lanjut.',
        ],
        default => [
            'header'  => 'header-neutral',
            'icon'    => '❓',
            'title'   => 'DATA TIDAK DITEMUKAN',
            'sub'     => 'Nomor pengajuan tidak terdaftar di sistem',
            'boxType' => 'info-box-warning',
            'boxIcon' => '⚠️',
            'boxText' => 'Nomor pengajuan <strong>' . e($nomor) . '</strong> tidak ditemukan. Pastikan nomor yang Anda scan benar, atau hubungi kelurahan untuk bantuan.',
        ],
    };
@endphp

<div class="card">

    {{-- HEADER --}}
    <div class="card-header {{ $cfg['header'] }}">
        <div class="header-icon">{{ $cfg['icon'] }}</div>
        <div class="header-title">{{ $cfg['title'] }}</div>
        <div class="header-sub">{{ $cfg['sub'] }}</div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        @if ($surat)
            <div class="row">
                <span class="row-label">Nomor Pengajuan</span>
                <span class="row-value mono">{{ $surat->nomor_pengajuan }}</span>
            </div>

            <div class="row">
                <span class="row-label">Nama Pemohon</span>
                <span class="row-value">{{ $surat->warga->name ?? '-' }}</span>
            </div>

            <div class="row">
                <span class="row-label">Jenis Layanan</span>
                <span class="row-value">{{ $surat->keperluan }}</span>
            </div>

            <div class="row">
                <span class="row-label">Tanggal Diajukan</span>
                <span class="row-value">{{ $surat->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
            </div>

            @if ($surat->nomor_surat)
                <div class="row">
                    <span class="row-label">Nomor Surat</span>
                    <span class="row-value mono">{{ $surat->nomor_surat }}</span>
                </div>
            @endif

            {{-- INFO BOX DINAMIS --}}
            <div class="info-box {{ $cfg['boxType'] }}">
                <div class="info-box-icon">{{ $cfg['boxIcon'] }}</div>
                <div>{!! $cfg['boxText'] !!}</div>
            </div>

            {{-- MINI TIMELINE --}}
            <div class="steps">
                <div class="steps-title">📊 Riwayat Proses</div>

                {{-- Step 1: Diajukan --}}
                <div class="step done">
                    <div class="step-dot done">✓</div>
                    <div class="step-body">
                        <div class="step-title">Surat Diajukan</div>
                        <div class="step-date">{{ $surat->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                    </div>
                </div>

                {{-- Step 2: Verifikasi RT --}}
                @if ($surat->rt_verified_at)
                    @php
                        $rtOk = $surat->status !== 'ditolak_rt';
                    @endphp
                    <div class="step {{ $rtOk ? 'done' : '' }}">
                        <div class="step-dot {{ $rtOk ? 'done' : 'rejected' }}">{{ $rtOk ? '✓' : '✕' }}</div>
                        <div class="step-body">
                            <div class="step-title {{ $rtOk ? '' : 'pending' }}">
                                {{ $rtOk ? 'Diverifikasi RT' : 'Ditolak RT' }}
                            </div>
                            <div class="step-date">{{ $surat->rt_verified_at->translatedFormat('d F Y, H:i') }} WIB</div>
                        </div>
                    </div>
                @else
                    <div class="step">
                        <div class="step-dot current">⏳</div>
                        <div class="step-body">
                            <div class="step-title">Menunggu Verifikasi RT</div>
                            <div class="step-date">Sedang diproses</div>
                        </div>
                    </div>
                @endif

                {{-- Step 3: Proses Kelurahan --}}
                @if ($surat->kelurahan_processed_at && in_array($surat->status, ['diproses_kelurahan', 'selesai', 'ditolak_kelurahan']))
                    @php
                        $kelOk = $surat->status !== 'ditolak_kelurahan';
                        $kelTitle = match($surat->status) {
                            'diproses_kelurahan' => 'Diproses Kelurahan',
                            'selesai'            => 'Surat Selesai',
                            'ditolak_kelurahan'  => 'Ditolak Kelurahan',
                            default              => 'Diproses Kelurahan',
                        };
                    @endphp
                    <div class="step {{ $kelOk ? 'done' : '' }}">
                        <div class="step-dot {{ $kelOk ? 'done' : 'rejected' }}">{{ $kelOk ? '✓' : '✕' }}</div>
                        <div class="step-body">
                            <div class="step-title {{ $kelOk ? '' : 'pending' }}">{{ $kelTitle }}</div>
                            <div class="step-date">{{ $surat->kelurahan_processed_at->translatedFormat('d F Y, H:i') }} WIB</div>
                        </div>
                    </div>
                               @endif
            </div>

        @else
            {{-- Nomor tidak ditemukan --}}
            <div class="info-box {{ $cfg['boxType'] }}">
                <div class="info-box-icon">{{ $cfg['boxIcon'] }}</div>
                <div>{!! $cfg['boxText'] !!}</div>
            </div>
        @endif

    </div>

    {{-- FOOTER --}}
    <div class="card-footer">
        🔒 <strong>Sistem Verifikasi Surat Digital</strong><br>
        Kelurahan Sungai Asam • Kecamatan Danau Sipin • Kota Jambi
    </div>

</div>

</body>
</html>