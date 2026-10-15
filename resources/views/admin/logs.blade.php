<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    📜 Log Aktivitas Sistem
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Rekam jejak semua aktivitas pengguna di sistem
                </p>
            </div>
            <div style="font-size:12.5px; color:#6b7280; display:flex; align-items:center; gap:6px; padding:8px 16px; background:white; border:1px solid #e5e7eb; border-radius:10px;">
                🗓️ {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1280px; margin:0 auto; padding:0 20px;">

            {{-- STATISTIK --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin-bottom:24px;">

                <div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(102,126,234,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">📊 Total Log</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ number_format($statistik['total']) }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Sepanjang waktu</div>
                </div>

                <div style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(16,185,129,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">📅 Hari Ini</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ number_format($statistik['hari_ini']) }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Aktivitas hari ini</div>
                </div>

                <div style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(245,158,11,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">🗓️ Minggu Ini</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ number_format($statistik['minggu_ini']) }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Aktivitas minggu ini</div>
                </div>

                <div style="background:linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(59,130,246,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">👤 User Aktif</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ number_format($statistik['user_aktif']) }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">7 hari terakhir</div>
                </div>
            </div>

            {{-- FILTER --}}
            <form method="GET" style="background:white; padding:16px; border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:10px; align-items:end;">

                    <div style="min-width:220px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Cari</label>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="🔍 Nama / aksi / deskripsi..."
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Aksi</label>
                        <select name="aksi" style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; background:white; font-family:inherit;">
                            <option value="">-- Semua Aksi --</option>
                            @foreach ($daftarAksi as $a)
                                <option value="{{ $a }}" @selected(request('aksi') === $a)>{{ str_replace('_', ' ', $a) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Role</label>
                        <select name="role" style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; background:white; font-family:inherit;">
                            <option value="">-- Semua Role --</option>
                            @foreach ($daftarRole as $r)
                                <option value="{{ $r }}" @selected(request('role') === $r)>{{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Sampai</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}"
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>

                    <div style="display:flex; gap:8px;">
                        <button type="submit"
                                style="flex:1; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; padding:11px 20px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit;">
                            🔍 Filter
                        </button>
                        <a href="{{ route('admin.logs') }}"
                           style="padding:11px 20px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; background:white; white-space:nowrap;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- TABEL --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                {{-- HEADER --}}
                <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(37,99,235,0.25);">
                            📜
                        </div>
                        <div>
                            <h3 style="font-size:17px; font-weight:700; color:#111827; margin:0; letter-spacing:-0.3px;">
                                Riwayat Aktivitas
                            </h3>
                            <p style="font-size:12.5px; color:#6b7280; margin:3px 0 0 0;">
                                Catatan lengkap aktivitas pengguna sistem
                            </p>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; padding:10px 16px; background:linear-gradient(135deg, #eff6ff 0%, #ede9fe 100%); border:1.5px solid #c7d2fe; border-radius:12px;">
                        <div style="font-size:26px; font-weight:800; background:linear-gradient(135deg, #2563eb, #7c3aed); -webkit-background-clip:text; -webkit-text-fill-color:transparent; line-height:1;">
                            {{ $logs->total() }}
                        </div>
                        <div style="font-size:11px; font-weight:700; color:#4338ca; text-transform:uppercase; letter-spacing:0.5px; line-height:1.2;">
                            Log<br>
                            <span style="font-size:10px; font-weight:600; color:#6366f1; opacity:0.8;">Ditampilkan</span>
                        </div>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; font-size:13.5px; text-align:left; border-collapse:collapse;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Waktu</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Pengguna</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Aksi</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Deskripsi</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px; text-align:right;">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                @php
                                    // Config role
                                    $roleConfig = [
                                        'warga'     => ['bg' => '#dbeafe', 'color' => '#1e40af', 'icon' => '👤'],
                                        'rt'        => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => '🏘️'],
                                        'kelurahan' => ['bg' => '#f3e8ff', 'color' => '#7e22ce', 'icon' => '🏛️'],
                                        'admin'     => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => '🛡️'],
                                    ];
                                    $rc = $roleConfig[$log->user_role] ?? ['bg' => '#e5e7eb', 'color' => '#374151', 'icon' => '·'];

                                    // Config aksi
                                    $aksiConfig = [
                                        'ajukan_surat'          => ['icon' => '📝', 'color' => '#1e40af'],
                                        'verifikasi_rt'         => ['icon' => '✅', 'color' => '#059669'],
                                        'proses_kelurahan'      => ['icon' => '⚙️', 'color' => '#d97706'],
                                        'cetak_pdf'             => ['icon' => '📄', 'color' => '#7c3aed'],
                                        'cetak_surat_pengantar' => ['icon' => '📋', 'color' => '#7c3aed'],
                                        'tambah_data_warga'     => ['icon' => '👥', 'color' => '#059669'],
                                        'edit_data_warga'       => ['icon' => '✏️', 'color' => '#d97706'],
                                        'hapus_data_warga'      => ['icon' => '🗑️', 'color' => '#dc2626'],
                                        'update_profil'         => ['icon' => '👤', 'color' => '#1e40af'],
                                        'hapus_foto_profil'     => ['icon' => '🖼️', 'color' => '#dc2626'],
                                        'hapus_ttd'             => ['icon' => '✍️', 'color' => '#dc2626'],
                                        'hapus_stempel'         => ['icon' => '🔖', 'color' => '#dc2626'],
                                        'rekap_pdf'             => ['icon' => '📊', 'color' => '#7c3aed'],
                                    ];
                                    $ac = $aksiConfig[$log->aksi] ?? ['icon' => '🔔', 'color' => '#6b7280'];

                                    // Format IP address
                                    $ip = $log->ip_address ?? '-';
                                    $ipDisplay = $ip;
                                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                                        $parts = explode('.', $ip);
                                        if (count($parts) === 4) {
                                            $ipDisplay = $parts[0] . '.' . $parts[1] . '.' . $parts[2] . '.***';
                                        }
                                    }
                                @endphp
                                <tr style="border-bottom:1px solid #f3f4f6; transition:0.15s;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">

                                    {{-- WAKTU --}}
                                    <td style="padding:16px 20px; vertical-align:middle; white-space:nowrap;">
                                        <div style="font-size:12.5px; font-weight:600; color:#111827;">
                                            {{ $log->created_at->translatedFormat('d M Y') }}
                                        </div>
                                        <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                                            {{ $log->created_at->format('H:i:s') }} • {{ $log->created_at->diffForHumans(null, true) }} lalu
                                        </div>
                                    </td>

                                    {{-- PENGGUNA --}}
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div style="width:34px; height:34px; border-radius:50%; background:{{ $rc['bg'] }}; color:{{ $rc['color'] }}; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; font-weight:700;">
                                                {{ strtoupper(substr($log->user_name ?? '?', 0, 1)) }}
                                            </div>
                                            <div style="min-width:0;">
                                                <div style="font-weight:600; color:#111827; font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:150px;">
                                                    {{ $log->user_name ?? 'System' }}
                                                </div>
                                                <div style="margin-top:2px;">
                                                    <span style="padding:2px 8px; border-radius:6px; font-size:10px; font-weight:700; background:{{ $rc['bg'] }}; color:{{ $rc['color'] }}; text-transform:uppercase; letter-spacing:0.5px;">
                                                        {{ $rc['icon'] }} {{ $log->user_role ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- AKSI --}}
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <span style="padding:5px 12px; border-radius:8px; font-size:11.5px; font-weight:700; background:{{ $ac['color'] }}15; color:{{ $ac['color'] }}; display:inline-flex; align-items:center; gap:6px; white-space:nowrap; border:1px solid {{ $ac['color'] }}30;">
                                            {{ $ac['icon'] }} {{ str_replace('_', ' ', $log->aksi) }}
                                        </span>
                                    </td>

                                    {{-- DESKRIPSI --}}
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="font-size:13px; color:#374151; line-height:1.5; max-width:400px;">
                                            {{ $log->deskripsi ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- IP --}}
                                    <td style="padding:16px 20px; vertical-align:middle; text-align:right;">
                                        <span style="font-family:monospace; font-size:11.5px; color:#6b7280; background:#f9fafb; padding:4px 10px; border-radius:6px; border:1px solid #e5e7eb;">
                                            {{ $ipDisplay }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding:60px 20px; text-align:center;">
                                        <div style="font-size:64px; margin-bottom:12px;">📭</div>
                                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                                            @if (request('cari') || request('aksi') || request('role') || request('dari'))
                                                Tidak Ada Hasil Ditemukan
                                            @else
                                                Belum Ada Aktivitas
                                            @endif
                                        </div>
                                        <div style="font-size:13px; color:#6b7280; max-width:400px; margin:0 auto; line-height:1.6;">
                                            @if (request('cari') || request('aksi') || request('role') || request('dari'))
                                                Coba ubah filter atau reset untuk melihat semua aktivitas.
                                            @else
                                                Belum ada aktivitas yang tercatat di sistem.
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($logs->hasPages())
                    <div style="padding:16px 22px; border-top:1px solid #f3f4f6; background:#fafbfc;">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>