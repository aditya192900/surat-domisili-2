<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat - {{ $nomor }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #ede9fe 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .card { max-width: 640px; margin: 0 auto; background: white; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); overflow: hidden; }
        .header-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 40px 30px; text-align: center; position: relative; overflow: hidden; }
        .header-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 40px 30px; text-align: center; }
        .header-danger  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 40px 30px; text-align: center; }
        .header-icon { font-size: 64px; margin-bottom: 8px; line-height: 1; }
        .header-title { font-size: 26px; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px; }
        .header-sub { font-size: 13px; opacity: 0.9; }
        .body-content { padding: 30px; }
        .info-row { display: flex; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6b7280; font-weight: 500; }
        .info-value { font-weight: 600; color: #111827; text-align: right; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .section-title { font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin: 24px 0 12px 0; display: flex; align-items: center; gap: 8px; }
        .section-title::after { content: ''; flex: 1; height: 1px; background: #e5e7eb; }
        .timeline { position: relative; padding-left: 30px; }
        .timeline-item { position: relative; padding-bottom: 18px; }
        .timeline-item:last-child { padding-bottom: 0; }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #e5e7eb;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #e5e7eb;
        }
        .timeline-item.done::before { background: #10b981; box-shadow: 0 0 0 2px #10b981; }
        .timeline-item.reject::before { background: #dc2626; box-shadow: 0 0 0 2px #dc2626; }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -23px;
            top: 20px;
            width: 2px;
            height: calc(100% - 16px);
            background: #e5e7eb;
        }
        .timeline-item:last-child::after { display: none; }
        .timeline-item.done::after { background: #10b981; }
        .timeline-title { font-weight: 700; font-size: 14px; color: #111827; }
        .timeline-desc { font-size: 12.5px; color: #6b7280; margin-top: 2px; }
        .timeline-item.done .timeline-title { color: #065f46; }
        .timeline-item.reject .timeline-title { color: #991b1b; }
        .alert-box { padding: 14px 18px; border-radius: 10px; margin: 16px 0; font-size: 13px; line-height: 1.6; }
        .alert-info { background: #eff6ff; border-left: 4px solid #2563eb; color: #1e40af; }
        .alert-danger { background: #fef2f2; border-left: 4px solid #dc2626; color: #991b1b; }
        .footer-bar { background: #f9fafb; padding: 18px; text-align: center; font-size: 11px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>

    <div class="card">

        @if ($surat && $surat->status === 'selesai')
            {{-- SURAT SELESAI = VALID --}}
            <div class="header-success">
                <div class="header-icon">✓</div>
                <div class="header-title">SURAT VALID</div>
                <div class="header-sub">Dokumen ini telah diverifikasi dan terdaftar di sistem kelurahan</div>
            </div>

            <div class="body-content">
                <div class="info-row">
                    <span class="info-label">Nomor Pengajuan</span>
                    <span class="info-value" style="font-family:monospace;">{{ $surat->nomor_pengajuan }}</span>
                </div>
                @if ($surat->nomor_surat)
                    <div class="info-row">
                        <span class="info-label">Nomor Surat</span>
                        <span class="info-value" style="font-family:monospace;">{{ $surat->nomor_surat }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Jenis Layanan</span>
                    <span class="info-value">{{ $surat->keperluan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Pemohon</span>
                    <span class="info-value">{{ $surat->warga->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIK</span>
                    <span class="info-value" style="font-family:monospace;">
                        {{ substr($surat->warga->nik, 0, 6) . '******' . substr($surat->warga->nik, -4) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">RT / RW</span>
                    <span class="info-value">{{ $surat->warga->rt }} / {{ $surat->warga->rw }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Terbit</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($surat->kelurahan_processed_at)->translatedFormat('d F Y') }}</span>
                </div>

                <div class="section-title">📊 Riwayat Verifikasi</div>

                <div class="timeline">
                    <div class="timeline-item done">
                        <div class="timeline-title">✓ Surat Diajukan</div>
                        <div class="timeline-desc">{{ $surat->created_at->translatedFormat('d F Y, H:i') }} WIB — oleh {{ $surat->warga->name }}</div>
                    </div>

                    @if ($surat->rt_verified_at)
                        <div class="timeline-item done">
                            <div class="timeline-title">✓ Diverifikasi RT {{ $surat->warga->rt }}</div>
                            <div class="timeline-desc">{{ $surat->rt_verified_at->translatedFormat('d F Y, H:i') }} WIB — oleh {{ $surat->rtVerifier->name ?? '-' }}</div>
                        </div>
                    @endif

                    @if ($surat->kelurahan_processed_at)
                        <div class="timeline-item done">
                            <div class="timeline-title">✓ Diproses & Disahkan Kelurahan</div>
                            <div class="timeline-desc">{{ $surat->kelurahan_processed_at->translatedFormat('d F Y, H:i') }} WIB — oleh {{ $surat->kelurahanProcessor->name ?? '-' }}</div>
                        </div>
                    @endif
                </div>

                <div class="alert-box alert-info">
                    ✓ <strong>Dokumen ini asli dan terverifikasi.</strong><br>
                    Surat dengan nomor pengajuan <span style="font-family:monospace; font-weight:700;">{{ $surat->nomor_pengajuan }}</span> 
                    terdaftar di Sistem Pelayanan Surat Kelurahan dan telah ditandatangani secara sah.
                </div>
            </div>

        @elseif ($surat && in_array($surat->status, ['diverifikasi_rt', 'diproses_kelurahan']))
            {{-- SURAT MASIH DIPROSES --}}
            <div class="header-warning">
                <div class="header-icon">⏳</div>
                <div class="header-title">SURAT DALAM PROSES</div>
                <div class="header-sub">Dokumen ini masih dalam tahap pemrosesan</div>
            </div>

            <div class="body-content">
                <div class="info-row">
                    <span class="info-label">Nomor Pengajuan</span>
                    <span class="info-value" style="font-family:monospace;">{{ $surat->nomor_pengajuan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Pemohon</span>
                    <span class="info-value">{{ $surat->warga->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Saat Ini</span>
                    <span class="info-value">{{ str_replace('_', ' ', $surat->status) }}</span>
                </div>

                <div class="alert-box alert-info">
                    ⏳ Surat dengan nomor pengajuan <strong>{{ $surat->nomor_pengajuan }}</strong> masih dalam proses. 
                    Silakan cek kembali nanti.
                </div>
            </div>

        @elseif ($surat && in_array($surat->status, ['ditolak_rt', 'ditolak_kelurahan']))
            {{-- SURAT DITOLAK --}}
            <div class="header-danger">
                <div class="header-icon">✕</div>
                <div class="header-title">SURAT DITOLAK</div>
                <div class="header-sub">Dokumen ini tidak disahkan</div>
            </div>

            <div class="body-content">
                <div class="info-row">
                    <span class="info-label">Nomor Pengajuan</span>
                    <span class="info-value" style="font-family:monospace;">{{ $surat->nomor_pengajuan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Pemohon</span>
                    <span class="info-value">{{ $surat->warga->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">{{ str_replace('_', ' ', $surat->status) }}</span>
                </div>

                @if ($surat->status === 'ditolak_rt' && $surat->catatan_rt)
                    <div class="alert-box alert-danger">
                        <strong>Alasan ditolak RT:</strong><br>
                        {{ $surat->catatan_rt }}
                    </div>
                @endif
                @if ($surat->status === 'ditolak_kelurahan' && $surat->catatan_kelurahan)
                    <div class="alert-box alert-danger">
                        <strong>Alasan ditolak Kelurahan:</strong><br>
                        {{ $surat->catatan_kelurahan }}
                    </div>
                @endif
            </div>

        @else
            {{-- SURAT TIDAK DITEMUKAN --}}
            <div class="header-danger">
                <div class="header-icon">✕</div>
                <div class="header-title">SURAT TIDAK DITEMUKAN</div>
                <div class="header-sub">Nomor pengajuan tidak terdaftar</div>
            </div>

            <div class="body-content">
                <div class="alert-box alert-danger">
                    Nomor pengajuan <strong style="font-family:monospace;">{{ $nomor }}</strong> tidak terdaftar di sistem kami.<br><br>
                    Kemungkinan penyebab:
                    <ul style="margin:8px 0 0 18px; padding:0;">
                        <li>Nomor yang Anda masukkan salah</li>
                        <li>Surat palsu atau tidak diterbitkan oleh sistem resmi</li>
                    </ul>
                </div>
            </div>
        @endif

        <div class="footer-bar">
            🔒 Sistem Verifikasi Surat Digital<br>
            Kelurahan Sungai Asam • Kecamatan Danau Sipin • Kota Jambi
        </div>

    </div>

</body>
</html>