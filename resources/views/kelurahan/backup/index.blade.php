<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(16,185,129,0.3);">
                    💾
                </div>
                <div>
                    <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                        Backup Database
                    </h2>
                    <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                        Kelola backup data sistem pelayanan surat
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('kelurahan.backup.create') }}" onsubmit="return confirmBackup(this);">
                @csrf
                <button type="submit"
                        style="padding:11px 22px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; box-shadow:0 4px 14px rgba(37,99,235,0.3); display:inline-flex; align-items:center; gap:8px; border:none; cursor:pointer; font-family:inherit;">
                    <span style="font-size:16px;">+</span>
                    Buat Backup Baru
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1100px; margin:0 auto; padding:0 20px;">

            {{-- FLASH MESSAGES --}}
            @if (session('success'))
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1px solid #6ee7b7; border-left:4px solid #10b981; color:#065f46; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:24px; height:24px; background:#10b981; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">✓</div>
                    <div style="font-size:14px;">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div style="background:linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border:1px solid #fecaca; border-left:4px solid #dc2626; color:#991b1b; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px;">
                    <div style="flex-shrink:0; width:24px; height:24px; background:#dc2626; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">!</div>
                    <div style="font-size:14px;">{{ session('error') }}</div>
                </div>
            @endif

            {{-- INFO BOX --}}
            <div style="background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:1.5px solid #93c5fd; border-left:5px solid #2563eb; border-radius:14px; padding:16px 22px; margin-bottom:20px; display:flex; align-items:flex-start; gap:14px;">
                <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(37,99,235,0.3);">💡</div>
                <div style="flex:1;">
                    <div style="font-weight:700; font-size:14px; color:#1e40af; margin-bottom:4px;">
                        Tentang Backup Database
                    </div>
                    <div style="font-size:12.5px; color:#1e40af; line-height:1.6;">
                        Backup berisi seluruh data (user, surat, warga, pengumuman, log). <strong>Disarankan backup rutin</strong> minimal 1x seminggu atau sebelum update sistem. File backup disimpan di server dan bisa diunduh kapan saja.
                    </div>
                </div>
            </div>

            {{-- STATISTIK --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:24px;">
                <div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(102,126,234,0.3);">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">📦 Total Backup</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">{{ $files->count() }}</div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">File tersimpan</div>
                </div>
                <div style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:14px; padding:20px; box-shadow:0 4px 14px rgba(16,185,129,0.3);">
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">💾 Total Ukuran</div>
                    <div style="font-size:32px; font-weight:800; margin-top:6px; line-height:1;">
                        {{ $files->sum(function($f){ return $f['size']; }) > 0 ? number_format($files->sum(function($f){ return $f['size']; }) / 1024 / 1024, 2) : '0' }}
                        <span style="font-size:16px; font-weight:600;">MB</span>
                    </div>
                    <div style="font-size:11px; opacity:0.85; margin-top:4px;">Total penyimpanan</div>
                </div>
            </div>

            {{-- LIST BACKUP --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0;">📋 Daftar Backup</h3>
                        <p style="font-size:12px; color:#6b7280; margin:3px 0 0 0;">Klik "Download" untuk menyimpan salinan ke komputer</p>
                    </div>
                </div>

                @if ($files->count() > 0)
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse; text-align:left;">
                            <thead style="background:#f9fafb;">
                                <tr>
                                    <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Nama File</th>
                                    <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Ukuran</th>
                                    <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px;">Tanggal</th>
                                    <th style="padding:14px 20px; font-size:11px; text-transform:uppercase; color:#6b7280; font-weight:700; letter-spacing:0.5px; text-align:right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($files as $file)
                                    <tr style="border-bottom:1px solid #f3f4f6; transition:0.15s;" onmouseover="this.style.background='#fafbfc'" onmouseout="this.style.background='white'">
                                        <td style="padding:16px 20px;">
                                            <div style="display:flex; align-items:center; gap:12px;">
                                                <div style="flex-shrink:0; width:40px; height:40px; background:linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px;">
                                                    🗄️
                                                </div>
                                                <div style="font-family:monospace; font-size:12.5px; font-weight:600; color:#1e40af; word-break:break-all;">
                                                    {{ $file['name'] }}
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:16px 20px;">
                                            <span style="padding:4px 10px; background:#f3f4f6; color:#374151; border-radius:6px; font-size:11.5px; font-weight:700;">
                                                {{ $file['size_human'] }}
                                            </span>
                                        </td>
                                        <td style="padding:16px 20px;">
                                            <div style="font-size:13px; font-weight:600; color:#111827;">
                                                {{ $file['created_at']->translatedFormat('d M Y') }}
                                            </div>
                                            <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                                                {{ $file['created_at']->format('H:i') }} WIB
                                                • {{ $file['created_at']->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td style="padding:16px 20px; text-align:right;">
                                            <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                                <a href="{{ route('kelurahan.backup.download', $file['name']) }}"
                                                   style="padding:8px 14px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:8px; font-size:12px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px; box-shadow:0 3px 10px rgba(16,185,129,0.25);">
                                                    📥 Download
                                                </a>
                                                <form method="POST" action="{{ route('kelurahan.backup.destroy', $file['name']) }}"
                                                      style="display:inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus backup ini?\n\nTindakan ini tidak bisa dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            style="padding:8px 14px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit;">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="padding:70px 20px; text-align:center;">
                        <div style="font-size:64px; margin-bottom:14px;">💾</div>
                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                            Belum Ada Backup
                        </div>
                        <div style="font-size:13px; color:#6b7280; margin-bottom:22px; max-width:400px; margin-left:auto; margin-right:auto;">
                            Klik tombol <strong>"Buat Backup Baru"</strong> di atas untuk membuat backup pertama Anda.
                        </div>
                    </div>
                @endif
            </div>

            {{-- INFO CARA RESTORE --}}
            <div style="margin-top:24px; background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border:1.5px solid #fcd34d; border-radius:14px; padding:18px 22px;">
                <div style="display:flex; align-items:flex-start; gap:12px;">
                    <div style="font-size:22px; flex-shrink:0;">📖</div>
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:14px; color:#78350f; margin-bottom:8px;">
                            Cara Restore Backup
                        </div>
                        <ol style="margin:0; padding-left:20px; font-size:12.5px; color:#92400e; line-height:1.8;">
                            <li>Download file backup dari daftar di atas</li>
                            <li>Buka <strong>phpMyAdmin</strong> (Laragon → Database)</li>
                            <li>Pilih database <strong>surat_domisili</strong></li>
                            <li>Klik tab <strong>Import</strong> → pilih file backup <code>.sql</code></li>
                            <li>Klik <strong>Go</strong> / <strong>Kirim</strong> → tunggu proses selesai</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function confirmBackup(form) {
            return confirm('Buat backup database sekarang?\n\nProses ini memakan waktu beberapa detik.');
        }
    </script>
</x-app-layout>