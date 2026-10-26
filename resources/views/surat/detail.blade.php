<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:20px; font-weight:700; color:#111827; margin:0;">
                    📄 Detail Surat
                </h2>
                <p style="font-size:12px; color:#6b7280; font-family:monospace; margin:3px 0 0 0;">
                    {{ $surat->nomor_pengajuan }}
                </p>
            </div>
            @php
                $role = auth()->user()->role;
                $kembaliUrl = match ($role) {
                    'rt'        => route('rt.surat.index'),
                    'kelurahan' => route('kelurahan.surat.index'),
                    'warga'     => route('warga.surat.index'),
                    default     => url('/'),
                };
            @endphp
            <a href="{{ $kembaliUrl }}"
               style="padding:9px 18px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:13px; font-weight:600; background:white; display:inline-flex; align-items:center; gap:6px;">
                ← Kembali
            </a>
        </div>
    </x-slot>

    @php
        $statusConfig = [
            'diajukan_ke_rt'     => ['gradient' => 'linear-gradient(135deg, #3b82f6 0%, #1e40af 100%)', 'icon' => '⏳', 'label' => 'Menunggu Verifikasi RT', 'sub' => 'Pengajuan Anda sedang menunggu verifikasi Ketua RT'],
            'diverifikasi_rt'    => ['gradient' => 'linear-gradient(135deg, #6366f1 0%, #4338ca 100%)', 'icon' => '✓',  'label' => 'Diverifikasi RT',        'sub' => 'Sedang diproses oleh petugas kelurahan'],
            'diproses_kelurahan' => ['gradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)', 'icon' => '⚙️', 'label' => 'Sedang Diproses',         'sub' => 'Surat Anda sedang diproses oleh kelurahan'],
            'selesai'            => ['gradient' => 'linear-gradient(135deg, #10b981 0%, #059669 100%)', 'icon' => '✓',  'label' => 'Surat Selesai',           'sub' => 'Surat Anda sudah siap diambil / diunduh'],
            'ditolak_rt'         => ['gradient' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', 'icon' => '✕',  'label' => 'Ditolak oleh RT',         'sub' => 'Silakan periksa alasan penolakan di bawah'],
            'ditolak_kelurahan'  => ['gradient' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)', 'icon' => '✕',  'label' => 'Ditolak oleh Kelurahan',  'sub' => 'Silakan periksa alasan penolakan di bawah'],
        ];
        $cfg = $statusConfig[$surat->status] ?? ['gradient' => 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)', 'icon' => '·', 'label' => $surat->status, 'sub' => ''];
        $user = auth()->user();
    @endphp

    <div class="py-8">
        <div style="max-width:920px; margin:0 auto; padding:0 20px;">

            {{-- FLASH MESSAGES --}}
            @if (session('success'))
                <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-left:4px solid #10b981; color:#065f46; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:24px; height:24px; background:#10b981; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">✓</div>
                    <div style="font-size:14px;">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; color:#991b1b; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:24px; height:24px; background:#dc2626; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">!</div>
                    <div style="font-size:14px;">{{ session('error') }}</div>
                </div>
            @endif

            {{-- HEADER STATUS --}}
            <div style="background:{{ $cfg['gradient'] }}; border-radius:18px; padding:28px; color:white; margin-bottom:20px; box-shadow:0 8px 24px rgba(0,0,0,0.15); position:relative; overflow:hidden;">
                <div style="position:absolute; top:-50px; right:-50px; width:200px; height:200px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                <div style="position:absolute; bottom:-80px; left:-40px; width:180px; height:180px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>

                <div style="display:flex; align-items:center; gap:16px; position:relative; z-index:1;">
                    <div style="width:64px; height:64px; background:rgba(255,255,255,0.2); backdrop-filter:blur(10px); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:32px; font-weight:700; flex-shrink:0;">
                        {{ $cfg['icon'] }}
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:12px; opacity:0.9; text-transform:uppercase; letter-spacing:1px; font-weight:600; margin-bottom:4px;">
                            Status Pengajuan
                        </div>
                        <div style="font-size:24px; font-weight:800; line-height:1.2; margin-bottom:4px;">
                            {{ $cfg['label'] }}
                        </div>
                        <div style="font-size:13px; opacity:0.9;">
                            {{ $cfg['sub'] }}
                        </div>
                    </div>
                </div>

                <div style="margin-top:20px; padding-top:16px; border-top:1px solid rgba(255,255,255,0.15); display:flex; gap:24px; flex-wrap:wrap; font-size:13px; position:relative; z-index:1;">
                    <div>
                        <div style="opacity:0.75; font-size:11px; text-transform:uppercase; font-weight:600;">Nomor Pengajuan</div>
                        <div style="font-family:monospace; font-weight:700; margin-top:2px;">{{ $surat->nomor_pengajuan }}</div>
                    </div>
                    @if ($surat->nomor_surat)
                        <div>
                            <div style="opacity:0.75; font-size:11px; text-transform:uppercase; font-weight:600;">Nomor Surat</div>
                            <div style="font-family:monospace; font-weight:700; margin-top:2px;">{{ $surat->nomor_surat }}</div>
                        </div>
                    @endif
                    <div>
                        <div style="opacity:0.75; font-size:11px; text-transform:uppercase; font-weight:600;">Tanggal Diajukan</div>
                        <div style="font-weight:600; margin-top:2px;">{{ $surat->created_at->format('d F Y, H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            {{-- PANEL BATALKAN VERIFIKASI (khusus RT) --}}
            @if ($user->role === 'rt' && in_array($surat->status, ['diverifikasi_rt', 'ditolak_rt']) && $user->rt === $surat->warga->rt)
                <div style="background:linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border:2px solid #fb923c; border-left:5px solid #ea580c; border-radius:16px; padding:22px 24px; margin-bottom:20px; box-shadow:0 8px 24px rgba(251,146,60,0.15);">
                    <div style="display:flex; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                        <div style="flex-shrink:0; width:52px; height:52px; background:linear-gradient(135deg, #f97316 0%, #ea580c 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:26px; box-shadow:0 4px 14px rgba(249,115,22,0.3);">
                            ⚠️
                        </div>
                        <div style="flex:1; min-width:240px;">
                            <div style="font-weight:800; color:#9a3412; font-size:15px; margin-bottom:6px;">
                                Sudah {{ $surat->status === 'diverifikasi_rt' ? 'Disetujui' : 'Ditolak' }} — Salah Klik?
                            </div>
                            <div style="font-size:13px; color:#c2410c; line-height:1.6;">
                                Anda {{ $surat->status === 'diverifikasi_rt' ? 'menyetujui' : 'menolak' }} surat ini pada
                                <strong>{{ $surat->rt_verified_at ? $surat->rt_verified_at->translatedFormat('d F Y, H:i') : '-' }} WIB</strong>.
                                Verifikasi masih bisa <strong>dibatalkan</strong> selama surat belum diproses kelurahan.
                            </div>
                        </div>
                        <div style="flex-shrink:0; display:flex; align-items:center;">
                            <button type="button" onclick="openBatalModal()"
                                    style="padding:12px 22px; background:linear-gradient(135deg, #f97316 0%, #ea580c 100%); color:white; border:none; border-radius:10px; font-size:13.5px; font-weight:700; cursor:pointer; font-family:inherit; box-shadow:0 4px 14px rgba(249,115,22,0.35); display:inline-flex; align-items:center; gap:8px; white-space:nowrap;">
                                ↩️ Batalkan Verifikasi
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ALASAN PENOLAKAN RT --}}
            @if ($surat->status === 'ditolak_rt' && $surat->catatan_rt)
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:5px solid #dc2626; border-radius:14px; padding:18px 22px; margin-bottom:20px;">
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="font-size:26px; flex-shrink:0;">⚠️</div>
                        <div style="flex:1;">
                            <div style="font-weight:700; color:#991b1b; font-size:14px; margin-bottom:6px;">
                                Surat Ditolak oleh RT
                            </div>
                            <div style="font-size:13.5px; color:#7f1d1d; line-height:1.6;">
                                <strong>Alasan:</strong> {{ $surat->catatan_rt }}
                            </div>
                            <div style="font-size:12px; color:#991b1b; margin-top:8px; opacity:0.8;">
                                Ditolak oleh: {{ $surat->rtVerifier->name ?? '-' }}
                                @if ($surat->rt_verified_at)
                                    • {{ $surat->rt_verified_at->format('d F Y, H:i') }} WIB
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- 🆕 TOMBOL AJUKAN ULANG (untuk WARGA yang ditolak) --}}
            @if ($user->id === $surat->user_id && in_array($surat->status, ['ditolak_rt', 'ditolak_kelurahan']))
                <div style="background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border:2px solid #fcd34d; border-left:5px solid #f59e0b; border-radius:16px; padding:22px 24px; margin-bottom:20px;">
                    <div style="display:flex; align-items:flex-start; gap:16px; flex-wrap:wrap;">
                        <div style="flex-shrink:0; width:52px; height:52px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:26px; box-shadow:0 4px 14px rgba(245,158,11,0.3);">
                            🔄
                        </div>
                        <div style="flex:1; min-width:240px;">
                            <div style="font-weight:800; color:#78350f; font-size:15px; margin-bottom:6px;">
                                Ingin Ajukan Ulang?
                            </div>
                            <div style="font-size:13px; color:#92400e; line-height:1.6;">
                                Surat ini ditolak. Anda bisa <strong>mengajukan ulang</strong> dengan data yang sudah terisi otomatis — cukup perbaiki bagian yang salah sesuai alasan penolakan di atas.
                            </div>
                        </div>
                        <div style="flex-shrink:0;">
                            <a href="{{ route('warga.surat.reapply', $surat) }}"
                               style="padding:12px 24px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:10px; text-decoration:none; font-size:13.5px; font-weight:700; box-shadow:0 4px 14px rgba(245,158,11,0.35); display:inline-flex; align-items:center; gap:8px; white-space:nowrap;">
                                🔄 Ajukan Ulang
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 🆕 INFO REAPPLY (kalau surat ini adalah pengajuan ulang) --}}
            @if ($surat->reapplyFrom)
                <div style="background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:1.5px solid #93c5fd; border-left:5px solid #2563eb; border-radius:14px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:flex-start; gap:12px;">
                    <div style="font-size:18px; flex-shrink:0;">ℹ️</div>
                    <div style="flex:1; font-size:13px; color:#1e40af; line-height:1.6;">
                        <strong>Pengajuan Ulang.</strong> Surat ini merupakan pengajuan ulang dari:
                        <a href="{{ route('surat.show', $surat->reapplyFrom) }}" style="font-family:monospace; font-weight:700; color:#1e40af; text-decoration:underline;">
                            {{ $surat->reapplyFrom->nomor_pengajuan }}
                        </a>
                    </div>
                </div>
            @endif

            {{-- ALASAN PENOLAKAN KELURAHAN --}}
            @if ($surat->status === 'ditolak_kelurahan' && $surat->catatan_kelurahan)
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:5px solid #dc2626; border-radius:14px; padding:18px 22px; margin-bottom:20px;">
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <div style="font-size:26px; flex-shrink:0;">⚠️</div>
                        <div style="flex:1;">
                            <div style="font-weight:700; color:#991b1b; font-size:14px; margin-bottom:6px;">
                                Surat Ditolak oleh Kelurahan
                            </div>
                            <div style="font-size:13.5px; color:#7f1d1d; line-height:1.6;">
                                <strong>Alasan:</strong> {{ $surat->catatan_kelurahan }}
                            </div>
                            <div style="font-size:12px; color:#991b1b; margin-top:8px; opacity:0.8;">
                                Ditolak oleh: {{ $surat->kelurahanProcessor->name ?? '-' }}
                                @if ($surat->kelurahan_processed_at)
                                    • {{ $surat->kelurahan_processed_at->format('d F Y, H:i') }} WIB
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- TOMBOL DOWNLOAD SURAT PENGANTAR RT (untuk WARGA) --}}
            @if ($user->id === $surat->user_id
                 && in_array($surat->status, ['diverifikasi_rt', 'diproses_kelurahan', 'selesai'])
                 && $surat->rt_verified_at)
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:2px solid #6ee7b7; border-left:5px solid #10b981; border-radius:16px; padding:24px; margin-bottom:20px; box-shadow:0 8px 24px rgba(16,185,129,0.15);">
                    <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                        <div style="flex-shrink:0; width:56px; height:56px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:28px; box-shadow:0 4px 14px rgba(16,185,129,0.3);">
                            📄
                        </div>
                        <div style="flex:1; min-width:220px;">
                            <div style="font-weight:800; color:#065f46; font-size:15.5px; margin-bottom:6px;">
                                🎉 Surat Pengantar RT Siap Diunduh!
                            </div>
                            <div style="font-size:13px; color:#047857; line-height:1.6;">
                                Surat Anda sudah diverifikasi RT pada <strong>{{ $surat->rt_verified_at->translatedFormat('d F Y, H:i') }} WIB</strong>.
                                Silakan download Surat Pengantar lalu <strong>bawa ke kantor kelurahan</strong> untuk diproses lebih lanjut.
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:18px;">
                        <a href="{{ route('warga.surat.pengantar', $surat) }}"
                           style="display:inline-flex; align-items:center; gap:10px; padding:14px 28px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; text-decoration:none; font-size:14.5px; font-weight:700; box-shadow:0 4px 14px rgba(16,185,129,0.35);">
                            📥 Download Surat Pengantar RT
                        </a>
                    </div>
                </div>
            @endif

            {{-- CETAK SURAT PENGANTAR RT (untuk RT setelah verifikasi) --}}
            @if ($user->role === 'rt' && $surat->rt_verified_at && $surat->status !== 'ditolak_rt' && $user->rt === $surat->warga->rt)
                <div style="background:linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius:16px; padding:24px; margin-bottom:20px; border:1.5px solid #93c5fd;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
                        <div style="font-size:24px;">📄</div>
                        <h3 style="font-size:16px; font-weight:700; color:#1e3a8a; margin:0;">Cetak Surat Pengantar RT</h3>
                    </div>
                    <p style="font-size:13.5px; color:#1e40af; margin-bottom:16px; line-height:1.6;">
                        Cetak Surat Pengantar RT (dengan pas foto & TTD). Berikan ke warga agar dibawa ke kantor kelurahan untuk diproses lebih lanjut.
                    </p>
                    <a href="{{ route('rt.surat.pengantar', $surat) }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:700; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                        🖨️ Download Surat Pengantar RT
                    </a>
                </div>
            @endif

            {{-- 🚀 TIMELINE (REVISI) --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:22px;">
                    <div style="font-size:20px;">📊</div>
                    <h3 style="font-size:16px; font-weight:700; color:#111827; margin:0;">Riwayat Proses</h3>
                </div>

                <div style="position:relative; padding-left:38px;">

                    {{-- ============================ --}}
                    {{-- STEP 1: SURAT DIAJUKAN --}}
                    {{-- ============================ --}}
                    <div style="position:relative; padding-bottom:26px; border-left:2px solid #10b981;">
                        <div style="position:absolute; left:-12px; top:0; width:22px; height:22px; border-radius:50%; background:#10b981; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:700; box-shadow:0 0 0 4px #ecfdf5;">
                            ✓
                        </div>
                        <div style="margin-left:20px;">
                            <div style="font-weight:700; font-size:14px; color:#065f46;">Surat Diajukan</div>
                            <div style="font-size:12.5px; color:#6b7280; margin-top:3px;">
                                {{ $surat->created_at->format('d F Y, H:i') }} WIB
                            </div>
                            <div style="font-size:12.5px; color:#374151; margin-top:5px; padding:6px 12px; background:#f0fdf4; border-radius:8px; display:inline-block;">
                                👤 {{ $surat->warga->name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- ============================ --}}
                    {{-- STEP 2: VERIFIKASI RT --}}
                    {{-- Muncul 2 kemungkinan: --}}
                    {{-- A. Sudah diverifikasi/ditolak RT → warna hijau/merah --}}
                    {{-- B. Masih menunggu RT → warna biru (in progress) --}}
                    {{-- ============================ --}}
                    @if ($surat->rt_verified_at)
                        @php
                            $rtOk = $surat->status !== 'ditolak_rt';
                            $rtColor = $rtOk ? '#10b981' : '#dc2626';
                            $rtBg = $rtOk ? '#ecfdf5' : '#fef2f2';
                            $rtIcon = $rtOk ? '✓' : '✕';
                            $rtTitle = $rtOk ? 'Diverifikasi RT' : 'Ditolak RT';
                        @endphp
                        <div style="position:relative; padding-bottom:26px; border-left:2px solid {{ $rtColor }};">
                            <div style="position:absolute; left:-12px; top:0; width:22px; height:22px; border-radius:50%; background:{{ $rtColor }}; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:700; box-shadow:0 0 0 4px {{ $rtBg }};">
                                {{ $rtIcon }}
                            </div>
                            <div style="margin-left:20px;">
                                <div style="font-weight:700; font-size:14px; color:{{ $rtColor }};">{{ $rtTitle }}</div>
                                <div style="font-size:12.5px; color:#6b7280; margin-top:3px;">
                                    {{ $surat->rt_verified_at->format('d F Y, H:i') }} WIB
                                </div>
                                <div style="font-size:12.5px; color:#374151; margin-top:5px; padding:6px 12px; background:#f9fafb; border-radius:8px; display:inline-block;">
                                    👤 {{ $surat->rtVerifier->name ?? '-' }}
                                </div>
                                @if ($surat->catatan_rt)
                                    <div style="margin-top:10px; padding:10px 14px; background:{{ $rtBg }}; border-left:3px solid {{ $rtColor }}; border-radius:8px; font-size:12.5px; color:{{ $rtOk ? '#065f46' : '#991b1b' }}; line-height:1.5;">
                                        <strong>Catatan:</strong> {{ $surat->catatan_rt }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Belum diverifikasi RT --}}
                        <div style="position:relative; padding-bottom:26px; border-left:2px dashed #3b82f6;">
                            <div style="position:absolute; left:-12px; top:0; width:22px; height:22px; border-radius:50%; background:#3b82f6; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:700; box-shadow:0 0 0 4px #dbeafe; animation:pulseBlue 2s infinite;">
                                ⏳
                            </div>
                            <div style="margin-left:20px;">
                                <div style="font-weight:700; font-size:14px; color:#1e40af;">Menunggu Verifikasi RT</div>
                                <div style="font-size:12.5px; color:#6b7280; margin-top:3px;">
                                    Sedang menunggu
                                </div>
                                <div style="font-size:12.5px; color:#1e40af; margin-top:8px; padding:10px 14px; background:#eff6ff; border-left:3px solid #3b82f6; border-radius:8px; line-height:1.5;">
                                    💡 Surat Anda sedang dalam antrian verifikasi Ketua RT. Proses ini biasanya selesai dalam beberapa jam.
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ============================ --}}
                    {{-- STEP 3: PROSES KELURAHAN --}}
                    {{-- Muncul jika: --}}
                    {{-- A. Sudah diproses kelurahan → warna hijau/merah --}}
                    {{-- B. Sudah diverifikasi RT tapi belum diproses kelurahan → abu-abu "Menunggu" --}}
                    {{-- C. Belum diverifikasi RT → skip (surat masih di RT) --}}
                    {{-- ============================ --}}
                    @if ($surat->kelurahan_processed_at && in_array($surat->status, ['diproses_kelurahan', 'selesai', 'ditolak_kelurahan']))
                        @php
                            $kelOk = $surat->status !== 'ditolak_kelurahan';
                            $kelColor = $kelOk ? '#10b981' : '#dc2626';
                            $kelBg = $kelOk ? '#ecfdf5' : '#fef2f2';
                            $kelIcon = $kelOk ? '✓' : '✕';
                            $kelTitle = match($surat->status) {
                                'diproses_kelurahan' => 'Diproses Kelurahan',
                                'selesai'            => 'Surat Selesai',
                                'ditolak_kelurahan'  => 'Ditolak Kelurahan',
                                default              => 'Diproses Kelurahan',
                            };
                        @endphp
                        <div style="position:relative; padding-bottom:26px; border-left:2px solid {{ $kelColor }};">
                            <div style="position:absolute; left:-12px; top:0; width:22px; height:22px; border-radius:50%; background:{{ $kelColor }}; display:flex; align-items:center; justify-content:center; color:white; font-size:12px; font-weight:700; box-shadow:0 0 0 4px {{ $kelBg }};">
                                {{ $kelIcon }}
                            </div>
                            <div style="margin-left:20px;">
                                <div style="font-weight:700; font-size:14px; color:{{ $kelColor }};">{{ $kelTitle }}</div>
                                <div style="font-size:12.5px; color:#6b7280; margin-top:3px;">
                                    {{ $surat->kelurahan_processed_at->format('d F Y, H:i') }} WIB
                                </div>
                                <div style="font-size:12.5px; color:#374151; margin-top:5px; padding:6px 12px; background:#f9fafb; border-radius:8px; display:inline-block;">
                                    👤 {{ $surat->kelurahanProcessor->name ?? '-' }}
                                </div>
                                @if ($surat->catatan_kelurahan)
                                    <div style="margin-top:10px; padding:10px 14px; background:{{ $kelBg }}; border-left:3px solid {{ $kelColor }}; border-radius:8px; font-size:12.5px; color:{{ $kelOk ? '#065f46' : '#991b1b' }}; line-height:1.5;">
                                        <strong>Catatan:</strong> {{ $surat->catatan_kelurahan }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif ($surat->rt_verified_at && $surat->status === 'diverifikasi_rt')
                        {{-- Sudah diverifikasi RT, belum diproses kelurahan --}}
                        <div style="position:relative; padding-bottom:10px; border-left:2px dashed #94a3b8;">
                            <div style="position:absolute; left:-12px; top:0; width:22px; height:22px; border-radius:50%; background:#cbd5e1; display:flex; align-items:center; justify-content:center; color:#64748b; font-size:11px; font-weight:700; box-shadow:0 0 0 4px #f1f5f9;">
                                ·
                            </div>
                            <div style="margin-left:20px; padding-top:2px;">
                                <div style="font-weight:700; font-size:13.5px; color:#64748b;">Menunggu Proses Kelurahan</div>
                                <div style="font-size:12px; color:#9ca3af; margin-top:3px;">
                                    Belum diproses oleh petugas kelurahan
                                </div>

                                {{-- 🆕 INFO BOX: Alur Selanjutnya --}}
                                <div style="margin-top:14px; padding:14px 16px; background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:1.5px solid #93c5fd; border-left:4px solid #2563eb; border-radius:10px;">
                                    <div style="display:flex; align-items:flex-start; gap:10px;">
                                        <div style="font-size:18px; flex-shrink:0;">ℹ️</div>
                                        <div style="flex:1;">
                                            <div style="font-weight:700; color:#1e40af; font-size:13px; margin-bottom:6px;">
                                                Alur Selanjutnya
                                            </div>
                                            <ol style="margin:0; padding-left:18px; font-size:12.5px; color:#1e40af; line-height:1.7;">
                                                <li>Surat <strong>sudah diverifikasi RT</strong> ✓</li>
                                                <li>Petugas <strong>kelurahan memproses</strong> dan menerbitkan nomor surat</li>
                                                <li>Setelah status <strong>"Selesai"</strong>, silakan datang ke kelurahan untuk <strong>mengambil berkas resmi</strong></li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                {{-- 🆕 NOTE: Surat Pengantar bukan berkas resmi --}}
                                <div style="margin-top:10px; padding:12px 14px; background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border:1.5px solid #fcd34d; border-left:4px solid #f59e0b; border-radius:10px;">
                                    <div style="display:flex; align-items:flex-start; gap:10px;">
                                        <div style="font-size:16px; flex-shrink:0;">⚠️</div>
                                        <div style="flex:1; font-size:12px; color:#78350f; line-height:1.6;">
                                            <strong>Penting:</strong> Surat Pengantar RT yang Anda download hanyalah <strong>pengantar</strong>, bukan berkas resmi. Berkas resmi (dengan tanda tangan & stempel Lurah) hanya bisa diambil setelah kelurahan menyelesaikan proses.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- INFO SURAT --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:22px;">
                    <div style="font-size:20px;">ℹ️</div>
                    <h3 style="font-size:16px; font-weight:700; color:#111827; margin:0;">Informasi Surat</h3>
                </div>

                @php
                    $layananIcon = '📄';
                    $layananColor = '#6b7280';
                    $layananBg = '#f3f4f6';
                    $lay = strtolower($surat->keperluan);
                    if (str_contains($lay, 'domisili')) { $layananIcon = '🏠'; $layananColor = '#1e40af'; $layananBg = '#eff6ff'; }
                    elseif (str_contains($lay, 'sktm') || str_contains($lay, 'tidak mampu')) { $layananIcon = '🤝'; $layananColor = '#b45309'; $layananBg = '#fffbeb'; }
                    elseif (str_contains($lay, 'sku') || str_contains($lay, 'usaha')) { $layananIcon = '🏪'; $layananColor = '#047857'; $layananBg = '#ecfdf5'; }
                @endphp

                <div style="background:{{ $layananBg }}; border-radius:12px; padding:16px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
                    <div style="font-size:32px;">{{ $layananIcon }}</div>
                    <div style="flex:1;">
                        <div style="font-size:11px; color:{{ $layananColor }}; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Jenis Layanan</div>
                        <div style="font-size:14px; font-weight:700; color:#111827; margin-top:2px;">{{ $surat->keperluan }}</div>
                    </div>
                </div>

                               <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">

                    {{-- KTP --}}
                    @php 
                        $ktpFiles = json_decode($surat->ktp_path, true) ?? [$surat->ktp_path];
                        $ktpFiles = array_filter($ktpFiles); // Hapus nilai kosong
                    @endphp
                    @if(count($ktpFiles) > 0)
                    <div style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:16px;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px; border-bottom:1px solid #bfdbfe; padding-bottom:10px;">
                            <span style="font-size:18px;">🪪</span>
                            <span style="font-weight:700; font-size:13px; color:#1e40af; text-transform:uppercase;">Scan KTP ({{ count($ktpFiles) }} File)</span>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($ktpFiles as $index => $file)
                                <a href="{{ route('surat.file', [$surat, 'ktp']) }}?file_index={{ $index }}" target="_blank"
                                   style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:white; border:1px solid #bfdbfe; border-radius:8px; text-decoration:none; color:#1e40af; font-size:12.5px; font-weight:600; transition:0.2s;"
                                   onmouseover="this.style.background='#f0f9ff'" onmouseout="this.style.background='white'">
                                    <span>Dokumen {{ $index + 1 }}</span>
                                    <span>Buka ↗</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- KK --}}
                    @php 
                        $kkFiles = json_decode($surat->kk_path, true) ?? [$surat->kk_path];
                        $kkFiles = array_filter($kkFiles);
                    @endphp
                    @if(count($kkFiles) > 0)
                    <div style="background:#ecfdf5; border:1.5px solid #a7f3d0; border-radius:12px; padding:16px;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px; border-bottom:1px solid #a7f3d0; padding-bottom:10px;">
                            <span style="font-size:18px;">📋</span>
                            <span style="font-weight:700; font-size:13px; color:#065f46; text-transform:uppercase;">Kartu Keluarga ({{ count($kkFiles) }} File)</span>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($kkFiles as $index => $file)
                                <a href="{{ route('surat.file', [$surat, 'kk']) }}?file_index={{ $index }}" target="_blank"
                                   style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:white; border:1px solid #a7f3d0; border-radius:8px; text-decoration:none; color:#065f46; font-size:12.5px; font-weight:600; transition:0.2s;"
                                   onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='white'">
                                    <span>Dokumen {{ $index + 1 }}</span>
                                    <span>Buka ↗</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- DOKUMEN 3 (PBB / Pas Foto / Akta / dll) --}}
                    @php 
                        $fotoFiles = json_decode($surat->foto_path, true) ?? [$surat->foto_path];
                        $fotoFiles = array_filter($fotoFiles);
                        
                        // Deteksi nama dokumen ke-3 berdasarkan jenis surat
                        $lay = strtolower($surat->keperluan);
                        $doc3Title = 'Dokumen Tambahan';
                        if (str_contains($lay, 'nikah')) $doc3Title = 'Pas Foto & Surat Sehat';
                        elseif (str_contains($lay, 'waris')) $doc3Title = 'Akta & KTP Saksi';
                        elseif (str_contains($lay, 'domisili') || str_contains($lay, 'sktm') || str_contains($lay, 'menikah') || str_contains($lay, 'rumah')) $doc3Title = 'Bukti Lunas PBB';
                    @endphp
                    @if(count($fotoFiles) > 0)
                    <div style="background:#fef3c7; border:1.5px solid #fcd34d; border-radius:12px; padding:16px;">
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px; border-bottom:1px solid #fcd34d; padding-bottom:10px;">
                            <span style="font-size:18px;">📄</span>
                            <span style="font-weight:700; font-size:13px; color:#92400e; text-transform:uppercase;">{{ $doc3Title }} ({{ count($fotoFiles) }} File)</span>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($fotoFiles as $index => $file)
                                <a href="{{ route('surat.file', [$surat, 'foto']) }}?file_index={{ $index }}" target="_blank"
                                   style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:white; border:1px solid #fcd34d; border-radius:8px; text-decoration:none; color:#92400e; font-size:12.5px; font-weight:600; transition:0.2s;"
                                   onmouseover="this.style.background='#fffbeb'" onmouseout="this.style.background='white'">
                                    <span>Dokumen {{ $index + 1 }}</span>
                                    <span>Buka ↗</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- DOKUMEN (dengan Preview) --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:18px; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="font-size:20px;">📎</div>
                        <h3 style="font-size:16px; font-weight:700; color:#111827; margin:0;">Dokumen Terlampir</h3>
                    </div>
                    <div style="font-size:11.5px; color:#6b7280; display:flex; align-items:center; gap:6px; padding:6px 12px; background:#f3f4f6; border-radius:8px;">
                        💡 Klik untuk memperbesar
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">

                    <div style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; overflow:hidden;">
                        <div style="padding:10px 14px; border-bottom:1px solid #bfdbfe; display:flex; align-items:center; justify-content:space-between;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:16px;">🪪</span>
                                <span style="font-weight:700; font-size:12.5px; color:#1e40af; text-transform:uppercase; letter-spacing:0.5px;">KTP</span>
                            </div>
                            <a href="{{ route('surat.file', [$surat, 'ktp']) }}" target="_blank"
                               style="font-size:11px; color:#1e40af; text-decoration:none; font-weight:600;">
                                Buka ↗
                            </a>
                        </div>
                        <div onclick="openPreview('{{ route('surat.file', [$surat, 'ktp']) }}', 'KTP - {{ $surat->warga->name }}')"
                             style="cursor:zoom-in; position:relative; background:#f0f9ff; height:180px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            <img src="{{ route('surat.file', [$surat, 'ktp']) }}" loading="lazy" alt="KTP"
                                 style="max-width:100%; max-height:100%; object-fit:contain;">
                            <div style="position:absolute; bottom:8px; right:8px; background:rgba(0,0,0,0.7); color:white; padding:4px 10px; border-radius:6px; font-size:10.5px; font-weight:600;">
                                🔍 Perbesar
                            </div>
                        </div>
                    </div>

                    <div style="background:#ecfdf5; border:1.5px solid #a7f3d0; border-radius:12px; overflow:hidden;">
                        <div style="padding:10px 14px; border-bottom:1px solid #a7f3d0; display:flex; align-items:center; justify-content:space-between;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:16px;">📋</span>
                                <span style="font-weight:700; font-size:12.5px; color:#065f46; text-transform:uppercase; letter-spacing:0.5px;">Kartu Keluarga</span>
                            </div>
                            <a href="{{ route('surat.file', [$surat, 'kk']) }}" target="_blank"
                               style="font-size:11px; color:#065f46; text-decoration:none; font-weight:600;">
                                Buka ↗
                            </a>
                        </div>
                        <div onclick="openPreview('{{ route('surat.file', [$surat, 'kk']) }}', 'KK - {{ $surat->warga->name }}')"
                             style="cursor:zoom-in; position:relative; background:#f0fdf4; height:180px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            <img src="{{ route('surat.file', [$surat, 'kk']) }}" loading="lazy" alt="KK"
                                 style="max-width:100%; max-height:100%; object-fit:contain;">
                            <div style="position:absolute; bottom:8px; right:8px; background:rgba(0,0,0,0.7); color:white; padding:4px 10px; border-radius:6px; font-size:10.5px; font-weight:600;">
                                🔍 Perbesar
                            </div>
                        </div>
                    </div>

                                        {{-- BUKTI LUNAS PBB --}}
                    @if ($surat->foto_path)
                        <div style="background:#fef3c7; border:1.5px solid #fcd34d; border-radius:12px; overflow:hidden;">
                            <div style="padding:10px 14px; border-bottom:1px solid #fcd34d; display:flex; align-items:center; justify-content:space-between;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="font-size:16px;">📄</span>
                                    <span style="font-weight:700; font-size:12.5px; color:#92400e; text-transform:uppercase; letter-spacing:0.5px;">Bukti Lunas PBB</span>
                                </div>
                                <a href="{{ route('surat.file', [$surat, 'foto']) }}" target="_blank"
                                   style="font-size:11px; color:#92400e; text-decoration:none; font-weight:600;">
                                    Buka ↗
                                </a>
                            </div>
                            <div onclick="openPreview('{{ route('surat.file', [$surat, 'foto']) }}', 'Bukti Lunas PBB - {{ $surat->warga->name }}')"
                                 style="cursor:zoom-in; position:relative; background:#fffbeb; height:180px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                <img src="{{ route('surat.file', [$surat, 'foto']) }}" loading="lazy" alt="Bukti Lunas PBB"
                                     style="max-width:100%; max-height:100%; object-fit:contain;">
                                <div style="position:absolute; bottom:8px; right:8px; background:rgba(0,0,0,0.7); color:white; padding:4px 10px; border-radius:6px; font-size:10.5px; font-weight:600;">
                                    🔍 Perbesar
                                </div>
                            </div>
                        </div>
                    
                    @endif
                </div>
            </div>

            {{-- MODAL PREVIEW GAMBAR --}}
            <div id="previewModal"
                 onclick="if(event.target === this) closePreview()"
                 style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.9); z-index:9999; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(8px);">
                <div style="position:relative; max-width:95vw; max-height:95vh; display:flex; flex-direction:column;">
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:rgba(255,255,255,0.1); border-radius:12px 12px 0 0; backdrop-filter:blur(10px);">
                        <div id="previewTitle" style="color:white; font-weight:700; font-size:14px;"></div>
                        <div style="display:flex; gap:8px;">
                            <a id="previewDownload" href="#" download target="_blank"
                               style="padding:6px 14px; background:white; color:#111827; border-radius:8px; text-decoration:none; font-size:12px; font-weight:700;">
                                📥 Download
                            </a>
                            <button onclick="closePreview()"
                                    style="width:32px; height:32px; background:rgba(255,255,255,0.2); color:white; border:none; border-radius:8px; cursor:pointer; font-size:18px; display:flex; align-items:center; justify-content:center;">
                                ×
                            </button>
                        </div>
                    </div>
                    <div style="flex:1; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.5); border-radius:0 0 12px 12px; padding:16px; overflow:auto;">
                        <img id="previewImage" src="" alt="Preview"
                             style="max-width:100%; max-height:calc(95vh - 80px); object-fit:contain; border-radius:8px;">
                    </div>
                </div>
            </div>

            {{-- MODAL BATALKAN VERIFIKASI --}}
            @if ($user->role === 'rt' && in_array($surat->status, ['diverifikasi_rt', 'ditolak_rt']) && $user->rt === $surat->warga->rt)
                <div id="batalModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(6px);">
                    <div style="background:white; border-radius:16px; max-width:500px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden;">

                        <div style="padding:20px 24px; background:linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-bottom:1px solid #fb923c; display:flex; align-items:center; gap:14px;">
                            <div style="width:44px; height:44px; background:linear-gradient(135deg, #f97316 0%, #ea580c 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px;">↩️</div>
                            <div style="flex:1;">
                                <h3 style="font-size:16px; font-weight:700; color:#7c2d12; margin:0;">Batalkan Verifikasi?</h3>
                                <p style="font-size:12px; color:#c2410c; margin:2px 0 0 0;">Surat akan kembali ke status awal</p>
                            </div>
                            <button type="button" onclick="closeBatalModal()"
                                    style="width:32px; height:32px; background:white; border:none; border-radius:8px; cursor:pointer; font-size:18px; color:#c2410c; display:flex; align-items:center; justify-content:center;">×</button>
                        </div>

                        <form action="{{ route('rt.surat.batalkan-verifikasi', $surat) }}" method="POST" style="padding:24px;">
                            @csrf

                            <div style="background:#fafbfc; border:1.5px solid #e5e7eb; border-radius:12px; padding:14px 18px; margin-bottom:20px;">
                                <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">Surat yang akan dibatalkan</div>
                                <div style="font-size:15px; font-weight:700; color:#111827;">{{ $surat->warga->name }}</div>
                                <div style="font-size:12px; color:#6b7280; font-family:monospace; margin-top:3px;">{{ $surat->nomor_pengajuan }}</div>
                            </div>

                            <div style="margin-bottom:20px;">
                                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">
                                    Alasan Pembatalan <span style="font-size:11.5px; color:#6b7280; font-weight:500;">(opsional)</span>
                                </label>
                                <textarea name="alasan_batal" rows="3"
                                          placeholder="Contoh: Salah klik, data perlu diperiksa ulang..."
                                          style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-family:inherit; font-size:13.5px; resize:vertical; box-sizing:border-box;"></textarea>
                            </div>

                            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:12.5px; color:#991b1b; line-height:1.6;">
                                <strong>⚠️ Perhatian:</strong> Surat akan kembali ke status <strong>"Menunggu Verifikasi RT"</strong>.
                            </div>

                            <div style="display:flex; gap:10px; justify-content:flex-end;">
                                <button type="button" onclick="closeBatalModal()"
                                        style="padding:11px 22px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit;">
                                    Batal
                                </button>
                                <button type="submit"
                                        style="padding:11px 24px; background:linear-gradient(135deg, #f97316 0%, #ea580c 100%); color:white; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; box-shadow:0 4px 14px rgba(249,115,22,0.3);">
                                    ↩️ Ya, Batalkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <script>
                function openPreview(url, title) {
                    document.getElementById('previewImage').src = url;
                    document.getElementById('previewTitle').textContent = title;
                    document.getElementById('previewDownload').href = url;
                    document.getElementById('previewModal').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }

                function closePreview() {
                    document.getElementById('previewModal').style.display = 'none';
                    document.body.style.overflow = '';
                }

                function openBatalModal() {
                    document.getElementById('batalModal').style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }

                function closeBatalModal() {
                    document.getElementById('batalModal').style.display = 'none';
                    document.body.style.overflow = '';
                }

                document.getElementById('batalModal')?.addEventListener('click', function(e) {
                    if (e.target === this) closeBatalModal();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closePreview();
                        closeBatalModal();
                    }
                });
            </script>

            <style>
                @keyframes pulseBlue {
                    0%, 100% { box-shadow: 0 0 0 4px #dbeafe; transform: scale(1); }
                    50% { box-shadow: 0 0 0 6px #bfdbfe; transform: scale(1.1); }
                }
            </style>

            {{-- AKSI RT --}}
            @if ($user->role === 'rt' && $surat->status === 'diajukan_ke_rt' && $user->rt === $surat->warga->rt)
                <div style="background:linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius:16px; padding:24px; margin-bottom:20px; border:1.5px solid #bae6fd;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
                        <div style="font-size:22px;">✍️</div>
                        <h3 style="font-size:16px; font-weight:700; color:#0c4a6e; margin:0;">Verifikasi Surat</h3>
                    </div>

                    <form method="POST" action="{{ route('rt.surat.verifikasi', $surat) }}">
                        @csrf
                        <div style="margin-bottom:14px;">
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                Catatan <span style="font-size:11px; color:#9ca3af; font-weight:400;">(wajib jika menolak)</span>
                            </label>
                            <textarea name="catatan" rows="3"
                                      style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-family:inherit; font-size:14px; resize:vertical; color:#111827;"
                                      placeholder="Tulis catatan untuk warga...">{{ old('catatan') }}</textarea>
                        </div>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <button type="submit" name="aksi" value="setuju"
                                    style="flex:1; min-width:140px; padding:12px 20px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(16,185,129,0.3); font-family:inherit;">
                                ✓ Setujui Surat
                            </button>
                            <button type="submit" name="aksi" value="tolak"
                                    onclick="return confirm('Yakin ingin menolak surat ini? Pastikan alasan sudah diisi.')"
                                    style="flex:1; min-width:140px; padding:12px 20px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(239,68,68,0.3); font-family:inherit;">
                                ✕ Tolak Surat
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- AKSI KELURAHAN --}}
            @if (in_array($user->role, ['kelurahan', 'admin']) && in_array($surat->status, ['diverifikasi_rt', 'diproses_kelurahan']))
                <div style="background:linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius:16px; padding:24px; margin-bottom:20px; border:1.5px solid #fcd34d;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
                        <div style="font-size:22px;">⚙️</div>
                        <h3 style="font-size:16px; font-weight:700; color:#78350f; margin:0;">Proses Surat</h3>
                    </div>

                    <form method="POST" action="{{ route('kelurahan.surat.proses', $surat) }}">
                        @csrf

                        @if ($surat->status === 'diproses_kelurahan')
                            <div style="margin-bottom:14px;">
                                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                    Nomor Surat
                                </label>
                                <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}"
                                       placeholder="Contoh: 470/0001/2026"
                                       style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; color:#111827; background:white;">
                                <div style="font-size:11px; color:#6b7280; margin-top:6px;">Kosongkan untuk generate otomatis</div>
                            </div>
                        @endif

                        <div style="margin-bottom:14px;">
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                Catatan <span style="font-size:11px; color:#9ca3af; font-weight:400;">(wajib jika menolak)</span>
                            </label>
                            <textarea name="catatan" rows="3"
                                      style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-family:inherit; font-size:14px; resize:vertical; color:#111827; background:white;"
                                      placeholder="Tulis catatan untuk warga...">{{ old('catatan', $surat->catatan_kelurahan) }}</textarea>
                        </div>

                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            @if ($surat->status === 'diverifikasi_rt')
                                <button type="submit" name="aksi" value="proses"
                                        style="flex:1; min-width:140px; padding:12px 20px; background:linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(59,130,246,0.3); font-family:inherit;">
                                    ▶ Proses Sekarang
                                </button>
                            @endif
                            @if ($surat->status === 'diproses_kelurahan')
                                <button type="submit" name="aksi" value="selesai"
                                        style="flex:1; min-width:140px; padding:12px 20px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(16,185,129,0.3); font-family:inherit;">
                                    ✓ Tandai Selesai
                                </button>
                            @endif
                            <button type="submit" name="aksi" value="tolak"
                                    onclick="return confirm('Yakin ingin menolak surat ini? Pastikan alasan sudah diisi.')"
                                    style="flex:1; min-width:140px; padding:12px 20px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border:none; border-radius:10px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(239,68,68,0.3); font-family:inherit;">
                                ✕ Tolak
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- CETAK PDF SURAT RESMI --}}
            @if (in_array($user->role, ['kelurahan', 'admin']) && $surat->status === 'selesai')
                <div style="background:linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); border-radius:16px; padding:24px; margin-bottom:20px; border:1.5px solid #c4b5fd;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
                        <div style="font-size:22px;">🖨️</div>
                        <h3 style="font-size:16px; font-weight:700; color:#4c1d95; margin:0;">Cetak Surat Resmi</h3>
                    </div>
                    <p style="font-size:13px; color:#5b21b6; margin-bottom:16px;">
                        Surat sudah selesai. Unduh versi PDF resmi dengan QR Code verifikasi.
                    </p>
                    <a href="{{ route('surat.cetak', $surat) }}" target="_blank"
                       style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:700; box-shadow:0 4px 14px rgba(124,58,237,0.3);">
                        📄 Download PDF Surat
                    </a>
                </div>
            @endif

            {{-- INFO WARGA JIKA SELESAI --}}
            @if ($user->id === $surat->user_id && $surat->status === 'selesai')
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius:16px; padding:24px; margin-bottom:20px; border:1.5px solid #a7f3d0;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
                        <div style="font-size:24px;">🎉</div>
                        <h3 style="font-size:16px; font-weight:700; color:#065f46; margin:0;">Surat Anda Sudah Selesai!</h3>
                    </div>
                    <p style="font-size:13.5px; color:#047857; margin-bottom:16px; line-height:1.6;">
                        Surat Keterangan Anda telah diproses dan selesai. Silakan datang ke kelurahan untuk mengambil surat fisik.
                    </p>
                    <a href="{{ route('verifikasi.publik', ['nomor' => $surat->nomor_pengajuan]) }}" target="_blank"
                       style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:700; box-shadow:0 4px 14px rgba(16,185,129,0.3);">
                        🔍 Lihat & Verifikasi Surat
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>