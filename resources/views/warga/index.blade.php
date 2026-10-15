<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    📋 Pengajuan Surat Saya
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Pantau status pengajuan surat Anda
                </p>
            </div>
            <a href="{{ route('warga.surat.create') }}"
               style="padding:11px 22px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); display:inline-flex; align-items:center; gap:8px;">
                <span style="font-size:18px;">+</span>
                Ajukan Surat Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1200px; margin:0 auto; padding:0 20px;">

            @php
                $baseQuery = \App\Models\SuratDomisili::where('user_id', auth()->id());
                $total = (clone $baseQuery)->count();
                $proses = (clone $baseQuery)->whereIn('status', ['diajukan_ke_rt', 'diverifikasi_rt', 'diproses_kelurahan'])->count();
                $selesai = (clone $baseQuery)->where('status', 'selesai')->count();
                $ditolak = (clone $baseQuery)->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan'])->count();
            @endphp

            {{-- STATISTIK --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:14px; margin-bottom:24px;">
                <a href="{{ route('warga.surat.index') }}" style="text-decoration:none; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(102,126,234,0.3); transition:0.2s; display:block;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Total Pengajuan</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $total }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Sepanjang waktu</div>
                </a>
                <a href="{{ route('warga.surat.index', ['kategori' => 'proses']) }}" style="text-decoration:none; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(245,158,11,0.3); transition:0.2s; display:block;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Sedang Diproses</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $proses }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Menunggu selesai</div>
                </a>
                <a href="{{ route('warga.surat.index', ['kategori' => 'selesai']) }}" style="text-decoration:none; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(16,185,129,0.3); transition:0.2s; display:block;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Selesai</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $selesai }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Surat terbit</div>
                </a>
                <a href="{{ route('warga.surat.index', ['kategori' => 'ditolak']) }}" style="text-decoration:none; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(239,68,68,0.3); transition:0.2s; display:block;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Ditolak</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $ditolak }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Perlu diperbaiki</div>
                </a>
            </div>

            {{-- FILTER --}}
            <form method="GET" style="background:white; padding:16px; border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:10px; align-items:end;">
                    <div style="min-width:220px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Cari</label>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="🔍 Nomor pengajuan / keperluan..."
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
                            <option value="diajukan_ke_rt" @selected(request('status') === 'diajukan_ke_rt')>⏳ Menunggu RT</option>
                            <option value="diverifikasi_rt" @selected(request('status') === 'diverifikasi_rt')>✓ Diverifikasi RT</option>
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
                        <a href="{{ route('warga.surat.index') }}"
                           style="padding:11px 20px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; background:white; white-space:nowrap;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- BANNER FILTER KATEGORI AKTIF --}}
            @if (request('kategori'))
                @php
                    $kategoriInfo = [
                        'proses'  => ['icon' => '⏳', 'label' => 'Sedang Diproses', 'color' => '#d97706', 'bg' => '#fef3c7', 'border' => '#fcd34d'],
                        'selesai' => ['icon' => '✓',  'label' => 'Selesai',         'color' => '#065f46', 'bg' => '#d1fae5', 'border' => '#a7f3d0'],
                        'ditolak' => ['icon' => '✕',  'label' => 'Ditolak',         'color' => '#991b1b', 'bg' => '#fee2e2', 'border' => '#fecaca'],
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
                                    Klik "Reset" untuk melihat semua pengajuan
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('warga.surat.index') }}"
                           style="padding:8px 16px; background:white; color:{{ $kat['color'] }}; border:1.5px solid {{ $kat['border'] }}; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700;">
                            🔄 Reset
                        </a>
                    </div>
                @endif
            @endif

            {{-- MODERN CARD LIST --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(37,99,235,0.25);">
                            📋
                        </div>
                        <div>
                            <h3 style="font-size:17px; font-weight:700; color:#111827; margin:0; letter-spacing:-0.3px;">
                                Daftar Pengajuan
                            </h3>
                            <p style="font-size:12.5px; color:#6b7280; margin:3px 0 0 0;">
                                Semua riwayat pengajuan surat Anda
                            </p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; padding:10px 16px; background:linear-gradient(135deg, #eff6ff 0%, #ede9fe 100%); border:1.5px solid #c7d2fe; border-radius:12px;">
                        <div style="font-size:26px; font-weight:800; background:linear-gradient(135deg, #2563eb, #7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; line-height:1;">
                            {{ $data->total() }}
                        </div>
                        <div style="font-size:11px; font-weight:700; color:#4338ca; text-transform:uppercase; letter-spacing:0.5px; line-height:1.2;">
                            Pengajuan<br>
                            <span style="font-size:10px; font-weight:600; color:#6366f1; opacity:0.8;">Ditampilkan</span>
                        </div>
                    </div>
                </div>

                {{-- HEADER BARIS (Desktop) --}}
                <div class="desktop-only" style="display:grid; grid-template-columns:280px 1fr 180px 200px 140px; gap:16px; padding:12px 24px; background:#fafbfc; border-bottom:1px solid #f3f4f6; font-size:10.5px; text-transform:uppercase; color:#9ca3af; font-weight:700; letter-spacing:1px;">
                    <div>Pengajuan</div>
                    <div>Layanan</div>
                    <div>Status</div>
                    <div>Tanggal</div>
                    <div style="text-align:right;">Aksi</div>
                </div>

                {{-- BODY --}}
                <div>
                    @forelse ($data as $item)
                        @php
                            $statusConfig = [
                                'diajukan_ke_rt'     => ['bg' => '#dbeafe', 'color' => '#1e40af', 'dot' => '#3b82f6', 'label' => 'Menunggu RT'],
                                'diverifikasi_rt'    => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'dot' => '#6366f1', 'label' => 'Diverifikasi RT'],
                                'diproses_kelurahan' => ['bg' => '#fef3c7', 'color' => '#92400e', 'dot' => '#f59e0b', 'label' => 'Diproses'],
                                'selesai'            => ['bg' => '#d1fae5', 'color' => '#065f46', 'dot' => '#10b981', 'label' => 'Selesai'],
                                'ditolak_rt'         => ['bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#ef4444', 'label' => 'Ditolak RT'],
                                'ditolak_kelurahan'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'dot' => '#ef4444', 'label' => 'Ditolak Kelurahan'],
                            ];
                            $cfg = $statusConfig[$item->status] ?? ['bg' => '#e5e7eb', 'color' => '#374151', 'dot' => '#9ca3af', 'label' => $item->status];

                            $layananIcon = '📄';
                            $layananBg = '#f3f4f6';
                            $lay = strtolower($item->keperluan);
                            if (str_contains($lay, 'domisili')) { $layananIcon = '🏠'; $layananBg = '#eff6ff'; }
                            elseif (str_contains($lay, 'sktm') || str_contains($lay, 'tidak mampu')) { $layananIcon = '🤝'; $layananBg = '#fffbeb'; }
                            elseif (str_contains($lay, 'sku') || str_contains($lay, 'usaha')) { $layananIcon = '🏪'; $layananBg = '#ecfdf5'; }

                            $adaDitolak = in_array($item->status, ['ditolak_rt', 'ditolak_kelurahan']);
                            $alasan = $item->status === 'ditolak_rt' ? $item->catatan_rt : ($item->status === 'ditolak_kelurahan' ? $item->catatan_kelurahan : null);
                        @endphp

                        <div style="border-bottom:1px solid #f3f4f6; transition:0.15s; position:relative;" class="row-hover">

                            {{-- ROW: DESKTOP --}}
                            <div class="desktop-only" style="display:grid; grid-template-columns:280px 1fr 180px 200px 140px; gap:16px; padding:18px 24px; align-items:center;">

                                {{-- KOLOM 1: NOMOR + DOKUMEN --}}
                                <div>
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                        <span style="font-family:monospace; font-size:12px; font-weight:700; color:#1e40af; background:#eff6ff; padding:4px 10px; border-radius:8px; letter-spacing:0.3px;">
                                            {{ $item->nomor_pengajuan }}
                                        </span>
                                        <button onclick="copyNomor('{{ $item->nomor_pengajuan }}', this)" title="Salin nomor"
                                                style="background:transparent; border:none; cursor:pointer; padding:4px; border-radius:6px; color:#9ca3af; transition:0.15s;"
                                                onmouseover="this.style.background='#f3f4f6'; this.style.color='#374151'" onmouseout="this.style.background='transparent'; this.style.color='#9ca3af'">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                        </button>
                                    </div>
                                    <div style="display:flex; gap:5px;">
                                        <a href="{{ route('surat.file', [$item, 'ktp']) }}" target="_blank"
                                           style="padding:3px 8px; background:#f9fafb; color:#6b7280; border:1px solid #e5e7eb; border-radius:6px; font-size:10.5px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:3px;">
                                            📎 KTP
                                        </a>
                                        <a href="{{ route('surat.file', [$item, 'kk']) }}" target="_blank"
                                           style="padding:3px 8px; background:#f9fafb; color:#6b7280; border:1px solid #e5e7eb; border-radius:6px; font-size:10.5px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:3px;">
                                            📎 KK
                                        </a>
                                    </div>
                                </div>

                                {{-- KOLOM 2: LAYANAN --}}
                                <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                                    <div style="width:40px; height:40px; background:{{ $layananBg }}; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
                                        {{ $layananIcon }}
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="font-weight:600; color:#111827; font-size:13.5px; line-height:1.35; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                                            {{ $item->keperluan }}
                                        </div>
                                    </div>
                                </div>

                                {{-- KOLOM 3: STATUS --}}
                                <div>
                                    <span style="padding:6px 12px; border-radius:20px; font-size:11.5px; font-weight:700; background:{{ $cfg['bg'] }}; color:{{ $cfg['color'] }}; display:inline-flex; align-items:center; gap:6px;">
                                        <span style="width:7px; height:7px; border-radius:50%; background:{{ $cfg['dot'] }}; box-shadow:0 0 0 3px {{ $cfg['bg'] }};"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                </div>

                                {{-- KOLOM 4: TANGGAL --}}
                                <div>
                                    <div style="font-size:13px; font-weight:600; color:#111827;">
                                        {{ $item->created_at->translatedFormat('d M Y') }}
                                    </div>
                                    <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                                        {{ $item->created_at->format('H:i') }} WIB
                                        • {{ $item->created_at->diffForHumans(null, true) }} lalu
                                    </div>
                                </div>

                                {{-- KOLOM 5: AKSI --}}
                                <div style="text-align:right;">
                                    <a href="{{ route('surat.show', $item) }}"
                                       style="padding:9px 16px; background:#111827; color:white; border-radius:8px; font-size:12px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:6px; transition:0.15s;"
                                       onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#111827'; this.style.transform='translateY(0)'">
                                        Detail
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a>
                                </div>
                            </div>

                            {{-- ALASAN PENOLAKAN --}}
                            @if ($adaDitolak && $alasan)
                                <div class="desktop-only" style="padding:0 24px 16px 24px; margin-top:-8px;">
                                    <div style="background:#fef2f2; border-left:4px solid #dc2626; border-radius:8px; padding:12px 16px; display:flex; align-items:flex-start; gap:10px;">
                                        <span style="font-size:16px;">⚠️</span>
                                        <div style="flex:1;">
                                            <div style="font-size:11px; font-weight:700; color:#991b1b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">
                                                Alasan Ditolak:
                                            </div>
                                            <div style="font-size:12.5px; color:#7f1d1d; line-height:1.5;">
                                                {{ $alasan }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ROW: MOBILE --}}
                            <div class="mobile-only" style="padding:16px;">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:12px;">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:44px; height:44px; background:{{ $layananBg }}; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">
                                            {{ $layananIcon }}
                                        </div>
                                        <div>
                                            <div style="font-family:monospace; font-size:11px; font-weight:700; color:#1e40af; margin-bottom:3px;">
                                                {{ $item->nomor_pengajuan }}
                                            </div>
                                            <div style="font-weight:600; color:#111827; font-size:13px; line-height:1.3;">
                                                {{ Str::limit($item->keperluan, 40) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px;">
                                    <span style="padding:5px 10px; border-radius:20px; font-size:10.5px; font-weight:700; background:{{ $cfg['bg'] }}; color:{{ $cfg['color'] }}; display:inline-flex; align-items:center; gap:5px;">
                                        <span style="width:6px; height:6px; border-radius:50%; background:{{ $cfg['dot'] }};"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                    <span style="font-size:11px; color:#9ca3af;">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>

                                <div style="display:flex; gap:8px;">
                                    <a href="{{ route('surat.show', $item) }}"
                                       style="flex:1; padding:10px; background:#111827; color:white; border-radius:8px; font-size:12px; text-decoration:none; font-weight:600; text-align:center;">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div style="padding:80px 20px; text-align:center;">
                            <div style="font-size:72px; margin-bottom:16px;">📭</div>
                            <div style="font-size:17px; font-weight:700; color:#374151; margin-bottom:8px;">
                                @if (request('kategori') || request('jenis') || request('status') || request('cari'))
                                    Tidak Ada Hasil Ditemukan
                                @else
                                    Belum Ada Pengajuan Surat
                                @endif
                            </div>
                            <div style="font-size:13px; color:#6b7280; margin-bottom:24px; max-width:420px; margin-left:auto; margin-right:auto; line-height:1.6;">
                                @if (request('kategori') || request('jenis') || request('status') || request('cari'))
                                    Coba ubah filter atau reset untuk melihat semua pengajuan.
                                @else
                                    Anda belum pernah mengajukan surat. Klik tombol di bawah untuk membuat pengajuan pertama Anda.
                                @endif
                            </div>
                            @if (request('kategori') || request('jenis') || request('status') || request('cari'))
                                <a href="{{ route('warga.surat.index') }}"
                                   style="padding:11px 24px; background:#f3f4f6; color:#374151; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                                    🔄 Reset Filter
                                </a>
                            @else
                                <a href="{{ route('warga.surat.create') }}"
                                   style="padding:12px 26px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                                    + Ajukan Surat Sekarang
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if ($data->hasPages())
                    <div style="padding:16px 24px; border-top:1px solid #f3f4f6; background:#fafbfc;">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        .row-hover { transition: 0.2s; }
        .row-hover:hover { background: #fafbfc !important; }
        .row-hover:hover { box-shadow: inset 4px 0 0 #2563eb; }

        .mobile-only { display: none; }
        .desktop-only { display: grid; }

        @media (max-width: 900px) {
            .desktop-only { display: none !important; }
            .mobile-only { display: block !important; }
        }

        .copied-btn { background: #10b981 !important; color: white !important; }
    </style>

    <script>
        function copyNomor(nomor, btn) {
            navigator.clipboard.writeText(nomor).then(function() {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                btn.classList.add('copied-btn');
                setTimeout(function() {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('copied-btn');
                }, 1500);
            });
        }
    </script>
</x-app-layout>