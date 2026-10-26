<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar RT</title>
    <style>
        @page { margin: 1.2cm 1.8cm; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.4; color: #000; }

        /* KOP */
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 6px; margin-bottom: 12px; }
        .kop h3 { margin: 0; font-size: 12pt; letter-spacing: 0.5px; }
        .kop h2 { margin: 2px 0; font-size: 14pt; letter-spacing: 0.5px; }
        .kop p { margin: 1px 0; font-size: 9pt; }

        /* JUDUL */
        .judul { text-align: center; margin: 14px 0 12px 0; }
        .judul h3 { margin: 0; text-decoration: underline; font-size: 13pt; text-transform: uppercase; letter-spacing: 1.5px; }
        .judul p { margin: 3px 0; font-size: 10pt; font-family: monospace; }

        /* ISI */
        .isi { text-align: justify; }
        .isi p { margin: 10px 0; line-height: 1.5; }

        table.data { width: 100%; margin: 10px 0; }
        table.data td { padding: 3px 0; vertical-align: top; font-size: 11pt; line-height: 1.5; }
        table.data td:first-child { width: 190px; }

        /* FOTO */
        .foto-box { float: right; margin-left: 18px; margin-bottom: 10px; border: 1px solid #999; padding: 3px; }
        .foto-box img { width: 2.7cm; height: 3.6cm; object-fit: cover; display: block; }

        /* TTD SECTION */
        .ttd-section {
            margin-top: 20px;
            text-align: right;
            page-break-inside: avoid;
        }
        .ttd-date { font-size: 11pt; margin-bottom: 2px; }
        .ttd-jabatan { font-size: 11pt; margin-bottom: 10px; }

        .ttd-sign-area {
            position: relative;
            height: 3.4cm;
            width: 4.2cm;
            margin-left: auto;
            margin-right: 20px;
        }
        .stempel-img {
            position: absolute;
            top: 0.2cm;
            left: 0;
            width: 3cm;
            height: 3cm;
            object-fit: contain;
            opacity: 0.8;
            z-index: 1;
        }
        .ttd-img {
            position: absolute;
            top: 0.4cm;
            left: 0.6cm;
            width: 3.8cm;
            height: auto;
            max-height: 2.4cm;
            object-fit: contain;
            z-index: 2;
        }
        .ttd-name {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 8px;
            text-align: right;
            padding-right: 20px;
        }

        /* QR SECTION */
        .qr-section {
            margin-top: 22px;
            padding-top: 12px;
            border-top: 1px dashed #999;
            text-align: center;
            page-break-inside: avoid;
        }
        .qr-box {
            display: inline-block;
            padding: 6px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            vertical-align: middle;
        }
        .qr-box img {
            width: 2.2cm;
            height: 2.2cm;
            display: block;
        }
        .qr-info {
            display: inline-block;
            text-align: left;
            margin-left: 14px;
            vertical-align: middle;
            max-width: 10cm;
        }
        .qr-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }
        .qr-desc {
            font-size: 8.5pt;
            color: #666;
            margin-bottom: 3px;
            line-height: 1.4;
        }
        .qr-url {
            font-family: monospace;
            font-size: 8pt;
            color: #333;
            word-break: break-all;
        }

        /* FOOTER */
        .footer-info { margin-top: 12px; font-size: 8.5pt; color: #555; text-align: center; }
        .footer-info p { margin: 3px 0; }
    </style>
</head>
<body>

    <div class="kop">
        <h3>RUKUN TETANGGA {{ $surat->warga->rt }} / RUKUN WARGA {{ $surat->warga->rw }}</h3>
        <h2>Kelurahan Sungai Asam</h2>
        <p>Kecamatan Danau Sipin, Kota Jambi</p>
        <p>Sekretariat: {{ $surat->warga->alamat ?? 'Jl. Raya Kelurahan No. 1' }}</p>
    </div>

    <div class="judul">
        <h3>Surat Pengantar RT</h3>
        <p>Nomor: 001/RT{{ $surat->warga->rt }}/RW{{ $surat->warga->rw }}/{{ now()->format('Y') }}</p>
    </div>

    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, Ketua RT {{ $surat->warga->rt }} / RW {{ $surat->warga->rw }}, 
           Kelurahan Sungai Asam, Kecamatan Danau Sipin, dengan ini menerangkan bahwa:</p>

       

        <table class="data">
            <tr><td>Nama Lengkap</td><td>: <strong>{{ $surat->warga->name }}</strong></td></tr>
            <tr><td>NIK</td><td>: {{ $surat->warga->nik }}</td></tr>
            <tr><td>Tempat / Tanggal Lahir</td><td>: 
                @if ($surat->warga->tempat_lahir || $surat->warga->tanggal_lahir)
                    {{ $surat->warga->tempat_lahir ?? '...' }}, 
                    {{ $surat->warga->tanggal_lahir ? $surat->warga->tanggal_lahir->translatedFormat('d F Y') : '...' }}
                @else
                    ............................................
                @endif
            </td></tr>
            <tr><td>Jenis Kelamin</td><td>: 
                @if ($surat->warga->jenis_kelamin === 'L') Laki-laki
                @elseif ($surat->warga->jenis_kelamin === 'P') Perempuan
                @else ............................
                @endif
            </td></tr>
            <tr><td>Alamat</td><td>: {{ $surat->warga->alamat }}</td></tr>
            <tr><td>RT / RW</td><td>: {{ $surat->warga->rt }} / {{ $surat->warga->rw }}</td></tr>
            <tr><td>Keperluan</td><td>: {{ $surat->keperluan }}</td></tr>
        </table>

        <p>Bahwa nama tersebut di atas adalah benar warga yang berdomisili di wilayah RT {{ $surat->warga->rt }} / 
           RW {{ $surat->warga->rw }}, Kelurahan Sungai Asam, dan sepanjang pengetahuan kami yang bersangkutan berkelakuan baik.</p>

        <p>Surat pengantar ini dibuat untuk keperluan pengurusan <strong>{{ $surat->keperluan }}</strong> 
           di tingkat Kelurahan.</p>

        <p>Demikian surat pengantar ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    {{-- TTD RT - OVERLAY --}}
    <div class="ttd-section">
        <div class="ttd-date">
            Contoh, {{ \Carbon\Carbon::parse($surat->rt_verified_at ?? now())->translatedFormat('d F Y') }}
        </div>
        <div class="ttd-jabatan">
            Ketua RT {{ $surat->warga->rt }} / RW {{ $surat->warga->rw }}
        </div>

        <div class="ttd-sign-area">
            @if ($rtUser && $rtUser->stempel_path && file_exists(storage_path('app/private/' . $rtUser->stempel_path)))
                @php
                    $stempelPath = storage_path('app/private/' . $rtUser->stempel_path);
                    $stempelMime = mime_content_type($stempelPath);
                    $stempelBase64 = 'data:' . $stempelMime . ';base64,' . base64_encode(file_get_contents($stempelPath));
                @endphp
                <img src="{{ $stempelBase64 }}" class="stempel-img" alt="Stempel">
            @endif

            @if ($rtUser && $rtUser->ttd_path && file_exists(storage_path('app/private/' . $rtUser->ttd_path)))
                @php
                    $ttdPath = storage_path('app/private/' . $rtUser->ttd_path);
                    $ttdMime = mime_content_type($ttdPath);
                    $ttdBase64 = 'data:' . $ttdMime . ';base64,' . base64_encode(file_get_contents($ttdPath));
                @endphp
                <img src="{{ $ttdBase64 }}" class="ttd-img" alt="TTD">
            @endif
        </div>

        <div class="ttd-name">
            (
            @if ($surat->rtVerifier)
                {{ $surat->rtVerifier->name }}
            @elseif ($rtUser)
                {{ $rtUser->name }}
            @else
                ..............................
            @endif
            )
        </div>
    </div>

    {{-- QR CODE VERIFIKASI --}}
    <div class="qr-section">
        <div class="qr-box">
            <img src="{{ $qrBase64 }}" alt="QR Code">
        </div><div class="qr-info">
            <div class="qr-title">✓ Scan QR untuk Verifikasi Keaslian Surat</div>
            <div class="qr-desc">Scan QR code di atas dengan kamera HP untuk memverifikasi keaslian surat pengantar ini.</div>
            <div class="qr-url">{{ $verifyUrl }}</div>
        </div>
    </div>

    <div class="footer-info">
        <p>Surat pengantar ini dicetak otomatis oleh Sistem Pelayanan Surat Kelurahan pada {{ now()->format('d/m/Y H:i') }} WIB</p>
        <p style="font-style: italic;">⚠ Harap bawa surat pengantar ini (cetak fisik) ke kantor kelurahan untuk diproses lebih lanjut.</p>
    </div>

</body>
</html>