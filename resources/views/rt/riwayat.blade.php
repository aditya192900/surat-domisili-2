<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    📋 Riwayat Surat - RT {{ auth()->user()->rt }}
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Semua riwayat pengajuan surat yang pernah ditangani
                </p>
            </div>
            <a href="{{ route('rt.surat.index') }}"
               style="padding:10px 20px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                ← Kembali ke Verifikasi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1280px; margin:0 auto; padding:0 20px;">

            {{-- STATISTIK (CLICKABLE) --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:14px; margin-bottom:24px;">
                <a href="{{ route('rt.surat.riwayat') }}"
                   style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(102,126,234,0.3); text-decoration:none; display:block; position:relative; overflow:hidden; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">📊 Total Riwayat</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ array_sum($statistik) }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Semua status</div>
                </a>

                <a href="{{ route('rt.surat.riwayat', ['kategori' => 'diproses']) }}"
                   style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(245,158,11,0.3); text-decoration:none; display:block; position:relative; overflow:hidden; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">⚙️ Diproses</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $statistik['diverifikasi_rt'] + $statistik['diproses_kelurahan'] }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Di kelurahan</div>
                </a>

                <a href="{{ route('rt.surat.riwayat', ['kategori' => 'selesai']) }}"
                   style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(16,185,129,0.3); text-decoration:none; display:block; position:relative; overflow:hidden; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">🎉 Selesai</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $statistik['selesai'] }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Surat terbit</div>
                </a>

                <a href="{{ route('rt.surat.riwayat', ['kategori' => 'ditolak']) }}"
                   style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(239,68,68,0.3); text-decoration:none; display:block; position:relative; overflow:hidden; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">✕ Ditolak</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $statistik['ditolak_rt'] + $statistik['ditolak_kelurahan'] }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Total penolakan</div>
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
                        <a href="{{ route('rt.surat.riwayat') }}"
                           style="padding:8px 16px; background:white; color:{{ $kat['color'] }}; border:1.5px solid {{ $kat['border'] }}; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700;">
                            🔄 Reset
                        </a>
                    </div>
                @endif
            @endif

            {{-- FILTER --}}
            <form method="GET" style="background:white; padding:16px; border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:10px; align-items:end;">
                    <div style="min-width:220px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Cari</label>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="🔍 Nomor pengajuan / nama warga..."
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Jenis Surat</label>
                        <select name="jenis" style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; background:white; font-family:inherit;">
                            <option value="">-- Semua Jenis --</option>
                            <option value="domisili" @selected(request('jenis') === 'domisili')>🏠 Surat Keterangan Domisili</option>
                            <option value="sktm" @selected(request('jenis') === 'sktm')>🤝 SKTM (Tidak Mampu)</option>
                            <option value="sku" @selected(request('jenis') === 'sku')>🏪 SKU (Usaha)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Status</label>
                        <select name="status" style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; background:white; font-family:inherit;">
                            <option value="">-- Semua Status --</option>
                            <option value="diverifikasi_rt" @selected(request('status') === 'diverifikasi_rt')>✓ Diteruskan ke Kelurahan</option>
                            <option value="diproses_kelurahan" @selected(request('status') === 'diproses_kelurahan')>⚙️ Diproses Kelurahan</option>
                            <option value="selesai" @selected(request('status') === 'selesai')>🎉 Selesai</option>
                            <option value="ditolak_rt" @selected(request('status') === 'ditolak_rt')>✕ Ditolak RT</option>
                            <option value="ditolak_kelurahan" @selected(request('status') === 'ditolak_kelurahan')>✕ Ditolak Kelurahan</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="submit"
                                style="flex:1; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; padding:11px 20px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit;">
                            🔍 Filter
                        </button>
                        <a href="{{ route('rt.surat.riwayat') }}"
                           style="padding:11px 20px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; background:white; white-space:nowrap;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- TABEL --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:18px 22px; border-bottom:1px solid #f3f4f6;">
                    <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0;">📋 Daftar Riwayat</h3>
                    <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">{{ $data->total() }} surat ditampilkan</p>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; font-size:14px; text-align:left; border-collapse:collapse;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">No. Pengajuan</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Warga</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Layanan</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Status</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Tgl Verifikasi RT</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px; text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                @php
                                    $statusConfig = [
                                        'diverifikasi_rt'    => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '✓',  'label' => 'Diteruskan'],
                                        'ditolak_rt'         => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => '✕',  'label' => 'Ditolak RT'],
                                        'diproses_kelurahan' => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => '⚙️', 'label' => 'Diproses'],
                                        'selesai'            => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => '✓',  'label' => 'Selesai'],
                                        'ditolak_kelurahan'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => '✕',  'label' => 'Ditolak Kelurahan'],
                                    ];
                                    $cfg = $statusConfig[$item->status] ?? ['bg' => '#e5e7eb', 'color' => '#374151', 'icon' => '·', 'label' => $item->status];

                                    $layananIcon = '📄';
                                    $lay = strtolower($item->keperluan);
                                    if (str_contains($lay, 'domisili')) $layananIcon = '🏠';
                                    elseif (str_contains($lay, 'sktm') || str_contains($lay, 'tidak mampu')) $layananIcon = '🤝';
                                    elseif (str_contains($lay, 'sku') || str_contains($lay, 'usaha')) $layananIcon = '🏪';
                                @endphp
                                <tr style="border-bottom:1px solid #f3f4f6; transition:0.15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="font-family:monospace; font-size:12px; font-weight:700; color:#1e40af; background:#eff6ff; padding:4px 8px; border-radius:6px; display:inline-block;">
                                            {{ $item->nomor_pengajuan }}
                                        </div>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            @if ($item->warga->foto)
                                                <img src="{{ route('profile.foto', $item->warga) }}"
                                                     style="width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb;">
                                            @else
                                                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">
                                                    {{ $item->warga->inisial() }}
                                                </div>
                                            @endif
                                            <div>
                                                <div style="font-weight:600; color:#111827; font-size:13.5px;">{{ $item->warga->name }}</div>
                                                <div style="font-size:11px; color:#9ca3af;">RT {{ $item->warga->rt }}/RW {{ $item->warga->rw }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="display:flex; align-items:flex-start; gap:8px;">
                                            <span style="font-size:18px; flex-shrink:0;">{{ $layananIcon }}</span>
                                            <span style="font-weight:500; color:#374151; font-size:12.5px; line-height:1.4;">{{ $item->keperluan }}</span>
                                        </div>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <span style="padding:5px 12px; border-radius:8px; font-size:11px; font-weight:700; background:{{ $cfg['bg'] }}; color:{{ $cfg['color'] }}; display:inline-flex; align-items:center; gap:4px; white-space:nowrap;">
                                            {{ $cfg['icon'] }} {{ $cfg['label'] }}
                                        </span>

                                        @if ($item->status === 'ditolak_kelurahan' && $item->catatan_kelurahan)
                                            <div style="margin-top:8px; padding:6px 10px; background:#fef2f2; border-left:3px solid #dc2626; border-radius:4px; font-size:11px; color:#991b1b; max-width:220px;">
                                                <strong>Alasan:</strong> {{ Str::limit($item->catatan_kelurahan, 60) }}
                                            </div>
                                        @endif
                                        @if ($item->status === 'ditolak_rt' && $item->catatan_rt)
                                            <div style="margin-top:8px; padding:6px 10px; background:#fef2f2; border-left:3px solid #dc2626; border-radius:4px; font-size:11px; color:#991b1b; max-width:220px;">
                                                <strong>Alasan:</strong> {{ Str::limit($item->catatan_rt, 60) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        @if ($item->rt_verified_at)
                                            <div style="font-size:13px; font-weight:600; color:#111827;">{{ $item->rt_verified_at->format('d/m/Y') }}</div>
                                            <div style="font-size:11px; color:#9ca3af; margin-top:2px;">{{ $item->rt_verified_at->format('H:i') }} WIB</div>
                                        @else
                                            <span style="color:#9ca3af; font-size:12px;">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle; text-align:right;">
                                        <a href="{{ route('surat.show', $item) }}"
                                           style="padding:8px 16px; background:#eff6ff; color:#1e40af; border-radius:8px; font-size:12px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:6px; transition:0.15s;"
                                           onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                            Detail
                                            <span style="font-size:14px;">→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding:60px 20px; text-align:center;">
                                        <div style="font-size:64px; margin-bottom:12px;">📭</div>
                                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                                            @if (request('kategori') || request('jenis') || request('status') || request('cari'))
                                                Tidak Ada Hasil Ditemukan
                                            @else
                                                Belum Ada Riwayat Surat
                                            @endif
                                        </div>
                                        <div style="font-size:13px; color:#6b7280; max-width:400px; margin:0 auto; line-height:1.6;">
                                            @if (request('kategori') || request('jenis') || request('status') || request('cari'))
                                                Coba ubah filter atau reset untuk melihat semua riwayat.
                                            @else
                                                Belum ada surat yang ditangani. Riwayat akan muncul setelah Anda memverifikasi surat dari warga.
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