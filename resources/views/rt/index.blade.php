<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    ✍️ Verifikasi Surat - RT {{ auth()->user()->rt }}
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Verifikasi pengajuan surat dari warga wilayah Anda
                </p>
            </div>
            <a href="{{ route('rt.surat.riwayat') }}"
               style="padding:10px 20px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                📋 Lihat Riwayat Surat
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1200px; margin:0 auto; padding:0 20px;">

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

            @php
                $rtId = auth()->user()->rt;
                $totalBaru = $jumlahSuratBaru ?? 0;
                $totalDiproses = \App\Models\SuratDomisili::whereHas('warga', fn($q) => $q->where('rt', $rtId))
                    ->whereIn('status', ['diverifikasi_rt', 'diproses_kelurahan'])->count();
                $totalSelesai = \App\Models\SuratDomisili::whereHas('warga', fn($q) => $q->where('rt', $rtId))
                    ->where('status', 'selesai')->count();
                $totalDitolak = \App\Models\SuratDomisili::whereHas('warga', fn($q) => $q->where('rt', $rtId))
                    ->whereIn('status', ['ditolak_rt', 'ditolak_kelurahan'])->count();
            @endphp

            {{-- STATISTIK (CLICKABLE) --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:14px; margin-bottom:24px;">

                {{-- KARTU 1: SURAT BARU --}}
                <a href="{{ route('rt.surat.index') }}"
                   style="background:linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(59,130,246,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(59,130,246,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(59,130,246,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">📥 Surat Baru</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $totalBaru }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Perlu diverifikasi</div>
                </a>

                {{-- KARTU 2: DIPROSES --}}
                <a href="{{ route('rt.surat.riwayat', ['kategori' => 'diproses']) }}"
                   style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(245,158,11,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(245,158,11,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(245,158,11,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">⚙️ Diproses</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $totalDiproses }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Diteruskan kelurahan</div>
                </a>

                {{-- KARTU 3: SELESAI --}}
                <a href="{{ route('rt.surat.riwayat', ['kategori' => 'selesai']) }}"
                   style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(16,185,129,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(16,185,129,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(16,185,129,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✓ Selesai</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $totalSelesai }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Surat terbit</div>
                </a>

                {{-- KARTU 4: DITOLAK --}}
                <a href="{{ route('rt.surat.riwayat', ['kategori' => 'ditolak']) }}"
                   style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:14px; padding:18px; box-shadow:0 4px 14px rgba(239,68,68,0.3); position:relative; overflow:hidden; text-decoration:none; display:block; transition:0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(239,68,68,0.45)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(239,68,68,0.3)'">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
                        <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✕ Ditolak</div>
                        <div style="font-size:14px; opacity:0.7;">→</div>
                    </div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $totalDitolak }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Perlu ditindaklanjuti</div>
                </a>
            </div>

            {{-- NOTIFIKASI: SURAT BARU MASUK --}}
            @if (isset($jumlahSuratBaru) && $jumlahSuratBaru > 0)
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1px solid #a7f3d0; border-left:5px solid #10b981; border-radius:14px; padding:16px 20px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                        🔔
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:15px; color:#065f46;">
                            {{ $jumlahSuratBaru }} surat baru dari warga menunggu verifikasi Anda
                        </div>
                        <div style="font-size:12.5px; color:#047857; margin-top:3px;">
                            Segera periksa dan verifikasi agar tidak menumpuk
                        </div>
                    </div>
                </div>
            @endif

            {{-- NOTIFIKASI: SURAT DITOLAK KELURAHAN --}}
            @if (isset($ditolakKelurahan) && $ditolakKelurahan->count() > 0)
                <div style="background:linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border:1px solid #fecaca; border-left:5px solid #dc2626; border-radius:14px; padding:20px; margin-bottom:20px;">
                    <div style="display:flex; align-items:flex-start; gap:14px;">
                        <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(239,68,68,0.3);">
                            ⚠️
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:15px; color:#991b1b; margin-bottom:4px;">
                                {{ $ditolakKelurahan->count() }} Surat Ditolak Kelurahan
                            </div>
                            <p style="font-size:12.5px; color:#7f1d1d; margin:0 0 14px 0; line-height:1.6;">
                                Surat dari warga RT Anda yang sudah diverifikasi RT, namun ditolak oleh kelurahan. Mohon ditindaklanjuti ke warga terkait.
                            </p>

                            <div style="display:flex; flex-direction:column; gap:10px;">
                                @foreach ($ditolakKelurahan as $item)
                                    <div style="background:white; border:1px solid #fecaca; border-radius:12px; padding:14px 16px;">
                                        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
                                            <div style="flex:1; min-width:200px;">
                                                <div style="font-family:monospace; font-size:12px; color:#dc2626; font-weight:700; background:#fef2f2; padding:3px 8px; border-radius:6px; display:inline-block;">
                                                    {{ $item->nomor_pengajuan }}
                                                </div>
                                                <div style="font-size:13px; color:#374151; margin-top:8px;">
                                                    <strong>{{ $item->warga->name }}</strong> • {{ $item->keperluan }}
                                                </div>
                                                @if ($item->catatan_kelurahan)
                                                    <div style="margin-top:10px; padding:10px 12px; background:#fef2f2; border-left:3px solid #dc2626; border-radius:6px; font-size:12px; color:#991b1b; line-height:1.5;">
                                                        <strong>Alasan:</strong> {{ $item->catatan_kelurahan }}
                                                    </div>
                                                @endif
                                                <div style="font-size:11px; color:#9ca3af; margin-top:8px;">
                                                    Ditolak: {{ $item->kelurahan_processed_at ? $item->kelurahan_processed_at->format('d F Y, H:i') : '-' }} WIB
                                                    @if ($item->kelurahanProcessor)
                                                        oleh {{ $item->kelurahanProcessor->name }}
                                                    @endif
                                                </div>
                                            </div>
                                            <a href="{{ route('surat.show', $item) }}"
                                               style="padding:8px 16px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:8px; text-decoration:none; font-size:12px; font-weight:600; white-space:nowrap; box-shadow:0 4px 12px rgba(239,68,68,0.25);">
                                                Lihat Detail →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- TABEL --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                <div style="padding:18px 22px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0; display:flex; align-items:center; gap:8px;">
                            📋 Menunggu Verifikasi RT
                        </h3>
                        <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">
                            {{ $data->total() }} surat perlu ditinjau
                        </p>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; font-size:14px; text-align:left; border-collapse:collapse;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th style="padding:14px 22px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">No. Pengajuan</th>
                                <th style="padding:14px 22px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Warga</th>
                                <th style="padding:14px 22px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Layanan</th>
                                <th style="padding:14px 22px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Dokumen</th>
                                <th style="padding:14px 22px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Tanggal</th>
                                <th style="padding:14px 22px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px; text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                @php
                                    $icon = '📄';
                                    $lay = strtolower($item->keperluan);
                                    if (str_contains($lay, 'domisili')) $icon = '🏠';
                                    elseif (str_contains($lay, 'sktm') || str_contains($lay, 'tidak mampu')) $icon = '🤝';
                                    elseif (str_contains($lay, 'sku') || str_contains($lay, 'usaha')) $icon = '🏪';
                                @endphp
                                <tr style="border-bottom:1px solid #f3f4f6; transition:0.15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                                    <td style="padding:18px 22px; vertical-align:middle;">
                                        <div style="font-family:monospace; font-size:12px; font-weight:700; color:#1e40af; background:#eff6ff; padding:4px 8px; border-radius:6px; display:inline-block;">
                                            {{ $item->nomor_pengajuan }}
                                        </div>
                                    </td>
                                    <td style="padding:18px 22px; vertical-align:middle;">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            @if ($item->warga->foto)
                                                <img src="{{ route('profile.foto', $item->warga) }}"
                                                     style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb;">
                                            @else
                                                <div style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700;">
                                                    {{ $item->warga->inisial() }}
                                                </div>
                                            @endif
                                            <div>
                                                <div style="font-weight:600; color:#111827;">{{ $item->warga->name }}</div>
                                                <div style="font-size:11.5px; color:#9ca3af;">RT {{ $item->warga->rt }}/RW {{ $item->warga->rw }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:18px 22px; vertical-align:middle;">
                                        <div style="display:flex; align-items:flex-start; gap:8px;">
                                            <span style="font-size:20px; flex-shrink:0;">{{ $icon }}</span>
                                            <span style="font-weight:500; color:#374151; line-height:1.4; font-size:13px;">{{ $item->keperluan }}</span>
                                        </div>
                                    </td>
                                    <td style="padding:18px 22px; vertical-align:middle;">
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
                                    <td style="padding:18px 22px; vertical-align:middle;">
                                        <div style="font-size:13px; font-weight:600; color:#111827;">{{ $item->created_at->format('d/m/Y') }}</div>
                                        <div style="font-size:11.5px; color:#9ca3af; margin-top:2px;">{{ $item->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td style="padding:18px 22px; vertical-align:middle; text-align:right;">
                                        <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                            <a href="{{ route('surat.show', $item) }}"
                                               style="padding:8px 14px; background:#f3f4f6; color:#374151; border-radius:8px; font-size:12px; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                                Detail
                                            </a>
                                            <form method="POST" action="{{ route('rt.surat.verifikasi', $item) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" name="aksi" value="setuju"
                                                        style="padding:8px 14px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; box-shadow:0 2px 8px rgba(16,185,129,0.25); font-family:inherit;">
                                                    ✓ Setuju
                                                </button>
                                            </form>
                                            <button type="button"
                                                    onclick="bukaModalTolak('{{ route('rt.surat.verifikasi', $item) }}', '{{ $item->nomor_pengajuan }}', '{{ $item->warga->name }}')"
                                                    style="padding:8px 14px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border:none; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; box-shadow:0 2px 8px rgba(239,68,68,0.25); font-family:inherit;">
                                                ✕ Tolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding:60px 20px; text-align:center;">
                                        <div style="font-size:64px; margin-bottom:12px;">🎉</div>
                                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                                            Tidak Ada Surat Menunggu
                                        </div>
                                        <div style="font-size:13px; color:#6b7280; max-width:400px; margin:0 auto; line-height:1.6;">
                                            Semua pengajuan sudah diverifikasi. Kerja bagus! 🎊
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

    {{-- MODAL TOLAK --}}
    <div id="modalTolak" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div style="background:white; padding:28px; border-radius:18px; max-width:500px; width:100%; box-shadow:0 20px 50px rgba(0,0,0,0.3);">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:44px; height:44px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; color:white; box-shadow:0 4px 12px rgba(239,68,68,0.3);">
                        ⚠️
                    </div>
                    <div>
                        <h3 style="font-weight:700; font-size:16px; margin:0; color:#111827;">Alasan Penolakan</h3>
                        <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">
                            Nomor: <span id="modalNomor" style="font-family:monospace; font-weight:600;"></span>
                        </p>
                    </div>
                </div>
                <button type="button" onclick="tutupModalTolak()"
                        style="background:none; border:none; font-size:26px; cursor:pointer; color:#9ca3af; line-height:1; padding:0; margin-top:-4px;">&times;</button>
            </div>

            <div style="padding:10px 14px; background:#f9fafb; border-radius:10px; margin-bottom:16px;">
                <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Warga</div>
                <div style="font-size:14px; font-weight:600; color:#111827; margin-top:2px;" id="modalWarga"></div>
            </div>

            <form id="formTolak" method="POST">
                @csrf
                <input type="hidden" name="aksi" value="tolak">

                <label style="display:block; font-weight:600; margin-bottom:8px; font-size:13.5px; color:#374151;">
                    Alasan Penolakan <span style="color:#dc2626;">*</span>
                </label>
                <textarea name="catatan" rows="4" required
                          placeholder="Contoh: Data KTP tidak sesuai dengan KK, mohon perbaiki lalu ajukan ulang."
                          style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-family:inherit; font-size:13.5px; resize:vertical; color:#111827;"
                          onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)'"
                          onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"></textarea>
                <p style="font-size:12px; color:#6b7280; margin-top:8px; line-height:1.5;">
                    💡 Alasan ini akan ditampilkan ke warga supaya mereka tahu kenapa suratnya ditolak.
                </p>

                <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                    <button type="button" onclick="tutupModalTolak()"
                            style="padding:11px 22px; border:1.5px solid #e5e7eb; border-radius:10px; background:white; cursor:pointer; font-size:14px; font-weight:600; color:#374151; font-family:inherit;">
                        Batal
                    </button>
                    <button type="submit"
                            style="padding:11px 22px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border:none; border-radius:10px; cursor:pointer; font-weight:700; font-size:14px; box-shadow:0 4px 14px rgba(239,68,68,0.3); font-family:inherit;">
                        ✕ Tolak Surat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModalTolak(url, nomor, warga) {
            document.getElementById('formTolak').action = url;
            document.getElementById('modalNomor').textContent = nomor;
            document.getElementById('modalWarga').textContent = warga;
            document.getElementById('modalTolak').style.display = 'flex';
        }
        function tutupModalTolak() {
            document.getElementById('modalTolak').style.display = 'none';
        }
        document.getElementById('modalTolak').addEventListener('click', function (e) {
            if (e.target === this) tutupModalTolak();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') tutupModalTolak();
        });
    </script>
</x-app-layout>