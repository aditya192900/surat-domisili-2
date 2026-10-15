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

            {{-- TIMELINE --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:22px;">
                    <div style="font-size:20px;">📊</div>
                    <h3 style="font-size:16px; font-weight:700; color:#111827; margin:0;">Riwayat Proses</h3>
                </div>

                <div style="position:relative; padding-left:38px;">

                    {{-- Step 1: Diajukan --}}
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

                    {{-- Step 2: Verifikasi RT --}}
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
                    @endif

                    {{-- Step 3: Proses Kelurahan --}}
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
                    @else
                        <div style="position:relative; padding-bottom:10px; border-left:2px dashed #d1d5db;">
                            <div style="position:absolute; left:-12px; top:0; width:22px; height:22px; border-radius:50%; background:#e5e7eb; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:11px; font-weight:700; box-shadow:0 0 0 4px #f9fafb;">
                                ·
                            </div>
                            <div style="margin-left:20px; padding-top:2px;">
                                <div style="font-weight:600; font-size:13.5px; color:#9ca3af;">Menunggu Proses Kelurahan</div>
                                <div style="font-size:12px; color:#9ca3af; margin-top:3px;">Belum diproses</div>
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

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
                    <div style="padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin-bottom:5px;">👤 Nama Pemohon</div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">{{ $surat->warga->name ?? '-' }}</div>
                    </div>
                    <div style="padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin-bottom:5px;">🆔 NIK</div>
                        <div style="font-size:14px; font-family:monospace; font-weight:600; color:#111827;">
                            {{ substr($surat->warga->nik, 0, 6) . '******' . substr($surat->warga->nik, -4) }}
                        </div>
                    </div>
                    <div style="padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin-bottom:5px;">📍 Alamat</div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">{{ $surat->warga->alamat ?? '-' }}</div>
                    </div>
                    <div style="padding:12px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; margin-bottom:5px;">🏘️ RT / RW</div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">{{ $surat->warga->rt ?? '-' }} / {{ $surat->warga->rw ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- DOKUMEN --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
                    <div style="font-size:20px;">📎</div>
                    <h3 style="font-size:16px; font-weight:700; color:#111827; margin:0;">Dokumen Terlampir</h3>
                </div>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('surat.file', [$surat, 'ktp']) }}" target="_blank"
                       style="padding:12px 20px; background:#eff6ff; color:#1e40af; border-radius:10px; text-decoration:none; font-size:13.5px; font-weight:600; display:inline-flex; align-items:center; gap:8px; border:1.5px solid #bfdbfe;">
                        <span style="font-size:18px;">🪪</span> Lihat KTP
                    </a>
                    <a href="{{ route('surat.file', [$surat, 'kk']) }}" target="_blank"
                       style="padding:12px 20px; background:#ecfdf5; color:#065f46; border-radius:10px; text-decoration:none; font-size:13.5px; font-weight:600; display:inline-flex; align-items:center; gap:8px; border:1.5px solid #a7f3d0;">
                        <span style="font-size:18px;">📋</span> Lihat KK
                    </a>
                    @if ($surat->foto_path)
                        <a href="{{ route('surat.file', [$surat, 'foto']) }}" target="_blank"
                           style="padding:12px 20px; background:#fef3c7; color:#92400e; border-radius:10px; text-decoration:none; font-size:13.5px; font-weight:600; display:inline-flex; align-items:center; gap:8px; border:1.5px solid #fcd34d;">
                            <span style="font-size:18px;">📷</span> Lihat Pas Foto
                        </a>
                    @endif
                </div>
            </div>

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

            {{-- CETAK PDF SURAT RESMI (kelurahan/admin jika selesai) --}}
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