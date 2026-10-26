<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    👥 Data Warga RT {{ auth()->user()->rt }}
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Kelola data warga yang terdaftar di wilayah RT Anda
                </p>
            </div>
            <a href="{{ route('rt.data-warga.create') }}"
               style="padding:11px 22px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); display:inline-flex; align-items:center; gap:8px;">
                <span style="font-size:18px;">+</span>
                Tambah Warga
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1280px; margin:0 auto; padding:0 20px;">

            @if (session('success'))
                <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-left:4px solid #10b981; color:#065f46; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:24px; height:24px; background:#10b981; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">✓</div>
                    <div style="font-size:14px;">{{ session('success') }}</div>
                </div>
            @endif

            {{-- 🔑 NOTIFIKASI PASSWORD BARU (Setelah Reset) --}}
            @if (session('password_baru'))
                <div style="background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:2px solid #60a5fa; border-left:5px solid #2563eb; padding:20px 24px; border-radius:14px; margin-bottom:20px; box-shadow:0 8px 24px rgba(37,99,235,0.15);">
                    <div style="display:flex; align-items:flex-start; gap:14px;">
                        <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">🔑</div>
                        <div style="flex:1;">
                            <div style="font-weight:700; color:#1e40af; font-size:15px; margin-bottom:8px;">
                                Password Baru untuk {{ session('warga_nama') }}
                            </div>
                            <div style="display:flex; align-items:center; gap:10px; background:white; padding:14px 18px; border-radius:10px; margin-bottom:10px; border:1.5px dashed #93c5fd;">
                                <code id="passwordText" style="font-family:monospace; font-size:20px; font-weight:700; color:#1e40af; letter-spacing:1px; flex:1;">{{ session('password_baru') }}</code>
                                <button type="button" onclick="copyPassword()"
                                        style="padding:8px 16px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border:none; border-radius:8px; font-size:12.5px; font-weight:700; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:6px;">
                                    📋 Salin
                                </button>
                            </div>
                            <div style="font-size:12.5px; color:#1e40af; line-height:1.6;">
                                <strong>⚠️ Password ini hanya muncul SEKALI.</strong> Segera catat atau sampaikan ke warga <strong>{{ session('warga_nama') }}</strong>.
                                Minta warga segera ganti password setelah login pertama kali.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; color:#991b1b; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:24px; height:24px; background:#dc2626; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">!</div>
                    <div style="font-size:14px;">{{ session('error') }}</div>
                </div>
            @endif

          {{-- STATISTIK (CLICKABLE) --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin-bottom:24px;">

    {{-- TOTAL WARGA --}}
    <a href="{{ route('rt.data-warga.index') }}"
       style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(102,126,234,0.3); text-decoration:none; display:block; position:relative; overflow:hidden; transition:0.2s;"
       onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(102,126,234,0.45)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(102,126,234,0.3)'">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
            <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">👥 Total Warga</div>
            <div style="font-size:14px; opacity:0.7;">→</div>
        </div>
        <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $statistik['total'] }}</div>
        <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Terdaftar di RT {{ auth()->user()->rt }}</div>
    </a>

    {{-- BELUM DAFTAR --}}
    <a href="{{ route('rt.data-warga.index', ['status' => 'belum_daftar']) }}"
       style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(245,158,11,0.3); text-decoration:none; display:block; position:relative; overflow:hidden; transition:0.2s;"
       onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(245,158,11,0.45)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(245,158,11,0.3)'">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
            <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">⏳ Belum Daftar</div>
            <div style="font-size:14px; opacity:0.7;">→</div>
        </div>
        <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $statistik['belum_daftar'] }}</div>
        <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Belum membuat akun</div>
    </a>

    {{-- SUDAH DAFTAR --}}
    <a href="{{ route('rt.data-warga.index', ['status' => 'sudah_daftar']) }}"
       style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(16,185,129,0.3); text-decoration:none; display:block; position:relative; overflow:hidden; transition:0.2s;"
       onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 28px rgba(16,185,129,0.45)'"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(16,185,129,0.3)'">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;">
            <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✓ Sudah Daftar</div>
            <div style="font-size:14px; opacity:0.7;">→</div>
        </div>
        <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1; position:relative; z-index:1;">{{ $statistik['sudah_daftar'] }}</div>
        <div style="font-size:11px; opacity:0.85; margin-top:4px; position:relative; z-index:1;">Punya akun pelayanan</div>
    </a>
</div>
            {{-- FILTER --}}
            <form method="GET" style="background:white; padding:16px; border-radius:14px; box-shadow:0 4px 20px rgba(0,0,0,0.06); margin-bottom:20px;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:10px; align-items:end;">
                    <div style="min-width:220px;">
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Cari</label>
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="🔍 Nama / NIK / No. HP..."
                               style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Status</label>
                        <select name="status" style="width:100%; padding:11px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; background:white; font-family:inherit;">
                            <option value="">-- Semua Status --</option>
                            <option value="belum_daftar" @selected(request('status') === 'belum_daftar')>⏳ Belum Daftar</option>
                            <option value="sudah_daftar" @selected(request('status') === 'sudah_daftar')>✓ Sudah Daftar</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button type="submit"
                                style="flex:1; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; padding:11px 20px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit;">
                            🔍 Filter
                        </button>
                        <a href="{{ route('rt.data-warga.index') }}"
                           style="padding:11px 20px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; background:white; white-space:nowrap;">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- TABEL WARGA --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                <div style="padding:18px 22px; border-bottom:1px solid #f3f4f6;">
                    <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0;">👥 Daftar Warga</h3>
                    <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">{{ $data->total() }} warga ditampilkan</p>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%; font-size:14px; text-align:left; border-collapse:collapse;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">NIK</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Nama Lengkap</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Jenis Kelamin</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">TTL</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">No. HP</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Status</th>
                                <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px; text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                @php
                                    $statusCfg = $item->status === 'sudah_daftar'
                                        ? ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => '✓ Sudah Daftar']
                                        : ['bg' => '#fef3c7', 'color' => '#92400e', 'label' => '⏳ Belum Daftar'];
                                @endphp
                                <tr style="border-bottom:1px solid #f3f4f6; transition:0.15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="font-family:monospace; font-size:12px; font-weight:700; color:#1e40af; background:#eff6ff; padding:4px 8px; border-radius:6px; display:inline-block;">
                                            {{ $item->nik }}
                                        </div>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="font-weight:600; color:#111827;">{{ $item->nama }}</div>
                                        <div style="font-size:11.5px; color:#9ca3af; margin-top:2px;">{{ Str::limit($item->alamat, 40) }}</div>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <span style="padding:4px 10px; border-radius:8px; font-size:11.5px; font-weight:600; background:{{ $item->jenis_kelamin === 'L' ? '#dbeafe' : '#fce7f3' }}; color:{{ $item->jenis_kelamin === 'L' ? '#1e40af' : '#9d174d' }};">
                                            {{ $item->jenis_kelamin === 'L' ? '👨 Laki-laki' : '👩 Perempuan' }}
                                        </span>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <div style="font-size:12.5px; color:#374151;">{{ $item->tempat_lahir }}</div>
                                        <div style="font-size:11.5px; color:#9ca3af; margin-top:2px;">{{ $item->tanggalLahirFormat() }}</div>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        @if ($item->no_hp)
                                            <div style="font-size:12.5px; color:#374151; font-family:monospace;">{{ $item->no_hp }}</div>
                                        @else
                                            <span style="color:#d1d5db; font-size:12px;">—</span>
                                        @endif
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle;">
                                        <span style="padding:5px 12px; border-radius:8px; font-size:11px; font-weight:700; background:{{ $statusCfg['bg'] }}; color:{{ $statusCfg['color'] }}; white-space:nowrap;">
                                            {{ $statusCfg['label'] }}
                                        </span>
                                    </td>
                                    <td style="padding:16px 20px; vertical-align:middle; text-align:right;">
                                        <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                            <a href="{{ route('rt.data-warga.show', $item) }}"
                                               style="padding:6px 12px; background:#f3f4f6; color:#374151; border-radius:6px; font-size:11.5px; text-decoration:none; font-weight:600;">
                                                Detail
                                            </a>
                                            <a href="{{ route('rt.data-warga.edit', $item) }}"
                                               style="padding:6px 12px; background:#eff6ff; color:#1e40af; border-radius:6px; font-size:11.5px; text-decoration:none; font-weight:600;">
                                                ✏️ Edit
                                            </a>
                                            @if ($item->status === 'sudah_daftar')
                                                <button type="button"
                                                        onclick="openResetModal({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->nik }}')"
                                                        style="padding:6px 12px; background:#fef3c7; color:#92400e; border:1px solid #fcd34d; border-radius:6px; font-size:11.5px; font-weight:600; cursor:pointer; font-family:inherit;">
                                                    🔑 Reset
                                                </button>
                                            @else
                                                <form method="POST" action="{{ route('rt.data-warga.destroy', $item) }}"
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus data {{ $item->nama }}? Tindakan ini tidak bisa dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            style="padding:6px 12px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:6px; font-size:11.5px; font-weight:600; cursor:pointer; font-family:inherit;">
                                                        🗑️
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:60px 20px; text-align:center;">
                                        <div style="font-size:64px; margin-bottom:12px;">👥</div>
                                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                                            @if (request('cari') || request('status'))
                                                Tidak Ada Hasil Ditemukan
                                            @else
                                                Belum Ada Data Warga
                                            @endif
                                        </div>
                                        <div style="font-size:13px; color:#6b7280; margin-bottom:20px; max-width:400px; margin-left:auto; margin-right:auto; line-height:1.6;">
                                            @if (request('cari') || request('status'))
                                                Coba ubah filter atau reset untuk melihat semua data warga.
                                            @else
                                                Mulai tambahkan data warga RT Anda. Setelah itu, warga bisa cek NIK di beranda dan daftar akun.
                                            @endif
                                        </div>
                                        @if (request('cari') || request('status'))
                                            <a href="{{ route('rt.data-warga.index') }}"
                                               style="padding:11px 24px; background:#f3f4f6; color:#374151; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600;">
                                                🔄 Reset Filter
                                            </a>
                                        @else
                                            <a href="{{ route('rt.data-warga.create') }}"
                                               style="padding:12px 26px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                                                + Tambah Warga Sekarang
                                            </a>
                                        @endif
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

    {{-- ============ MODAL RESET PASSWORD ============ --}}
    <div id="resetModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:white; border-radius:16px; max-width:480px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; animation:fadeInUp 0.3s ease-out;">

            {{-- Header Modal --}}
            <div style="padding:20px 24px; background:linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-bottom:1px solid #fcd34d; display:flex; align-items:center; gap:14px;">
                <div style="width:44px; height:44px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">🔑</div>
                <div style="flex:1;">
                    <h3 style="font-size:16px; font-weight:700; color:#78350f; margin:0;">Reset Password Warga</h3>
                    <p style="font-size:12px; color:#92400e; margin:2px 0 0 0;">Password akan diganti dengan yang baru</p>
                </div>
                <button type="button" onclick="closeResetModal()"
                        style="width:32px; height:32px; background:white; border:none; border-radius:8px; cursor:pointer; font-size:18px; color:#92400e; display:flex; align-items:center; justify-content:center;">×</button>
            </div>

            <form id="resetForm" method="POST" style="padding:24px;">
                @csrf

                {{-- Info Warga --}}
                <div style="background:#fafbfc; border:1.5px solid #e5e7eb; border-radius:12px; padding:14px 18px; margin-bottom:20px;">
                    <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">Warga yang akan direset</div>
                    <div style="font-size:15px; font-weight:700; color:#111827;" id="modalNamaWarga">—</div>
                    <div style="font-size:12px; color:#6b7280; font-family:monospace; margin-top:3px;" id="modalNikWarga">—</div>
                </div>

                {{-- Input Password Baru --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">
                        Password Baru
                        <span style="font-size:11.5px; color:#6b7280; font-weight:500;">(min. 8 karakter)</span>
                    </label>
                    <div style="display:flex; gap:8px;">
                        <input type="text" name="password_baru" id="inputPasswordBaru"
                               placeholder="Ketik atau klik Generate"
                               style="flex:1; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:monospace; outline:none;">
                        <button type="button" onclick="generatePassword()"
                                style="padding:12px 16px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border:none; border-radius:10px; font-size:12.5px; font-weight:700; cursor:pointer; font-family:inherit; white-space:nowrap;">
                            🎲 Generate
                        </button>
                    </div>
                    <div style="font-size:11.5px; color:#6b7280; margin-top:8px; line-height:1.5;">
                        💡 Klik <strong>"Generate"</strong> untuk membuat password otomatis, atau ketik manual.
                    </div>
                </div>

                {{-- Peringatan --}}
                <div style="background:#fffbeb; border:1px solid #fcd34d; border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:12.5px; color:#78350f; line-height:1.6;">
                    <strong>⚠️ Perhatian:</strong> Password baru akan ditampilkan <strong>sekali saja</strong> setelah disimpan. Segera catat dan sampaikan ke warga.
                </div>

                {{-- Tombol --}}
                <div style="display:flex; gap:10px; justify-content:flex-end;">
                    <button type="button" onclick="closeResetModal()"
                            style="padding:11px 22px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit;">
                        Batal
                    </button>
                    <button type="submit"
                            style="padding:11px 24px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; box-shadow:0 4px 14px rgba(245,158,11,0.3); display:inline-flex; align-items:center; gap:6px;">
                        🔑 Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        function openResetModal(id, nama, nik) {
            const modal = document.getElementById('resetModal');
            const form = document.getElementById('resetForm');

            // Set form action
            form.action = '/rt/data-warga/' + id + '/reset-password';

            // Set info warga
            document.getElementById('modalNamaWarga').textContent = nama;
            document.getElementById('modalNikWarga').textContent = 'NIK: ' + nik;

            // Reset input
            document.getElementById('inputPasswordBaru').value = '';

            // Tampilkan modal
            modal.style.display = 'flex';
        }

        function closeResetModal() {
            document.getElementById('resetModal').style.display = 'none';
        }

        function generatePassword() {
            const input = document.getElementById('inputPasswordBaru');
            const namaWarga = document.getElementById('modalNamaWarga').textContent.trim();
            const kataPertama = namaWarga.split(' ')[0].replace(/[^A-Za-z]/g, '');
            const tahun = new Date().getFullYear();
            const randomNum = Math.floor(Math.random() * 90) + 10;

            input.value = kataPertama + '@' + tahun + randomNum;
            input.select();
        }

        function copyPassword() {
            const text = document.getElementById('passwordText').textContent;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    alert('✅ Password berhasil disalin!');
                });
            } else {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); alert('✅ Password berhasil disalin!'); }
                catch(e) { alert('Gagal menyalin, silakan copy manual.'); }
                document.body.removeChild(ta);
            }
        }

        document.getElementById('resetModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeResetModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeResetModal();
        });
    </script>
</x-app-layout>