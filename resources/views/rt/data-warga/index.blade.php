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

            @if (session('error'))
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; color:#991b1b; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:24px; height:24px; background:#dc2626; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">!</div>
                    <div style="font-size:14px;">{{ session('error') }}</div>
                </div>
            @endif

            {{-- STATISTIK --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin-bottom:24px;">
                <div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(102,126,234,0.3);">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">👥 Total Warga</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $statistik['total'] }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Terdaftar di RT {{ auth()->user()->rt }}</div>
                </div>
                <div style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(245,158,11,0.3);">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">⏳ Belum Daftar</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $statistik['belum_daftar'] }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Belum membuat akun</div>
                </div>
                <div style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(16,185,129,0.3);">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">✓ Sudah Daftar</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $statistik['sudah_daftar'] }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Punya akun pelayanan</div>
                </div>
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
                                        <div style="display:flex; gap:6px; justify-content:flex-end;">
                                            <a href="{{ route('rt.data-warga.show', $item) }}"
                                               style="padding:6px 12px; background:#f3f4f6; color:#374151; border-radius:6px; font-size:11.5px; text-decoration:none; font-weight:600;">
                                                Detail
                                            </a>
                                            <a href="{{ route('rt.data-warga.edit', $item) }}"
                                               style="padding:6px 12px; background:#eff6ff; color:#1e40af; border-radius:6px; font-size:11.5px; text-decoration:none; font-weight:600;">
                                                ✏️ Edit
                                            </a>
                                            @if ($item->status === 'belum_daftar')
                                                <form method="POST" action="{{ route('rt.data-warga.destroy', $item) }}"
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus data {{ $item->nama }}? Tindakan ini tidak bisa dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            style="padding:6px 12px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:6px; font-size:11.5px; text-decoration:none; font-weight:600; cursor:pointer; font-family:inherit;">
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
</x-app-layout>