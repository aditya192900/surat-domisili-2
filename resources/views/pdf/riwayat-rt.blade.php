<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat RT</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0 0 5px 0; font-size: 16px; text-transform: uppercase; }
        .header h3 { margin: 0 0 5px 0; font-size: 14px; }
        .header p { margin: 0; font-size: 11px; }
        
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 15px; text-decoration: underline; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #666; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; text-align: center; font-size: 10px; }
        td { font-size: 10px; }
        
        .footer { margin-top: 30px; width: 100%; }
        .footer table { border: none; }
        .footer td { border: none; padding: 0; text-align: center; font-size: 11px; }
        
        .status-badge { padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; color: white; }
        .bg-success { background-color: #10b981; }
        .bg-warning { background-color: #f59e0b; }
        .bg-danger { background-color: #dc2626; }
        .bg-info { background-color: #3b82f6; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header">
        <h2>PEMERINTAH KOTA JAMBI</h2>
        <h3>{{ strtoupper($namaKelurahan) }}</h3>
        <p>Jl. Sungai Asam No. 1, Kecamatan Danau Sipin, Kota Jambi</p>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="title">LAPORAN RIWAYAT SURAT - RT {{ $rtUser->rt }}</div>

    <p style="margin-bottom: 10px;">
        <strong>Filter Aktif:</strong> 
        Status: {{ $filter['status'] ?? 'Semua' }} | 
        Jenis: {{ $filter['jenis'] ?? 'Semua' }} | 
        Pencarian: {{ $filter['cari'] ?? '-' }}
    </p>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">No. Pengajuan</th>
                <th width="18%">Nama Warga</th>
                <th width="12%">NIK</th>
                <th width="20%">Layanan</th>
                <th width="12%">Status</th>
                <th width="10%">Tgl Verifikasi RT</th>
                <th width="13%">Catatan Penolakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                @php
                    $statusColor = 'bg-info';
                    $statusLabel = 'Menunggu';
                    
                    if ($item->status == 'diverifikasi_rt') { $statusColor = 'bg-info'; $statusLabel = 'Diverifikasi RT'; }
                    elseif ($item->status == 'diproses_kelurahan') { $statusColor = 'bg-warning'; $statusLabel = 'Diproses'; }
                    elseif ($item->status == 'selesai') { $statusColor = 'bg-success'; $statusLabel = 'Selesai'; }
                    elseif (in_array($item->status, ['ditolak_rt', 'ditolak_kelurahan'])) { $statusColor = 'bg-danger'; $statusLabel = 'Ditolak'; }

                    $catatan = $item->status == 'ditolak_rt' ? $item->catatan_rt : ($item->status == 'ditolak_kelurahan' ? $item->catatan_kelurahan : '-');
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->nomor_pengajuan }}</td>
                    <td>{{ $item->warga->name ?? '-' }}</td>
                    <td>{{ $item->warga->nik ?? '-' }}</td>
                    <td>{{ $item->keperluan }}</td>
                    <td style="text-align: center;">
                        <span class="status-badge {{ $statusColor }}">{{ $statusLabel }}</span>
                    </td>
                    <td style="text-align: center;">
                        {{ $item->rt_verified_at ? $item->rt_verified_at->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $catatan ? \Illuminate\Support\Str::limit($catatan, 30) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data riwayat surat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="footer">
        <table>
            <tr>
                <td width="70%"></td>
                <td width="30%">
                    Jambi, {{ now()->translatedFormat('d F Y') }}<br>
                    Ketua RT {{ $rtUser->rt }}<br><br><br><br><br>
                    <strong><u>{{ $rtUser->name }}</u></strong><br>
                    NIP. {{ $rtUser->nip ?? '-' }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>