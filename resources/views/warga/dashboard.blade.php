<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    👋 Selamat Datang, {{ auth()->user()->name }}
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Dashboard pelayanan surat Anda
                </p>
            </div>
            <div style="font-size:12.5px; color:#6b7280; display:flex; align-items:center; gap:6px; padding:8px 16px; background:white; border:1px solid #e5e7eb; border-radius:10px;">
                🗓️ {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1200px; margin:0 auto; padding:0 20px;">

            {{-- NOTIFIKASI SURAT SEDANG DIPROSES --}}
            @if ($sedangDiproses > 0)
                <div style="background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border:1px solid #fcd34d; border-left:5px solid #f59e0b; border-radius:14px; padding:16px 22px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                        ⏳
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:14.5px; color:#78350f;">
                            Anda memiliki <strong>{{ $sedangDiproses }} surat</strong> yang sedang diproses
                        </div>
                        <div style="font-size:12.5px; color:#92400e; margin-top:3px;">
                            Pantau status terkini di halaman "Surat Saya"
                        </div>
                    </div>
                    <a href="{{ route('warga.surat.index') }}"
                       style="padding:9px 18px; background:white; color:#92400e; border:1.5px solid #fcd34d; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700; white-space:nowrap;">
                        Lihat →
                    </a>
                </div>
            @endif

            {{-- STATISTIK PRIBADI (CLICKABLE) --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin-bottom:24px;">

                {{-- KARTU 1: TOTAL --}}
                <a href="{{ route('warga.surat.index') }}"
                   style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(102,126,234,0.3); position:relative; overflow:hidden; text-decoration:none; cursor:pointer; transition:0.2s; display:block;"
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

                {{-- KARTU 2: SEDANG DIPROSES --}}
                <a href="{{ route('warga.surat.index', ['kategori' => 'proses']) }}"
                   style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(245,158,11,0.3); position:relative; overflow:hidden; text-decoration:none; cursor:pointer; transition:0.2s; display:block;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(245,158,11,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(245,158,11,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">⏳ Sedang Diproses</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $statistik['proses'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Menunggu selesai</div>
                </a>

                {{-- KARTU 3: SELESAI --}}
                <a href="{{ route('warga.surat.index', ['kategori' => 'selesai']) }}"
                   style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(16,185,129,0.3); position:relative; overflow:hidden; text-decoration:none; cursor:pointer; transition:0.2s; display:block;"
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

                {{-- KARTU 4: DITOLAK --}}
                <a href="{{ route('warga.surat.index', ['kategori' => 'ditolak']) }}"
                   style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(239,68,68,0.3); position:relative; overflow:hidden; text-decoration:none; cursor:pointer; transition:0.2s; display:block;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(239,68,68,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(239,68,68,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✕ Ditolak</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $statistik['ditolak'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Perlu diperbaiki</div>
                </a>
            </div>

            {{-- QUICK ACTION --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:24px; margin-bottom:24px;">
                <div style="margin-bottom:18px;">
                    <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0;">⚡ Aksi Cepat</h3>
                    <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">Akses fitur utama dengan sekali klik</p>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px;">
                    <a href="{{ route('warga.surat.create') }}"
                       style="padding:20px; background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:1.5px solid #bfdbfe; border-radius:14px; text-decoration:none; display:flex; align-items:center; gap:14px; transition:0.2s;"
                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(37,99,235,0.15)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                            📝
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:14px; color:#1e40af;">Ajukan Surat Baru</div>
                            <div style="font-size:12px; color:#3b82f6; margin-top:2px;">Domisili, SKTM, atau SKU</div>
                        </div>
                    </a>

                    <a href="{{ route('warga.surat.index') }}"
                       style="padding:20px; background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1.5px solid #a7f3d0; border-radius:14px; text-decoration:none; display:flex; align-items:center; gap:14px; transition:0.2s;"
                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(16,185,129,0.15)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                            📋
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:14px; color:#065f46;">Surat Saya</div>
                            <div style="font-size:12px; color:#059669; margin-top:2px;">Lihat semua pengajuan</div>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       style="padding:20px; background:linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border:1.5px solid #fcd34d; border-radius:14px; text-decoration:none; display:flex; align-items:center; gap:14px; transition:0.2s;"
                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(245,158,11,0.15)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                            👤
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:14px; color:#78350f;">Edit Profil</div>
                            <div style="font-size:12px; color:#92400e; margin-top:2px;">Perbarui data pribadi</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- PENGAJUAN TERBARU --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden; margin-bottom:24px;">
                <div style="padding:20px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0;">🕒 Pengajuan Terbaru</h3>
                        <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">3 aktivitas terakhir Anda</p>
                    </div>
                    @if ($statistik['total'] > 0)
                        <a href="{{ route('warga.surat.index') }}"
                           style="padding:8px 16px; background:#eff6ff; color:#1e40af; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700;">
                            Lihat Semua →
                        </a>
                    @endif
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
                                        {{ $item->keperluan }}
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
                        <div style="font-size:13px; color:#6b7280; margin-bottom:20px; max-width:400px; margin-left:auto; margin-right:auto;">
                            Anda belum pernah mengajukan surat. Klik tombol di bawah untuk memulai.
                        </div>
                        <a href="{{ route('warga.surat.create') }}"
                           style="padding:12px 26px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                            + Ajukan Surat Sekarang
                        </a>
                    </div>
                @endif
            </div>

            {{-- INFO KONTAK --}}
            <div style="background:linear-gradient(135deg, #eff6ff 0%, #ede9fe 100%); border:1.5px solid #c7d2fe; border-radius:16px; padding:24px;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                    <div style="font-size:24px;">💬</div>
                    <div>
                        <div style="font-weight:700; font-size:14.5px; color:#1e40af;">Butuh Bantuan?</div>
                        <div style="font-size:12.5px; color:#4338ca; margin-top:2px;">Hubungi petugas kelurahan jika ada kendala</div>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-top:14px;">
                    <div style="padding:12px; background:white; border-radius:10px; font-size:12.5px;">
                        <div style="color:#9ca3af; font-weight:600; text-transform:uppercase; font-size:10.5px; letter-spacing:0.5px; margin-bottom:3px;">📞 Telepon</div>
                        <div style="font-weight:700; color:#374151;">@kelurahan('telepon')</div>
                    </div>
                    <div style="padding:12px; background:white; border-radius:10px; font-size:12.5px;">
                        <div style="color:#9ca3af; font-weight:600; text-transform:uppercase; font-size:10.5px; letter-spacing:0.5px; margin-bottom:3px;">💬 WhatsApp</div>
                        <div style="font-weight:700; color:#374151;">@kelurahan('whatsapp_format')</div>
                    </div>
                    <div style="padding:12px; background:white; border-radius:10px; font-size:12.5px;">
                        <div style="color:#9ca3af; font-weight:600; text-transform:uppercase; font-size:10.5px; letter-spacing:0.5px; margin-bottom:3px;">📧 Email</div>
                        <div style="font-weight:700; color:#374151; font-size:11px; word-break:break-all;">@kelurahan('email')</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>