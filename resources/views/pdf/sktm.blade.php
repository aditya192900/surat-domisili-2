<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Tidak Mampu</title>
    <style>
        @page { margin: 1.2cm 1.8cm; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.4; color: #000; }

        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 6px; margin-bottom: 12px; }
        .kop h3 { margin: 0; font-size: 12pt; letter-spacing: 0.5px; }
        .kop h2 { margin: 2px 0; font-size: 14pt; letter-spacing: 0.5px; }
        .kop h1 { margin: 2px 0; font-size: 16pt; letter-spacing: 0.5px; }
        .kop p { margin: 1px 0; font-size: 9pt; }

        .judul { text-align: center; margin: 14px 0 12px 0; }
        .judul h3 { margin: 0; text-decoration: underline; font-size: 13pt; text-transform: uppercase; letter-spacing: 1.5px; }
        .judul p { margin: 3px 0; font-size: 10pt; font-family: monospace; }

        .isi { text-align: justify; }
        .isi p { margin: 10px 0; line-height: 1.5; }

        table.data { width: 100%; margin: 10px 0; }
        table.data td { padding: 3px 0; vertical-align: top; font-size: 11pt; line-height: 1.5; }
        table.data td:first-child { width: 190px; }

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
        .qr-box img { width: 2.2cm; height: 2.2cm; display: block; }
        .qr-info {
            display: inline-block;
            text-align: left;
            margin-left: 14px;
            vertical-align: middle;
            max-width: 10cm;
        }
        .qr-title { font-size: 10pt; font-weight: bold; color: #1e40af; margin-bottom: 3px; }
        .qr-desc { font-size: 8.5pt; color: #666; margin-bottom: 3px; line-height: 1.4; }
        .qr-url { font-family: monospace; font-size: 8pt; color: #333; word-break: break-all; }

        .footer-info { margin-top: 12px; font-size: 8.5pt; color: #555; text-align: center; }
        .footer-info p { margin: 3px 0; }
    </style>
</head>
<body>

    <div class="kop">
        <h3>PEMERINTAH Kota Jambi</h3>
        <h2>Kecamatan Danau Sipin</h2>
        <h1>Kelurahan Sungai Asam</h1>
        <p>Jl. Sungai Asam No. 1, Kode Pos 36124</p>
        <p>Telp: (0741) 123456 | Email: kelurahan.sungaiasam@jambikota.go.id</p>
    </div>

    <div class="judul">
        <h3>Surat Keterangan Tidak Mampu</h3>
        <p>Nomor: {{ $surat->nomor_surat }}</p>
    </div>

    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, {{ $lurah->jabatan ?? 'Lurah Kelurahan Sungai Asam' }}, dengan ini menerangkan bahwa:</p>

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
        </table>

        <p>Berdasarkan pengamatan dan keterangan yang ada, nama tersebut di atas adalah benar warga 
           Kelurahan Sungai Asam yang termasuk dalam kategori <strong>keluarga tidak mampu</strong>, 
           dan berkelakuan baik.</p>

        <p>Surat keterangan ini dibuat untuk keperluan: <strong>{{ $surat->keperluan }}</strong>.</p>

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd-section">
        <div class="ttd-date">
            Contoh, {{ \Carbon\Carbon::parse($surat->kelurahan_processed_at)->translatedFormat('d F Y') }}
        </div>
        <div class="ttd-jabatan">
            {{ $lurah->jabatan ?? 'Lurah Kelurahan Sungai Asam' }}
        </div>

        <div class="ttd-sign-area">
            @if ($lurah && $lurah->stempel_path && file_exists(storage_path('app/private/' . $lurah->stempel_path)))
                @php
                    $stempelPath = storage_path('app/private/' . $lurah->stempel_path);
                    $stempelMime = mime_content_type($stempelPath);
                    $stempelBase64 = 'data:' . $stempelMime . ';base64,' . base64_encode(file_get_contents($stempelPath));
                @endphp
                <img src="{{ $stempelBase64 }}" class="stempel-img" alt="Stempel">
            @endif

            @if ($lurah && $lurah->ttd_path && file_exists(storage_path('app/private/' . $lurah->ttd_path)))
                @php
                    $ttdPath = storage_path('app/private/' . $lurah->ttd_path);
                    $ttdMime = mime_content_type($ttdPath);
                    $ttdBase64 = 'data:' . $ttdMime . ';base64,' . base64_encode(file_get_contents($ttdPath));
                @endphp
                <img src="{{ $ttdBase64 }}" class="ttd-img" alt="TTD">
            @endif
        </div>

        <div class="ttd-name">
            ({{ $lurah->name ?? '..............................' }})
            @if ($lurah && $lurah->nip)
                <br><span style="font-weight:normal; font-size:10pt;">NIP. {{ $lurah->nip }}</span>
            @endif
        </div>
    </div>

    <div class="qr-section">
        <div class="qr-box">
            <img src="{{ $qrBase64 }}" alt="QR Code">
        </div><div class="qr-info">
            <div class="qr-title">✓ Scan QR untuk Verifikasi Keaslian Surat</div>
            <div class="qr-desc">Scan QR code di atas dengan kamera HP untuk memverifikasi keaslian surat keterangan ini.</div>
            <div class="qr-url">{{ $verifyUrl }}</div>
        </div>
    </div>

    <div class="footer-info">
        <p>Surat keterangan ini dicetak otomatis oleh Sistem Pelayanan Surat Kelurahan pada {{ now()->format('d/m/Y H:i') }} WIB</p>
    </div>

</body>
</html>