<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    ⚙️ Pengaturan Kelurahan
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Kelola identitas, alamat, dan pejabat kelurahan
                </p>
            </div>
            <a href="{{ route('kelurahan.dashboard') }}"
               style="padding:10px 20px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                ← Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:960px; margin:0 auto; padding:0 20px;">

            @if (session('success'))
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1px solid #a7f3d0; border-left:5px solid #10b981; border-radius:14px; padding:16px 22px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:40px; height:40px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px;">
                        ✓
                    </div>
                    <div style="font-weight:600; font-size:14px; color:#065f46;">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:12px; padding:16px 20px; margin-bottom:20px;">
                    <div style="font-weight:700; color:#991b1b; font-size:14px; margin-bottom:6px;">Ada kesalahan pada form</div>
                    <ul style="margin:0; padding-left:18px; font-size:13px; color:#7f1d1d; line-height:1.7;">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- INFO BOX --}}
            <div style="background:linear-gradient(135deg, #eff6ff 0%, #ede9fe 100%); border:1.5px solid #c7d2fe; border-radius:14px; padding:16px 22px; margin-bottom:24px; display:flex; align-items:flex-start; gap:14px;">
                <div style="flex-shrink:0; font-size:24px;">💡</div>
                <div style="font-size:13px; color:#1e40af; line-height:1.6;">
                    <strong>Info:</strong> Semua pengaturan di bawah ini akan langsung muncul di <strong>kop surat PDF</strong>, header halaman, footer, dan beranda. Setelah disimpan, tidak perlu edit kode lagi.
                </div>
            </div>

            <form method="POST" action="{{ route('kelurahan.pengaturan.update') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- ============ GRUP: LOGO KELURAHAN ============ --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; padding-bottom:16px; border-bottom:2px solid #f3f4f6;">
                        <div style="width:40px; height:40px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:white; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                            🖼️
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Logo Kelurahan</div>
                            <div style="font-size:12px; color:#6b7280;">Akan muncul di kop surat & header</div>
                        </div>
                    </div>

                    <div style="display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                        <div style="flex-shrink:0;">
                            @if (setting('logo_path'))
                                <div style="width:120px; height:120px; background:#f9fafb; border:2px dashed #d1d5db; border-radius:12px; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                                    <img src="{{ route('kelurahan.logo') }}" alt="Logo" style="max-width:100%; max-height:100%; object-fit:contain;">
                                </div>
                                {{-- TOMBOL HAPUS LOGO --}}
                                <button type="button" onclick="hapusLogo()"
                                        style="margin-top:10px; width:120px; padding:8px 12px; background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                                    🗑️ Hapus Logo
                                </button>
                            @else
                                <div style="width:120px; height:120px; background:#f9fafb; border:2px dashed #d1d5db; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:36px; color:#d1d5db;">
                                    🏛️
                                </div>
                            @endif
                        </div>
                        <div style="flex:1; min-width:220px;">
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                Upload Logo Baru
                            </label>
                            <input type="file" name="logo" accept=".jpg,.jpeg,.png"
                                   style="width:100%; padding:10px; border:1.5px solid #e5e7eb; border-radius:10px; font-family:inherit; font-size:13px; background:white;">
                            <div style="font-size:11.5px; color:#9ca3af; margin-top:6px;">
                                Format: PNG / JPG, maksimal 2MB. Disarankan PNG transparan.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ GRUP: IDENTITAS WILAYAH ============ --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; padding-bottom:16px; border-bottom:2px solid #f3f4f6;">
                        <div style="width:40px; height:40px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:white; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                            📍
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Identitas Wilayah</div>
                            <div style="font-size:12px; color:#6b7280;">Nama kelurahan, kecamatan, kota</div>
                        </div>
                    </div>

                    @php
                        $grupUmum = $grup['umum'] ?? collect();
                    @endphp

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        @foreach ($grupUmum as $item)
                            <div>
                                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                    {{ $item->label }}
                                    @if (in_array($item->key, ['nama_kelurahan', 'nama_kecamatan', 'nama_kota']))
                                        <span style="color:#dc2626;">*</span>
                                    @endif
                                </label>
                                <input type="text" name="{{ $item->key }}" value="{{ old($item->key, $item->value) }}"
                                       style="width:100%; padding:11px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============ GRUP: ALAMAT & KONTAK ============ --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; padding-bottom:16px; border-bottom:2px solid #f3f4f6;">
                        <div style="width:40px; height:40px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:white; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                            📞
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Alamat & Kontak</div>
                            <div style="font-size:12px; color:#6b7280;">Alamat kantor, telepon, email</div>
                        </div>
                    </div>

                    @php
                        $grupKontak = $grup['kontak'] ?? collect();
                    @endphp

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        @foreach ($grupKontak as $item)
                            <div style="{{ $item->tipe === 'textarea' ? 'grid-column: span 2;' : '' }}">
                                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                    {{ $item->label }}
                                    @if ($item->key === 'alamat_kelurahan')
                                        <span style="color:#dc2626;">*</span>
                                    @endif
                                </label>

                                @if ($item->tipe === 'textarea')
                                    <textarea name="{{ $item->key }}" rows="2"
                                              style="width:100%; padding:11px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; resize:vertical; background:white;">{{ old($item->key, $item->value) }}</textarea>
                                @else
                                    <input type="{{ $item->tipe }}" name="{{ $item->key }}" value="{{ old($item->key, $item->value) }}"
                                           style="width:100%; padding:11px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============ GRUP: PEJABAT ============ --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; padding-bottom:16px; border-bottom:2px solid #f3f4f6;">
                        <div style="width:40px; height:40px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:white; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                            👤
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Data Lurah</div>
                            <div style="font-size:12px; color:#6b7280;">Nama & NIP pejabat yang tanda tangan surat</div>
                        </div>
                    </div>

                    @php
                        $grupPejabat = $grup['pejabat'] ?? collect();
                    @endphp

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        @foreach ($grupPejabat as $item)
                            <div class="{{ $item->key === 'jabatan_lurah' ? 'grid-column: span 2;' : '' }}">
                                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                    {{ $item->label }}
                                    @if (in_array($item->key, ['nama_lurah', 'jabatan_lurah']))
                                        <span style="color:#dc2626;">*</span>
                                    @endif
                                </label>
                                <input type="text" name="{{ $item->key }}" value="{{ old($item->key, $item->value) }}"
                                       style="width:100%; padding:11px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============ GRUP: SISTEM ============ --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; padding-bottom:16px; border-bottom:2px solid #f3f4f6;">
                        <div style="width:40px; height:40px; background:linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:20px; color:white; box-shadow:0 4px 12px rgba(124,58,237,0.3);">
                            🔧
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Pengaturan Sistem</div>
                            <div style="font-size:12px; color:#6b7280;">Nama aplikasi & jam operasional</div>
                        </div>
                    </div>

                    @php
                        $grupSistem = $grup['sistem'] ?? collect();
                    @endphp

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        @foreach ($grupSistem as $item)
                            <div class="{{ $item->key === 'app_full_name' ? 'grid-column: span 2;' : '' }}">
                                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                                    {{ $item->label }}
                                </label>
                                <input type="text" name="{{ $item->key }}" value="{{ old($item->key, $item->value) }}"
                                       style="width:100%; padding:11px 14px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; background:white;">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TOMBOL SIMPAN --}}
                <div style="display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap; position:sticky; bottom:20px;">
                    <a href="{{ route('kelurahan.dashboard') }}"
                       style="padding:14px 26px; background:white; border:1.5px solid #e5e7eb; border-radius:12px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; display:inline-flex; align-items:center; box-shadow:0 4px 14px rgba(0,0,0,0.06);">
                        Batal
                    </a>
                    <button type="submit"
                            style="padding:14px 32px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border:none; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit; display:inline-flex; align-items:center; gap:8px;">
                        💾 Simpan Pengaturan
                    </button>
                </div>

            </form>

            {{-- FORM HAPUS LOGO (hidden, dipicu oleh tombol) --}}
            <form id="formHapusLogo" method="POST" action="{{ route('kelurahan.pengaturan.logo.hapus') }}" style="display:none;">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>

    <script>
        function hapusLogo() {
            if (confirm('⚠ Yakin ingin menghapus logo kelurahan?\n\nLogo akan hilang dari kop surat & header. Anda bisa upload ulang kapan saja.')) {
                document.getElementById('formHapusLogo').submit();
            }
        }
    </script>
</x-app-layout>