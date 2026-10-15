<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    📋 Riwayat Surat - Kelurahan
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Semua riwayat pengajuan surat di Kelurahan Sungai Asam
                </p>
            </div>
            <a href="{{ route('kelurahan.surat.index') }}"
               style="padding:10px 20px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                ← Kembali ke Proses Surat
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1280px; margin:0 auto; padding:0 20px;">

            {{-- STATISTIK CLICKABLE --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:14px; margin-bottom:24px;">

                <a href="{{ route('kelurahan.surat.riwayat') }}"
                   style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(102,126,234,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(102,126,234,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(102,126,234,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">📊 Total</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['total'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Semua surat</div>
                </a>

                <a href="{{ route('kelurahan.surat.riwayat', ['kategori' => 'diproses']) }}"
                   style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(245,158,11,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(245,158,11,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(245,158,11,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">⚙️ Diproses</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['diverifikasi_rt'] + $statistik['diproses_kelurahan'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Perlu ditindaklanjuti</div>
                </a>

                <a href="{{ route('kelurahan.surat.riwayat', ['kategori' => 'selesai']) }}"
                   style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(16,185,129,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(16,185,129,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(16,185,129,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✓ Selesai</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['selesai'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Surat terbit</div>
                </a>

                <a href="{{ route('kelurahan.surat.riwayat', ['kategori' => 'ditolak']) }}"
                   style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(239,68,68,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(239,68,68,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(239,68,68,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✕ Ditolak</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['ditolak_rt'] + $statistik['ditolak_kelurahan'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Total penolakan</div>
                </a>
            </div>

            {{-- BANNER KATEGORI AKTIF --}}
            @if (request('kategori'))
                @php
                    $kategoriInfo = [
                        'diproses' => ['icon' => '⚙️', 'label' => 'Sedang Diproses', 'color' => '#d97706', 'bg' => '#fef3c7', 'border' => '#fcd34d'],
                        'selesai'  => ['icon' => '✓',  'label' => 'Selesai',         'color' => '#065f46', 'bg' => '#d1fae5', 'border' => '#a7f3d0'],
                        'ditolak'  => ['icon' => '✕',  'label' => 'Ditolak',         'color' => '#991b1b', 'bg' => '#fee2e2', 'border' => '#fecaca'],
                    ];
                    $kat = $kategoriInfo[request('kategori')] ?? null;
                @endphp

                @if ($kat)
                    <div style="background:{{ $kat['bg'] }}; border:1.5px solid {{ $kat['border'] }}; border-radius:14px; padding:14px 20px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:36px; height:36px; background:{{ $kat['color'] }}; color:white; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:700;">
                                {{ $kat['icon'] }}
                            </div>
                            <div>
                                <div style="font-weight:700; font-size:14px; color:{{ $kat['color'] }};">
                                    Menampilkan: {{ $kat['label'] }}
                                </div>
                                <div style="font-size:12px; color:{{ $kat['color'] }}; opacity:0.8;">
                                    Klik "Reset" untuk melihat semua riwayat
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('kelurahan.surat.riwayat') }}"
                           style="padding:8px 16px; background:white; color:{{ $kat['color'] }}; border:1.5px solid {{ $kat['border'] }}; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700;">
                            🔄 Reset
                        </a>
                    </div>
                @endif
            @endif

            {{-- FILTER --}}
            <form method="GET" style="background:white; padding:16px; border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:16px;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:10px; align-items:end;">
                    <div style="min-width:220px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Cari</label>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="🔍 Nomor / nama warga..."
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Status</label>
                        <select name="status" style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; background:white; font-family:inherit;">
                            <option value="">-- Semua Status --</option>
                            <option value="diverifikasi_rt" @selected(request('status') === 'diverifikasi_rt')>✓ Diverifikasi RT</option>
                            <option value="diproses_kelurahan" @selected(request('status') === 'diproses_kelurahan')>⚙️ Diproses</option>
                            <option value="selesai" @selected(request('status') === 'selesai')>🎉 Selesai</option>
                            <option value="ditolak_rt" @selected(request('status') === 'ditolak_rt')>✕ Ditolak RT</option>
                            <option value="ditolak_kelurahan" @selected(request('status') === 'ditolak_kelurahan')>✕ Ditolak Kelurahan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}"
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="submit"
                                style="flex:1; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; padding:11px 20px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit;">
                            🔍 Filter
                        </button>
                        <a href="{{ route('kelurahan.surat.riwayat') }}"
                           style="padding:11px 20px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; background:white; white-space:nowrap;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- EXPORT & REKAP --}}
            <div style="background:white; padding:16px; border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; color:white; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                        📥
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:14px; color:#111827;">Ekspor & Rekap Laporan</div>
                        <div style="font-size:11.5px; color:#6b7280;">Ekspor data atau cetak rekap sesuai periode</div>
                    </div>
                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="{{ route('kelurahan.surat.export', request()->query()) }}"
                       style="padding:10px 18px; background:linear-gradient(135deg, #16a34a 0%, #15803d 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(22,163,74,0.25);">
                        📊 Ekspor Excel (Filter Aktif)
                    </a>

                    <a href="{{ route('kelurahan.surat.rekap', ['periode' => 'hari_ini']) }}"
                       style="padding:10px 18px; background:linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(220,38,38,0.25);">
                        📄 Rekap Hari Ini
                    </a>

                    <a href="{{ route('kelurahan.surat.rekap', ['periode' => 'minggu_ini']) }}"
                       style="padding:10px 18px; background:linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(220,38,38,0.25);">
                        🗓️ Rekap Minggu Ini
                    </a>

                    <a href="{{ route('kelurahan.surat.rekap', ['periode' => 'bulan_ini']) }}"
                       style="padding:10px 18px; background:linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(220,38,38,0.25);">
                        📅 Rekap Bulan Ini
                    </a>

                    <a href="{{ route('kelurahan.surat.rekap', ['periode' => 'bulan_ini', 'foto' => 1]) }}"
                       onclick="return confirm('⚠ PENTING:\n\nRekap dengan foto KTP/KK akan membuat file PDF berukuran besar dan berisi data pribadi warga.\n\nPastikan Anda menyimpan file ini dengan aman.\n\nLanjutkan?')"
                       style="padding:10px 18px; background:linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(124,58,237,0.25);">
                        📸 Rekap + Foto
                    </a>
                </div>

                <div style="font-size:11.5px; color:#9ca3af; margin-top:10px; padding-top:10px; border-top:1px dashed #e5e7eb;">
                    💡 <strong>Ekspor Excel</strong> mengikuti filter di atas. <strong>Rekap PDF</strong> berdasarkan periode (hari/minggu/bulan ini).
                </div>
            </div>

            {{-- TABEL --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(37,99,235,0.25);">
                            📋
                        </div>
                        <div>
                            <h3 style="font-size:17px; font-weight:700; color:#111827; margin:0; letter-spacing:-0.3px;">
                                Daftar Riwayat
                            </h3>
                            <p style="font-size:12.5px; color:#6b7280; margin:3px 0 0 0;">
                                Semua surat yang pernah diproses
                            </p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; padding:10px 16px; background:linear-gradient(135deg, #eff6ff 0%, #ede9fe 100%); border:1.5px solid #c7d2fe; border-radius:12px;">
                        <div style="font-size:26px; font-weight:800; background:linear-gradient(135deg, #2563eb, #7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; line-height:1;">
                            {{ $data->total() }}
                        </div>
                        <div style="font-size:11px; font-weight:700; color:#4338ca; text-transform:uppercase; letter-spacing:0.5px; line-height:1.2;">
                            Surat<br>
                            <span style="font-size:10px; font-weight:600; color:#6366f1; opacity:0.8;">Ditampilkan</span>
                        </div>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; font-size:14px; text-align:left; border-collapse:collapse;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">No. Pengajuan</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">No. Surat</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Warga</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Layanan</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Status</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Tanggal</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px; text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                @php
                                    $statusConfig = [
                                        'diverifikasi_rt'    => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'dot' => '#6366f1', 'icon' => '✓',  'label' => 'Diverifikasi RT'],
                                        'ditolak_rt'         => ['bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#ef4444', 'icon' => '✕',  'label' => 'Ditolak RT'],
                                        'diproses_kelurahan' => ['bg' => '#fef3c7', 'color' => '#92400e', 'dot' => '#f59e0b', 'icon' => '⚙️', 'label' => 'Diproses'],
                                        'selesai'            => ['bg' => '#d1fae5', 'color' => '#065f46', 'dot' => '#10b981', 'icon' => '✓',  'label' => 'Selesai'],
                                        'ditolak_kelurahan'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#ef4444', 'icon' => '✕',  'label' => 'Ditolak Kelurahan'],
                                    ];
                                    $cfg = $statusConfig[$item->status] ?? ['bg' => '#e5e7eb', 'color' => '#374151', 'dot' => '#9ca3af', 'icon' => '·', 'label' => $item->status];

                                    $layananIcon = '📄';
                                    $layananBg = '#f3f4f6';
                                    $lay = strtolower($item->keperluan);
                                    if (str_contains($lay, 'domisili')) { $layananIcon = '🏠'; $layananBg = '#eff6ff'; }
                                    elseif (str_contains($lay, 'sktm') || str_contains($lay, 'tidak mampu')) { $layananIcon = '🤝'; $layananBg = '#fffbeb'; }
                                    elseif (str_contains($lay, 'sku') || str_contains($lay, 'usaha')) { $layananIcon = '🏪'; $layananBg = '#ecfdf5'; }

                                    $adaDitolak = in_array($item->status, ['ditolak_rt', 'ditolak_kelurahan']);
                                    $alasan = $item->status === 'ditolak_rt' ? $item->catatan_rt : ($item->status === 'ditolak_kelurahan' ? $item->catatan_kelurahan : null);
                                @endphp
                                <tr style="border-bottom:1px solid #f3f4f6; transition:0.15s;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        <div style="font-family:monospace; font-size:12px; font-weight:700; color:#1e40af; background:#eff6ff; padding:4px 10px; border-radius:8px; display:inline-block;">
                                            {{ $item->nomor_pengajuan }}
                                        </div>
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        @if ($item->nomor_surat)
                                            <div style="font-family:monospace; font-size:12px; font-weight:600; color:#374151;">
                                                {{ $item->nomor_surat }}
                                            </div>
                                        @else
                                            <span style="color:#d1d5db;">—</span>
                                        @endif
                                    </td>
                                                                  <td style="padding:18px 20px; vertical-align:middle; min-width:170px;">
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            @if ($item->warga->foto)
                                                <img src="{{ route('profile.foto', $item->warga) }}"
                                                     style="width:42px; height:42px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb; flex-shrink:0;">
                                            @else
                                                <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; flex-shrink:0;">
                                                    {{ $item->warga->inisial() }}
                                                </div>
                                            @endif
                                            <div style="min-width:0;">
                                                <div style="font-weight:600; color:#111827; font-size:13.5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:130px;">
                                                    {{ $item->warga->name }}
                                                </div>
                                                <div style="font-size:11px; color:#9ca3af; white-space:nowrap;">
                                                    RT {{ $item->warga->rt }}/RW {{ $item->warga->rw }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        <div style="display:flex; align-items:flex-start; gap:10px;">
                                            <div style="flex-shrink:0; width:36px; height:36px; background:{{ $layananBg }}; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px;">
                                                {{ $layananIcon }}
                                            </div>
                                            <span style="font-weight:500; color:#374151; line-height:1.4; font-size:13px;">{{ $item->keperluan }}</span>
                                        </div>
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        <span style="padding:6px 12px; border-radius:20px; font-size:11.5px; font-weight:700; background:{{ $cfg['bg'] }}; color:{{ $cfg['color'] }}; display:inline-flex; align-items:center; gap:6px; white-space:nowrap;">
                                            <span style="width:7px; height:7px; border-radius:50%; background:{{ $cfg['dot'] }}; box-shadow:0 0 0 3px {{ $cfg['bg'] }};"></span>
                                            {{ $cfg['label'] }}
                                        </span>

                                        @if ($adaDitolak && $alasan)
                                            <div style="margin-top:8px; padding:8px 10px; background:#fef2f2; border-left:3px solid #dc2626; border-radius:6px; font-size:11px; color:#991b1b; max-width:240px; line-height:1.4;">
                                                <strong>Alasan:</strong> {{ Str::limit($alasan, 80) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        <div style="font-size:13px; font-weight:600; color:#111827;">{{ $item->created_at->format('d/m/Y') }}</div>
                                        <div style="font-size:11px; color:#9ca3af; margin-top:2px;">{{ $item->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle; text-align:right;">
                                        <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                            <a href="{{ route('surat.show', $item) }}"
                                               style="padding:8px 14px; background:#eff6ff; color:#1e40af; border-radius:8px; font-size:12px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:4px; transition:0.15s;"
                                               onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                                Detail →
                                            </a>
                                            @if ($item->status === 'selesai')
                                                <a href="{{ route('surat.cetak', $item) }}" target="_blank"
                                                   style="padding:8px 14px; background:linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color:white; border-radius:8px; font-size:12px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; box-shadow:0 2px 8px rgba(124,58,237,0.25);">
                                                    📄 PDF
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:60px 20px; text-align:center;">
                                        <div style="font-size:64px; margin-bottom:12px;">📭</div>
                                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                                            @if (request('kategori') || request('status') || request('cari') || request('dari'))
                                                Tidak Ada Hasil Ditemukan
                                            @else
                                                Belum Ada Riwayat Surat
                                            @endif
                                        </div>
                                        <div style="font-size:13px; color:#6b7280; max-width:400px; margin:0 auto; line-height:1.6;">
                                            @if (request('kategori') || request('status') || request('cari') || request('dari'))
                                                Coba ubah filter atau reset untuk melihat semua riwayat.
                                            @else
                                                Belum ada surat yang pernah diproses. Riwayat akan muncul setelah ada pengajuan.
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($data->hasPages())
                    <div style="padding:16px 22px; border-top:1px solid #f3f4f6; background:#fafbfc;">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>