<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Surat</title>
    <style>
        @page { margin: 1.5cm 1.2cm; }
        body { font-family: 'Times New Roman', serif; font-size: 10pt; color: #000; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop h3 { margin: 0; font-size: 12pt; }
        .kop h2 { margin: 2px 0; font-size: 14pt; }
        .kop h1 { margin: 2px 0; font-size: 16pt; }
        .kop p { margin: 2px 0; font-size: 9pt; }

        .title { text-align: center; margin: 16px 0; }
        .title h3 { margin: 0; font-size: 13pt; text-decoration: underline; text-transform: uppercase; }
        .title p { margin: 4px 0; font-size: 10pt; }

        .statistik { display: table; width: 100%; margin-bottom: 14px; }
        .stat-item { display: table-cell; width: 25%; padding: 8px 6px; border: 1px solid #999; text-align: center; }
        .stat-item .num { font-size: 16pt; font-weight: bold; color: #1e40af; }
        .stat-item .lbl { font-size: 8pt; text-transform: uppercase; color: #555; margin-top: 2px; }

        table.data { width: 100%; border-collapse: collapse; font-size: 9pt; }
        table.data th { background: #1e40af; color: white; padding: 6px 4px; border: 1px solid #1e40af; text-align: center; font-size: 8.5pt; }
        table.data td { padding: 5px 4px; border: 1px solid #999; vertical-align: top; }
        table.data tr:nth-child(even) td { background: #f9fafb; }

        .badge { padding: 2px 5px; border-radius: 3px; font-size: 8pt; font-weight: bold; }
        .badge-selesai { background: #d1fae5; color: #065f46; }
        .badge-diproses { background: #fef3c7; color: #92400e; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-pending { background: #dbeafe; color: #1e40af; }

        .foto-thumb { width: 55px; height: 38px; object-fit: cover; border: 1px solid #999; margin-right: 3px; }
        .foto-na { font-size: 7pt; color: #999; font-style: italic; }

        .footer-ttd { margin-top: 30px; width: 100%; }
        .footer-ttd td { vertical-align: top; text-align: center; font-size: 10pt; }
    </style>
</head>
<body>

    <div class="kop">
        <h3>PEMERINTAH Kota Jambi</h3>
        <h2>Kecamatan Danau Sipin</h2>
        <h1>Kelurahan Sungai Asam</h1>
        <p>Jl. Sungai Asam No. 1, Kode Pos 36124 | Telp: (0741) 123456</p>
    </div>

    <div class="title">
        <h3>Rekapitulasi Pengajuan Surat</h3>
        <p>Periode: {{ $label }}</p>
    </div>

    <div class="statistik">
        <div class="stat-item">
            <div class="num">{{ $statistik['total'] }}</div>
            <div class="lbl">Total</div>
        </div>
        <div class="stat-item">
            <div class="num">{{ $statistik['selesai'] }}</div>
            <div class="lbl">Selesai</div>
        </div>
        <div class="stat-item">
            <div class="num">{{ $statistik['diproses'] }}</div>
            <div class="lbl">Diproses</div>
        </div>
        <div class="stat-item">
            <div class="num">{{ $statistik['ditolak'] }}</div>
            <div class="lbl">Ditolak</div>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="25">No</th>
                <th width="75">Tanggal</th>
                <th width="110">Nomor Pengajuan</th>
                <th width="90">Nama</th>
                <th width="55">RT/RW</th>
                <th>Jenis Surat</th>
                <th width="70">Status</th>
                @if ($sertakanFoto)
                    <th width="130">Foto KTP / KK</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $item)
                @php
                    $statusClass = 'badge-pending';
                    $statusLabel = 'Menunggu RT';
                    if ($item->status === 'selesai') { $statusClass = 'badge-selesai'; $statusLabel = 'Selesai'; }
                    elseif ($item->status === 'ditolak_rt') { $statusClass = 'badge-ditolak'; $statusLabel = 'Ditolak RT'; }
                    elseif ($item->status === 'ditolak_kelurahan') { $statusClass = 'badge-ditolak'; $statusLabel = 'Ditolak Kel.'; }
                    elseif (in_array($item->status, ['diverifikasi_rt', 'diproses_kelurahan'])) { $statusClass = 'badge-diproses'; $statusLabel = 'Diproses'; }
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $i + 1 }}</td>
                    <td style="text-align:center; font-size:8pt;">{{ $item->created_at->format('d/m/Y') }}<br>{{ $item->created_at->format('H:i') }}</td>
                    <td style="font-family:monospace; font-size:8pt;">{{ $item->nomor_pengajuan }}</td>
                    <td>{{ $item->warga->name ?? '-' }}</td>
                    <td style="text-align:center;">{{ $item->warga->rt ?? '-' }}/{{ $item->warga->rw ?? '-' }}</td>
                    <td style="font-size:8.5pt;">{{ $item->keperluan }}</td>
                    <td style="text-align:center;">
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    @if ($sertakanFoto)
                        <td style="text-align:center;">
                            @if (!empty($fotoData[$item->id]['ktp']))
                                <img src="{{ $fotoData[$item->id]['ktp'] }}" class="foto-thumb">
                            @else
                                <span class="foto-na">KTP: N/A</span>
                            @endif
                            @if (!empty($fotoData[$item->id]['kk']))
                                <img src="{{ $fotoData[$item->id]['kk'] }}" class="foto-thumb">
                            @else
                                <span class="foto-na">KK: N/A</span>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $sertakanFoto ? 8 : 7 }}" style="text-align:center; padding:20px; color:#999;">
                        Tidak ada data surat pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-ttd">
        <tr>
            <td width="60%"></td>
            <td width="40%">
                Contoh, {{ now()->translatedFormat('d F Y') }}<br>
                Lurah Kelurahan Sungai Asam<br><br><br><br><br>
                <strong><u>......................................</u></strong><br>
                NIP. ..............................
            </td>
        </tr>
    </table>

    <div style="margin-top:20px; text-align:center; font-size:8pt; color:#666; border-top:1px solid #ccc; padding-top:6px;">
        Digenerate otomatis oleh Sistem Pelayanan Surat Kelurahan pada {{ now()->format('d/m/Y H:i') }} WIB
        @if ($sertakanFoto)
            <br><strong style="color:#dc2626;">⚠ Dokumen ini berisi data pribadi (KTP/KK) — jaga kerahasiaannya.</strong>
        @endif
    </div>

</body>
</html>