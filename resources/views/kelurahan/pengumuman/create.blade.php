<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:14px;">
            <a href="{{ route('kelurahan.pengumuman.index') }}"
               style="width:44px; height:44px; background:white; border:1.5px solid #e5e7eb; border-radius:12px; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6b7280; font-size:20px;">←</a>
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(139,92,246,0.3);">📢</div>
                <div>
                    <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">Buat Pengumuman</h2>
                    <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">Informasi akan tampil di halaman beranda</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:800px; margin:0 auto; padding:0 20px;">
            <form action="{{ route('kelurahan.pengumuman.store') }}" method="POST"
                  style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">
                @csrf

                <div style="padding:20px 24px; background:linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border-bottom:1px solid #e9d5ff; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px;">📝</div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#5b21b6; margin:0;">Detail Pengumuman</h3>
                        <p style="font-size:12px; color:#7c3aed; margin:3px 0 0 0;">Isi semua kolom dengan data yang valid</p>
                    </div>
                </div>

                <div style="padding:24px; display:flex; flex-direction:column; gap:18px;">

                    <div>
                        <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                            📌 Judul <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required
                               placeholder="Contoh: Posyandu Balita 20 Oktober 2026"
                               style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; box-sizing:border-box;">
                        @error('judul') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                            🏷️ Kategori <span style="color:#dc2626;">*</span>
                        </label>
                        <select name="kategori" required
                                style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; box-sizing:border-box; background:white;">
                            <option value="info" {{ old('kategori') == 'info' ? 'selected' : '' }}>ℹ️ Info Umum</option>
                            <option value="penting" {{ old('kategori') == 'penting' ? 'selected' : '' }}>🔴 Penting</option>
                            <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>📅 Kegiatan</option>
                        </select>
                        @error('kategori') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                            📝 Isi Pengumuman <span style="color:#dc2626;">*</span>
                        </label>
                        <textarea name="konten" rows="6" required
                                  placeholder="Tulis isi pengumuman di sini..."
                                  style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; box-sizing:border-box; font-family:inherit; resize:vertical;">{{ old('konten') }}</textarea>
                        @error('konten') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                📅 Tanggal Mulai <span style="color:#dc2626;">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                                   style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; box-sizing:border-box;">
                            @error('tanggal_mulai') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:7px;">
                                📅 Tanggal Selesai
                                <span style="font-size:11.5px; color:#6b7280; font-weight:500;">(opsional)</span>
                            </label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                   style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:12px; font-size:13.5px; outline:none; box-sizing:border-box;">
                            @error('tanggal_selesai') <p style="margin-top:6px; font-size:12px; color:#dc2626;">⚠️ {{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fafbfc; border-radius:12px;">
                        <input type="checkbox" name="is_published" id="is_published" value="1"
                               {{ old('is_published', true) ? 'checked' : '' }}
                               style="width:18px; height:18px; cursor:pointer;">
                        <label for="is_published" style="font-size:13px; font-weight:600; color:#374151; cursor:pointer;">
                            ✅ Publish langsung (tampil di beranda)
                        </label>
                    </div>
                </div>

                <div style="padding:18px 24px; background:#fafbfc; border-top:1px solid #f3f4f6; display:flex; justify-content:flex-end; gap:12px;">
                    <a href="{{ route('kelurahan.pengumuman.index') }}"
                       style="padding:11px 24px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600;">Batal</a>
                    <button type="submit"
                            style="padding:11px 28px; background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border:none; border-radius:10px; font-size:13px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(139,92,246,0.3);">
                        ✓ Simpan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>