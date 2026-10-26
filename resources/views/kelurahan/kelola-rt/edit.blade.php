<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:14px;">
            <a href="{{ route('kelurahan.kelola-rt.index') }}"
               style="width:44px; height:44px; background:white; border:1.5px solid #e5e7eb; border-radius:12px; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6b7280; font-size:20px;">
                ←
            </a>
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                    ✏️
                </div>
                <div>
                    <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                        Edit Pengurus RT
                    </h2>
                    <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                        Perbarui data akun pengurus RT
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1100px; margin:0 auto; padding:0 20px;">

            <div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

                {{-- ===== FORM KIRI ===== --}}
                <form action="{{ route('kelurahan.kelola-rt.update', $user->id) }}" method="POST"
                      style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">
                    @csrf
                    @method('PUT')

                    {{-- Header Form --}}
                    <div style="padding:20px 24px; background:linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%); border-bottom:1px solid #c7d2fe; display:flex; align-items:center; gap:14px;">
                        <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                            ✏️
                        </div>
                        <div>
                            <h3 style="font-size:15px; font-weight:700; color:#1e3a8a; margin:0;">
                                Edit Data Pengurus RT
                            </h3>
                            <p style="font-size:12px; color:#3b82f6; margin:3px 0 0 0;">
                                Kosongkan password jika tidak ingin diubah
                            </p>
                        </div>
                    </div>

                    {{-- Body Form --}}
                    <div style="padding:24px; display:flex; flex-direction:column; gap:18px;">

                        {{-- Nama --}}
                        <div>
                            <label for="name" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                👤 Nama Lengkap <span style="color:#dc2626;">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                   style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; transition:0.15s; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)'"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                            @error('name') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                        </div>

                        {{-- NIK & Email --}}
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <label for="nik" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                    🆔 NIK <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="nik" id="nik" value="{{ old('nik', $user->nik) }}" required maxlength="16"
                                       style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; transition:0.15s; box-sizing:border-box;"
                                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)'"
                                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                @error('nik') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                    📧 Email <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                       style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; transition:0.15s; box-sizing:border-box;"
                                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)'"
                                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                @error('email') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                🔒 Password
                                <span style="font-size:11.5px; color:#6b7280; font-weight:500;">(kosongkan jika tidak diubah)</span>
                            </label>
                            <input type="password" name="password" id="password"
                                   placeholder="••••••••"
                                   style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; transition:0.15s; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)'"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                            @error('password') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                        </div>

                        {{-- RT & RW --}}
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <label for="rt" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                    🏠 Nomor RT <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="rt" id="rt" value="{{ old('rt', $user->rt) }}" required
                                       style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; transition:0.15s; box-sizing:border-box;"
                                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)'"
                                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                @error('rt') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="rw" style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                    🏢 Nomor RW <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="rw" id="rw" value="{{ old('rw', $user->rw) }}" required
                                       style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; transition:0.15s; box-sizing:border-box;"
                                       onfocus="this.style.borderColor='#2563eb'; this.style.boxShadow='0 0 0 4px rgba(37,99,235,0.1)'"
                                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                @error('rw') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Footer Form --}}
                    <div style="padding:18px 24px; background:#fafbfc; border-top:1px solid #f3f4f6; display:flex; justify-content:flex-end; gap:12px;">
                        <a href="{{ route('kelurahan.kelola-rt.index') }}"
                           style="padding:11px 24px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600;">
                            Batal
                        </a>
                        <button type="submit"
                                style="padding:11px 28px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(37,99,235,0.3); display:inline-flex; align-items:center; gap:8px;">
                            ✓ Update Akun
                        </button>
                    </div>
                </form>

                {{-- ===== PANEL KANAN ===== --}}
                <div style="display:flex; flex-direction:column; gap:16px; position:sticky; top:20px;">

                    {{-- Kartu Info User --}}
                    <div style="background:linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border:1.5px solid #e9d5ff; border-radius:16px; padding:24px; text-align:center;">
                        <div style="width:72px; height:72px; margin:0 auto; border-radius:50%; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:700; box-shadow:0 8px 20px rgba(124,58,237,0.3);">
                            {{ $user->inisial() }}
                        </div>
                        <p style="font-size:15px; font-weight:700; color:#111827; margin:14px 0 4px 0;">
                            {{ $user->name }}
                        </p>
                        <p style="font-size:12px; color:#6b7280; margin:0 0 12px 0;">
                            {{ $user->email }}
                        </p>
                        <div style="display:flex; align-items:center; justify-content:center; gap:6px; flex-wrap:wrap;">
                            <span style="padding:4px 10px; background:#dbeafe; color:#1e40af; border-radius:6px; font-size:11px; font-weight:700;">
                                RT {{ $user->rt }} / RW {{ $user->rw }}
                            </span>
                          <span style="padding:4px 10px; background:linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color:white; border-radius:6px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; box-shadow:0 2px 6px rgba(124,58,237,0.35);">
    {{ $user->role }}
</span>
                        </div>
                    </div>

                    {{-- Warning --}}
                    <div style="background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border:1.5px solid #fcd34d; border-radius:16px; padding:22px;">
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                            <div style="width:40px; height:40px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                                ⚠️
                            </div>
                            <h4 style="font-size:14px; font-weight:700; color:#78350f; margin:0;">Perhatian</h4>
                        </div>
                        <p style="font-size:12.5px; color:#92400e; line-height:1.6; margin:0;">
                            Perubahan data akan langsung tersimpan. Pastikan NIK dan email tidak sama dengan user lain agar tidak error.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>