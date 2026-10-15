<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    ➕ Tambah Data Warga
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Input data warga RT {{ auth()->user()->rt }} secara manual
                </p>
            </div>
            <a href="{{ route('rt.data-warga.index') }}"
               style="padding:10px 18px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:13px; font-weight:600; background:white;">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:760px; margin:0 auto; padding:0 20px;">

            @if ($errors->any())
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; align-items:flex-start; gap:12px;">
                    <div style="flex-shrink:0; width:28px; height:28px; background:#dc2626; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">!</div>
                    <div style="flex:1;">
                        <div style="font-weight:700; color:#991b1b; font-size:14px; margin-bottom:6px;">Ada kesalahan pada form</div>
                        <ul style="margin:0; padding-left:18px; font-size:13px; color:#7f1d1d; line-height:1.7;">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; align-items:flex-start; gap:12px;">
                <div style="font-size:20px; flex-shrink:0;">💡</div>
                <div style="font-size:13px; color:#1e40af; line-height:1.6;">
                    <strong>Info:</strong> Setelah Anda input data warga di sini, warga tersebut bisa <strong>cek NIK di halaman beranda</strong> dan melanjutkan pendaftaran akun pelayanan.
                </div>
            </div>

            <form method="POST" action="{{ route('rt.data-warga.store') }}"
                  style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:28px;">
                @csrf

                {{-- DATA PRIBADI --}}
                <div style="padding-bottom:16px; margin-bottom:20px; border-bottom:2px solid #f3f4f6;">
                    <h3 style="font-size:14px; font-weight:700; color:#374151; margin:0 0 4px 0;">👤 Data Pribadi</h3>
                    <p style="font-size:12px; color:#9ca3af; margin:0;">Sesuaikan dengan KTP warga</p>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                        NIK (16 digit) <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" name="nik" value="{{ old('nik') }}"
                           placeholder="3201010101010001"
                           maxlength="16" pattern="[0-9]{16}" required
                           style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:monospace; letter-spacing:1px; color:#111827; background:white;">
                    <div style="font-size:11px; color:#9ca3af; margin-top:6px;">
                        NIK akan digunakan warga untuk cek & daftar di beranda
                    </div>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                        Nama Lengkap <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                           placeholder="Sesuai KTP"
                           required
                           style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:white;">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                            Jenis Kelamin <span style="color:#dc2626;">*</span>
                        </label>
                        <select name="jenis_kelamin" required
                                style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; background:white; font-family:inherit;">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                            No. HP <span style="font-size:11px; color:#9ca3af; font-weight:400;">(opsional)</span>
                        </label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                               placeholder="081234567890"
                               style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:white;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                            Tempat Lahir <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                               placeholder="Contoh: Jambi"
                               required
                               style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:white;">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                            Tanggal Lahir <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               required
                               style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:white;">
                    </div>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                        Alamat Lengkap <span style="color:#dc2626;">*</span>
                    </label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}"
                           placeholder="Jl. Mawar No. 10"
                           required
                           style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:white;">
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                        Email <span style="font-size:11px; color:#9ca3af; font-weight:400;">(opsional)</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="warga@email.com"
                           style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; color:#111827; background:white;">
                </div>

                {{-- INFO WILAYAH --}}
                <div style="padding:14px 18px; background:#f9fafb; border:1px dashed #d1d5db; border-radius:10px; margin-bottom:24px;">
                    <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; margin-bottom:4px;">Wilayah</div>
                    <div style="font-size:13.5px; color:#374151; font-weight:600;">
                        RT {{ auth()->user()->rt }} / RW {{ auth()->user()->rw }}
                    </div>
                    <div style="font-size:11.5px; color:#9ca3af; margin-top:2px;">Otomatis diisi dari profil Anda</div>
                </div>

                {{-- TOMBOL --}}
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button type="submit"
                            style="flex:1; min-width:180px; padding:14px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                        💾 Simpan Data Warga
                    </button>
                    <a href="{{ route('rt.data-warga.index') }}"
                       style="padding:14px 24px; background:white; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; display:inline-flex; align-items:center;">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>