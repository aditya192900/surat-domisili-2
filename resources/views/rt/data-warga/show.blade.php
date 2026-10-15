<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    👤 Detail Warga
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    {{ $dataWarga->nama }}
                </p>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('rt.data-warga.edit', $dataWarga) }}"
                   style="padding:10px 18px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
                    ✏️ Edit
                </a>
                <a href="{{ route('rt.data-warga.index') }}"
                   style="padding:10px 18px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:13px; font-weight:600; background:white;">
                    ← Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:760px; margin:0 auto; padding:0 20px;">

            @if ($dataWarga->status === 'sudah_daftar')
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius:16px; padding:24px; margin-bottom:20px; border:1.5px solid #a7f3d0; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                        ✓
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:15px; color:#065f46;">Warga Sudah Mendaftar Akun</div>
                        <div style="font-size:12.5px; color:#047857; margin-top:3px;">
                            {{ $dataWarga->user ? 'Akun: ' . $dataWarga->user->email : 'Akun pelayanan sudah aktif' }}
                        </div>
                    </div>
                </div>
            @else
                <div style="background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius:16px; padding:24px; margin-bottom:20px; border:1.5px solid #fcd34d; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                        ⏳
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:15px; color:#78350f;">Menunggu Pendaftaran Akun</div>
                        <div style="font-size:12.5px; color:#92400e; margin-top:3px;">
                            Warga belum membuat akun pelayanan. Minta warga cek NIK di beranda.
                        </div>
                    </div>
                </div>
            @endif

            {{-- INFO DATA --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                    <div style="font-size:20px;">📋</div>
                    <h3 style="font-size:16px; font-weight:700; color:#111827; margin:0;">Data Warga</h3>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">🆔 NIK</div>
                        <div style="font-size:15px; font-weight:700; color:#111827; font-family:monospace; letter-spacing:1px;">{{ $dataWarga->nik }}</div>
                    </div>

                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">👤 Nama Lengkap</div>
                        <div style="font-size:15px; font-weight:700; color:#111827;">{{ $dataWarga->nama }}</div>
                    </div>

                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">⚧ Jenis Kelamin</div>
                        <div style="font-size:15px; font-weight:600; color:#111827;">{{ $dataWarga->jenisKelaminLabel() }}</div>
                    </div>

                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">🎂 Umur</div>
                        <div style="font-size:15px; font-weight:600; color:#111827;">{{ $dataWarga->umur() }} tahun</div>
                    </div>

                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">🏠 Tempat & Tgl Lahir</div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">
                            {{ $dataWarga->tempat_lahir }},<br>
                            <span style="font-weight:500;">{{ $dataWarga->tanggalLahirFormat() }}</span>
                        </div>
                    </div>

                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">📍 Alamat</div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">{{ $dataWarga->alamat }}</div>
                    </div>

                    @if ($dataWarga->no_hp)
                        <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                            <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">📱 No. HP</div>
                            <div style="font-size:14px; font-weight:600; color:#111827; font-family:monospace;">{{ $dataWarga->no_hp }}</div>
                        </div>
                    @endif

                    @if ($dataWarga->email)
                        <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                            <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">📧 Email</div>
                            <div style="font-size:14px; font-weight:600; color:#111827;">{{ $dataWarga->email }}</div>
                        </div>
                    @endif

                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">🏘️ RT / RW</div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">{{ $dataWarga->rt }} / {{ $dataWarga->rw }}</div>
                    </div>

                    <div style="padding:14px 0; border-bottom:1px solid #f3f4f6;">
                        <div style="font-size:11px; color:#6b7280; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:6px;">📅 Terdaftar</div>
                        <div style="font-size:14px; font-weight:600; color:#111827;">{{ $dataWarga->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL AKSI BAWAH --}}
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('rt.data-warga.edit', $dataWarga) }}"
                   style="flex:1; min-width:180px; padding:14px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; text-align:center; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                    ✏️ Edit Data
                </a>

                @if ($dataWarga->status === 'belum_daftar')
                    <form method="POST" action="{{ route('rt.data-warga.destroy', $dataWarga) }}"
                          style="flex:1; min-width:180px;"
                          onsubmit="return confirm('Yakin ingin menghapus data {{ $dataWarga->nama }}? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="width:100%; padding:14px; background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; font-family:inherit;">
                            🗑️ Hapus Data
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>