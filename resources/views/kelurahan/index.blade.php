<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    ⚙️ Proses Surat - Kelurahan
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Kelola dan proses pengajuan surat dari RT
                </p>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('kelurahan.dashboard') }}"
                   style="padding:10px 20px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                    📊 Dashboard
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

            @if ($errors->any())
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; align-items:flex-start; gap:12px;">
                    <div style="flex-shrink:0; width:28px; height:28px; background:#dc2626; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">!</div>
                    <div style="flex:1;">
                        <div style="font-weight:700; color:#991b1b; font-size:14px; margin-bottom:6px;">Ada kesalahan</div>
                        <ul style="margin:0; padding-left:18px; font-size:13px; color:#7f1d1d; line-height:1.7;">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- STATISTIK --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:14px; margin-bottom:24px;">

                <div style="background:linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(59,130,246,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">📥 Surat Baru</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['baru'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Perlu diproses</div>
                </div>

                <div style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(245,158,11,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">⚙️ Diproses</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['diproses'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Sedang diproses</div>
                </div>

                <div style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(16,185,129,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">✓ Selesai</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['selesai'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Surat terbit</div>
                </div>

                <div style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:16px; padding:20px; box-shadow:0 4px 16px rgba(239,68,68,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">✕ Ditolak</div>
                    <div style="font-size:34px; font-weight:800; margin-top:8px; line-height:1; position:relative; z-index:1;">{{ $statistik['ditolak'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:5px; position:relative; z-index:1;">Total penolakan</div>
                </div>
            </div>

            {{-- NOTIFIKASI: SURAT BARU DARI RT --}}
            @if (isset($suratBaru) && $suratBaru->count() > 0)
                <div style="background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border:1px solid #fcd34d; border-left:5px solid #f59e0b; border-radius:14px; padding:18px 22px; margin-bottom:20px;">
                    <div style="display:flex; align-items:flex-start; gap:14px;">
                        <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                            📥
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:15px; color:#78350f; margin-bottom:4px;">
                                {{ $suratBaru->count() }} Surat Baru dari RT — Menunggu Diproses
                            </div>
                            <p style="font-size:12.5px; color:#92400e; margin:0 0 14px 0; line-height:1.6;">
                                Surat berikut sudah diverifikasi RT dan siap untuk diproses kelurahan.
                            </p>

                            <div style="display:flex; flex-direction:column; gap:10px;">
                                @foreach ($suratBaru as $item)
                                    <div style="background:white; border:1px solid #fcd34d; border-radius:12px; padding:14px 16px;">
                                        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
                                            <div style="flex:1; min-width:200px;">
                                                <div style="font-family:monospace; font-size:12px; color:#b45309; font-weight:700; background:#fef3c7; padding:3px 8px; border-radius:6px; display:inline-block;">
                                                    {{ $item->nomor_pengajuan }}
                                                </div>
                                                <div style="font-size:13px; color:#374151; margin-top:8px;">
                                                    <strong>{{ $item->warga->name }}</strong> • RT {{ $item->warga->rt }}/{{ $item->warga->rw }} • {{ $item->keperluan }}
                                                </div>
                                                <div style="font-size:11px; color:#9ca3af; margin-top:6px;">
                                                    Diverifikasi RT: {{ $item->rt_verified_at ? $item->rt_verified_at->format('d F Y, H:i') : '-' }} WIB
                                                </div>
                                            </div>
                                            <a href="{{ route('surat.show', $item) }}"
                                               style="padding:8px 18px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700; white-space:nowrap; box-shadow:0 4px 12px rgba(245,158,11,0.25);">
                                                Proses Sekarang →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- FILTER --}}
            <form method="GET" style="background:white; padding:16px; border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:10px; align-items:end;">
                    <div style="min-width:220px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Cari</label>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="🔍 Nomor / nama warga..."
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
                            <option value="diverifikasi_rt" @selected(request('status') === 'diverifikasi_rt')>✓ Diverifikasi RT</option>
                            <option value="diproses_kelurahan" @selected(request('status') === 'diproses_kelurahan')>⚙️ Diproses Kelurahan</option>
                            <option value="selesai" @selected(request('status') === 'selesai')>🎉 Selesai</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="submit"
                                style="flex:1; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; padding:11px 20px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit;">
                            🔍 Filter
                        </button>
                        <a href="{{ route('kelurahan.surat.index') }}"
                           style="padding:11px 20px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; background:white; white-space:nowrap;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- TABEL --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(37,99,235,0.25);">
                            📋
                        </div>
                        <div>
                            <h3 style="font-size:17px; font-weight:700; color:#111827; margin:0; letter-spacing:-0.3px;">
                                Daftar Surat Aktif
                            </h3>
                            <p style="font-size:12.5px; color:#6b7280; margin:3px 0 0 0;">
                                Surat yang sedang diproses & sudah selesai
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
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Warga</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Layanan</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Dokumen</th>
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
                                        'diproses_kelurahan' => ['bg' => '#fef3c7', 'color' => '#92400e', 'dot' => '#f59e0b', 'icon' => '⚙️', 'label' => 'Diproses'],
                                        'selesai'            => ['bg' => '#d1fae5', 'color' => '#065f46', 'dot' => '#10b981', 'icon' => '✓',  'label' => 'Selesai'],
                                    ];
                                    $cfg = $statusConfig[$item->status] ?? ['bg' => '#e5e7eb', 'color' => '#374151', 'dot' => '#9ca3af', 'icon' => '·', 'label' => $item->status];

                                    $layananIcon = '📄';
                                    $layananBg = '#f3f4f6';
                                    $lay = strtolower($item->keperluan);
                                    if (str_contains($lay, 'domisili')) { $layananIcon = '🏠'; $layananBg = '#eff6ff'; }
                                    elseif (str_contains($lay, 'sktm') || str_contains($lay, 'tidak mampu')) { $layananIcon = '🤝'; $layananBg = '#fffbeb'; }
                                    elseif (str_contains($lay, 'sku') || str_contains($lay, 'usaha')) { $layananIcon = '🏪'; $layananBg = '#ecfdf5'; }
                                @endphp
                                <tr style="border-bottom:1px solid #f3f4f6; transition:0.15s;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        <div style="font-family:monospace; font-size:12px; font-weight:700; color:#1e40af; background:#eff6ff; padding:4px 10px; border-radius:8px; display:inline-block;">
                                            {{ $item->nomor_pengajuan }}
                                        </div>
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
                                        <div style="display:flex; gap:6px;">
                                            <a href="{{ route('surat.file', [$item, 'ktp']) }}" target="_blank"
                                               style="padding:4px 10px; background:#eff6ff; color:#1e40af; border-radius:6px; font-size:11px; text-decoration:none; font-weight:600;">
                                                🪪 KTP
                                            </a>
                                            <a href="{{ route('surat.file', [$item, 'kk']) }}" target="_blank"
                                               style="padding:4px 10px; background:#ecfdf5; color:#065f46; border-radius:6px; font-size:11px; text-decoration:none; font-weight:600;">
                                                📋 KK
                                            </a>
                                        </div>
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        <span style="padding:6px 12px; border-radius:20px; font-size:11.5px; font-weight:700; background:{{ $cfg['bg'] }}; color:{{ $cfg['color'] }}; display:inline-flex; align-items:center; gap:6px; white-space:nowrap;">
                                            <span style="width:7px; height:7px; border-radius:50%; background:{{ $cfg['dot'] }}; box-shadow:0 0 0 3px {{ $cfg['bg'] }};"></span>
                                            {{ $cfg['label'] }}
                                        </span>
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle;">
                                        <div style="font-size:13px; font-weight:600; color:#111827;">{{ $item->created_at->format('d/m/Y') }}</div>
                                        <div style="font-size:11px; color:#9ca3af; margin-top:2px;">{{ $item->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td style="padding:18px 20px; vertical-align:middle; text-align:right;">
                                        <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                            <a href="{{ route('surat.show', $item) }}"
                                               style="padding:8px 14px; background:#f3f4f6; color:#374151; border-radius:8px; font-size:12px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                                Detail
                                            </a>
                                            @if ($item->status === 'selesai')
                                                <a href="{{ route('surat.cetak', $item) }}" target="_blank"
                                                   style="padding:8px 14px; background:linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color:white; border-radius:8px; font-size:12px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; box-shadow:0 2px 8px rgba(124,58,237,0.25);">
                                                    📄 PDF
                                                </a>
                                            @else
                                                <a href="{{ route('surat.show', $item) }}"
                                                   style="padding:8px 14px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:8px; font-size:12px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; box-shadow:0 2px 8px rgba(37,99,235,0.25);">
                                                    ⚙️ Proses
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
                                            @if (request('cari') || request('jenis') || request('status'))
                                                Tidak Ada Hasil Ditemukan
                                            @else
                                                Tidak Ada Surat Aktif
                                            @endif
                                        </div>
                                        <div style="font-size:13px; color:#6b7280; max-width:400px; margin:0 auto; line-height:1.6;">
                                            @if (request('cari') || request('jenis') || request('status'))
                                                Coba ubah filter atau reset untuk melihat semua surat.
                                            @else
                                                Belum ada surat yang perlu diproses. Surat baru dari RT akan muncul di sini.
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