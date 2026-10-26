<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-600">Perbarui informasi profil, foto, dan data diri Anda.</p>
    </header>

    {{-- FOTO PROFIL --}}
    <div class="mt-6" style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
        <div>
            @if ($user->foto)
                <img src="{{ route('profile.foto', $user) }}" alt="Foto Profil"
                     style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid #e5e7eb;">
            @else
                <div style="width:100px; height:100px; border-radius:50%; background:#2563eb; color:white;
                            display:flex; align-items:center; justify-content:center; font-size:32px;
                            font-weight:bold; border:3px solid #e5e7eb;">
                    {{ $user->inisial() }}
                </div>
            @endif
        </div>
        <div>
            <div style="font-weight:600; margin-bottom:4px;">{{ $user->name }}</div>
            <div style="font-size:13px; color:#6b7280;">
                <span style="padding:2px 8px; background:#e5e7eb; border-radius:4px; text-transform:uppercase; font-size:11px;">
                    {{ $user->role }}
                </span>
            </div>
            @if ($user->foto)
                <form method="POST" action="{{ route('profile.foto.hapus') }}" style="margin-top:8px;"
                      onsubmit="return confirm('Yakin hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:12px; padding:0; text-decoration:underline;">
                        Hapus foto
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- FORM UTAMA --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="foto" :value="__('Foto Profil')" />
            <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png"
                   class="mt-1 block w-full" style="padding:8px; border:1px solid #ccc; border-radius:6px;">
            <p style="font-size:12px; color:#6b7280; margin-top:4px;">Format JPG/PNG, maks 2MB</p>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          :value="old('name', $user->name)" required />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- EMAIL (READONLY - HANYA BISA DIUBAH VIA RT) --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div style="position:relative; margin-top:4px;">
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       readonly
                       style="width:100%; padding:10px 40px 10px 12px; border:1.5px solid #e5e7eb; border-radius:8px;
                              background:#f3f4f6; color:#6b7280; cursor:not-allowed; font-family:inherit; font-size:14px;">
                <div style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:16px; color:#9ca3af;" title="Email terkunci">
                    🔒
                </div>
            </div>

            {{-- INFO BOX --}}
            <div style="margin-top:8px; padding:10px 14px; background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:flex-start; gap:8px;">
                <div style="font-size:16px; flex-shrink:0;">ℹ️</div>
                <div style="font-size:12.5px; color:#1e40af; line-height:1.5;">
                    <strong>Email digunakan untuk login akun.</strong> Untuk mengubah email, silakan hubungi atau konfirmasi ke <strong>Ketua RT</strong> setempat.
                </div>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Nomor HP')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                          :value="old('phone', $user->phone)" placeholder="081234567890" />
        </div>

        {{-- DATA DIRI --}}
        <div style="padding-top:16px; border-top:2px solid #f3f4f6;">
            <h3 style="font-weight:700; font-size:14px; color:#374151; margin-bottom:14px;">👤 Data Diri</h3>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                    <x-text-input id="tempat_lahir" name="tempat_lahir" type="text" class="mt-1 block w-full"
                                  :value="old('tempat_lahir', $user->tempat_lahir)" placeholder="Contoh: Depok" />
                </div>

                <div>
                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full"
                                  :value="old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '')" />
                </div>
            </div>

            <div style="margin-top:14px;">
                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                <select id="jenis_kelamin" name="jenis_kelamin"
                        style="margin-top:4px; width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div style="margin-top:14px;">
                <x-input-label for="alamat" :value="__('Alamat')" />
                <x-text-input id="alamat" name="alamat" type="text" class="mt-1 block w-full"
                              :value="old('alamat', $user->alamat)" placeholder="Jl. Mawar No. 10" />
            </div>
        </div>

        {{-- TTD & STEMPEL (Khusus RT/Kelurahan) --}}
        @if (in_array($user->role, ['rt', 'kelurahan', 'admin']))
            <div style="padding-top:16px; border-top:2px solid #f3f4f6;">
                <h3 style="font-weight:700; font-size:14px; color:#374151; margin-bottom:6px;">✍️ Tanda Tangan & Stempel Digital</h3>
                <p style="font-size:12px; color:#6b7280; margin-bottom:14px; line-height:1.5;">
                    Upload gambar TTD & stempel (PNG transparan lebih baik). Akan otomatis muncul di PDF surat.
                </p>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                    <div>
                        <x-input-label for="nip" :value="__('NIP (opsional)')" />
                        <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full"
                                      :value="old('nip', $user->nip)" placeholder="198501012010011001" />
                    </div>
                    <div>
                        <x-input-label for="jabatan" :value="__('Jabatan')" />
                        <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full"
                                      :value="old('jabatan', $user->jabatan)" placeholder="Lurah Kelurahan Sungai Asam" />
                    </div>
                </div>

                <div style="margin-top:16px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <x-input-label :value="__('Tanda Tangan')" />
                        @if ($user->ttd_path)
                            <div style="margin-top:6px; padding:10px; background:#f9fafb; border:1px dashed #d1d5db; border-radius:8px; text-align:center;">
                                <img src="{{ route('profile.ttd', $user) }}" alt="TTD"
                                     style="max-height:80px; max-width:100%;">
                            </div>
                        @endif
                        <input type="file" name="ttd" accept=".png,.jpg,.jpeg"
                               style="margin-top:8px; display:block; width:100%; padding:6px; border:1px solid #ccc; border-radius:6px;">
                        <p style="font-size:11px; color:#9ca3af; margin-top:4px;">PNG transparan, maks 1MB</p>
                    </div>

                    <div>
                        <x-input-label :value="__('Stempel')" />
                        @if ($user->stempel_path)
                            <div style="margin-top:6px; padding:10px; background:#f9fafb; border:1px dashed #d1d5db; border-radius:8px; text-align:center;">
                                <img src="{{ route('profile.stempel', $user) }}" alt="Stempel"
                                     style="max-height:80px; max-width:100%;">
                            </div>
                        @endif
                        <input type="file" name="stempel" accept=".png,.jpg,.jpeg"
                               style="margin-top:8px; display:block; width:100%; padding:6px; border:1px solid #ccc; border-radius:6px;">
                        <p style="font-size:11px; color:#9ca3af; margin-top:4px;">PNG transparan, maks 1MB</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>

    {{-- HAPUS TTD & STEMPEL (Di luar form utama biar tidak bentrok) --}}
    @if (in_array($user->role, ['rt', 'kelurahan', 'admin']))
        @if ($user->ttd_path || $user->stempel_path)
            <div style="margin-top:20px; padding-top:16px; border-top:2px solid #f3f4f6;">
                <div style="font-size:12px; color:#6b7280; margin-bottom:10px;">Pilihan lain:</div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    @if ($user->ttd_path)
                        <form method="POST" action="{{ route('profile.ttd.hapus') }}"
                              onsubmit="return confirm('Yakin hapus tanda tangan?')" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:8px 16px; background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit;">
                                🗑️ Hapus Tanda Tangan
                            </button>
                        </form>
                    @endif
                    @if ($user->stempel_path)
                        <form method="POST" action="{{ route('profile.stempel.hapus') }}"
                              onsubmit="return confirm('Yakin hapus stempel?')" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:8px 16px; background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit;">
                                🗑️ Hapus Stempel
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    @endif

</section>