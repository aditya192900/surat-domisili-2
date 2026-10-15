<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    📊 Dashboard Statistik - Kelurahan
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Ringkasan pelayanan surat di Kelurahan Sungai Asam
                </p>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('kelurahan.surat.index') }}"
                   style="padding:10px 20px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                    ⚙️ Proses Surat
                </a>
                <a href="{{ route('kelurahan.surat.riwayat') }}"
                   style="padding:10px 20px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                    📋 Riwayat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1280px; margin:0 auto; padding:0 20px;">

            {{-- NOTIFIKASI: SURAT BARU DARI RT --}}
            @if ($statistik['diverifikasi_rt'] > 0)
                <div style="background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border:1px solid #fcd34d; border-left:5px solid #f59e0b; border-radius:14px; padding:16px 22px; margin-bottom:20px; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                    <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                        📥
                    </div>
                    <div style="flex:1; min-width:200px;">
                        <div style="font-weight:700; font-size:14.5px; color:#78350f;">
                            {{ $statistik['diverifikasi_rt'] }} surat baru dari RT menunggu diproses
                        </div>
                        <div style="font-size:12.5px; color:#92400e; margin-top:3px;">
                            Segera tindak lanjuti agar warga tidak menunggu lama
                        </div>
                    </div>
                    <a href="{{ route('kelurahan.surat.index') }}"
                       style="padding:10px 20px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; white-space:nowrap; box-shadow:0 4px 14px rgba(245,158,11,0.3);">
                        Lihat & Proses →
                    </a>
                </div>
            @endif

            {{-- STATISTIK CLICKABLE --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin-bottom:24px;">

                {{-- TOTAL --}}
                <a href="{{ route('kelurahan.surat.riwayat') }}"
                   style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(102,126,234,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(102,126,234,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(102,126,234,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">📊 Total Pengajuan</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $statistik['total'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Sepanjang waktu</div>
                </a>

                {{-- SEDANG DIPROSES --}}
                <a href="{{ route('kelurahan.surat.riwayat', ['kategori' => 'diproses']) }}"
                   style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(245,158,11,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(245,158,11,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(245,158,11,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">⚙️ Sedang Diproses</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $statistik['diverifikasi_rt'] + $statistik['diproses_kelurahan'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Perlu ditindaklanjuti</div>
                </a>

                {{-- SELESAI --}}
                <a href="{{ route('kelurahan.surat.riwayat', ['kategori' => 'selesai']) }}"
                   style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(16,185,129,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(16,185,129,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(16,185,129,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✓ Selesai</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $statistik['selesai'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Surat terbit</div>
                </a>

                {{-- DITOLAK --}}
                <a href="{{ route('kelurahan.surat.riwayat', ['kategori' => 'ditolak']) }}"
                   style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(239,68,68,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(239,68,68,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(239,68,68,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✕ Ditolak</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $statistik['ditolak_rt'] + $statistik['ditolak_kelurahan'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Total penolakan</div>
                </a>

                {{-- RATA-RATA --}}
                <div style="background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(59,130,246,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">⏱️ Rata-rata Proses</div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">
                        {{ $rataRataJam }}<span style="font-size:18px; font-weight:600; margin-left:4px;">jam</span>
                    </div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Dari ajukan → selesai</div>
                </div>
            </div>

            {{-- QUICK ACTION --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:24px; margin-bottom:24px;">
                <div style="margin-bottom:18px;">
                    <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0;">⚡ Aksi Cepat</h3>
                    <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">Akses fitur utama dengan sekali klik</p>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px;">
                    <a href="{{ route('kelurahan.surat.index') }}"
                       style="padding:20px; background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:1.5px solid #bfdbfe; border-radius:14px; text-decoration:none; display:flex; align-items:center; gap:14px; transition:0.2s;"
                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(37,99,235,0.15)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                            ⚙️
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:14px; color:#1e40af;">Proses Surat</div>
                            <div style="font-size:12px; color:#3b82f6; margin-top:2px;">Tindak lanjuti pengajuan</div>
                        </div>
                    </a>

                    <a href="{{ route('kelurahan.surat.riwayat') }}"
                       style="padding:20px; background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1.5px solid #a7f3d0; border-radius:14px; text-decoration:none; display:flex; align-items:center; gap:14px; transition:0.2s;"
                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(16,185,129,0.15)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                            📋
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:14px; color:#065f46;">Riwayat Surat</div>
                            <div style="font-size:12px; color:#059669; margin-top:2px;">Lihat semua riwayat</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.logs') }}"
                       style="padding:20px; background:linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border:1.5px solid #fcd34d; border-radius:14px; text-decoration:none; display:flex; align-items:center; gap:14px; transition:0.2s;"
                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(245,158,11,0.15)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                            📜
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:14px; color:#78350f;">Log Aktivitas</div>
                            <div style="font-size:12px; color:#92400e; margin-top:2px;">Pantau aktivitas sistem</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- 10 PENGAJUAN TERBARU --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden; margin-bottom:24px;">
                <div style="padding:20px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0;">🕒 10 Pengajuan Terbaru</h3>
                        <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">Aktivitas pengajuan surat terakhir</p>
                    </div>
                    <a href="{{ route('kelurahan.surat.riwayat') }}"
                       style="padding:8px 16px; background:#eff6ff; color:#1e40af; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700;">
                        Lihat Semua →
                    </a>
                </div>

                @if ($terbaru->count() > 0)
                    <div>
                        @foreach ($terbaru as $item)
                            @php
                                $cfg = [
                                    'diajukan_ke_rt'     => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => '⏳', 'label' => 'Menunggu RT'],
                                    'diverifikasi_rt'    => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '✓', 'label' => 'Diverifikasi RT'],
                                    'diproses_kelurahan' => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => '⚙️', 'label' => 'Diproses'],
                                    'selesai'            => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => '✓', 'label' => 'Selesai'],
                                    'ditolak_rt'         => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => '✕', 'label' => 'Ditolak RT'],
                                    'ditolak_kelurahan'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => '✕', 'label' => 'Ditolak Kel.'],
                                ];
                                $c = $cfg[$item->status] ?? ['bg' => '#e5e7eb', 'color' => '#374151', 'icon' => '·', 'label' => $item->status];

                                $layananIcon = '📄';
                                $lay = strtolower($item->keperluan);
                                if (str_contains($lay, 'domisili')) $layananIcon = '🏠';
                                elseif (str_contains($lay, 'sktm') || str_contains($lay, 'tidak mampu')) $layananIcon = '🤝';
                                elseif (str_contains($lay, 'sku') || str_contains($lay, 'usaha')) $layananIcon = '🏪';
                            @endphp
                            <div style="padding:16px 24px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:16px; transition:0.15s;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">
                                <div style="flex-shrink:0; width:44px; height:44px; background:#f3f4f6; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px;">
                                    {{ $layananIcon }}
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="font-family:monospace; font-size:11.5px; font-weight:700; color:#1e40af; margin-bottom:2px;">
                                        {{ $item->nomor_pengajuan }}
                                    </div>
                                    <div style="font-size:13px; color:#374151; font-weight:500; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        {{ $item->warga->name ?? '-' }} — {{ $item->keperluan }}
                                    </div>
                                    <div style="font-size:11px; color:#9ca3af; margin-top:3px;">
                                        {{ $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                </div>
                                <div style="flex-shrink:0; text-align:right;">
                                    <span style="padding:5px 12px; border-radius:20px; font-size:11px; font-weight:700; background:{{ $c['bg'] }}; color:{{ $c['color'] }}; display:inline-flex; align-items:center; gap:4px; white-space:nowrap;">
                                        {{ $c['icon'] }} {{ $c['label'] }}
                                    </span>
                                    <div style="margin-top:6px;">
                                        <a href="{{ route('surat.show', $item) }}"
                                           style="font-size:12px; color:#2563eb; text-decoration:none; font-weight:600;">
                                            Detail →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding:60px 20px; text-align:center;">
                        <div style="font-size:64px; margin-bottom:12px;">📭</div>
                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                            Belum Ada Pengajuan
                        </div>
                        <div style="font-size:13px; color:#6b7280;">
                            Belum ada aktivitas pengajuan surat.
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>