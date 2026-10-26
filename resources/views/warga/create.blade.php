<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    {{ isset($suratLama) ? '🔄 Ajukan Ulang Surat' : '📝 Ajukan Surat Baru' }}
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    {{ isset($suratLama) ? 'Data sudah terisi otomatis — perbaiki bagian yang salah' : 'Isi form di bawah untuk mengajukan surat keterangan' }}
                </p>
            </div>
            <a href="{{ route('warga.surat.index') }}"
               style="padding:10px 18px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:6px; background:white;">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:800px; margin:0 auto; padding:0 20px;">

            {{-- BANNER INFO REAPPLY --}}
            @if (isset($suratLama))
                <div style="background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:2px solid #60a5fa; border-left:5px solid #2563eb; border-radius:14px; padding:18px 22px; margin-bottom:20px;">
                    <div style="display:flex; align-items:flex-start; gap:14px;">
                        <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
                            🔄
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:800; color:#1e40af; font-size:14.5px; margin-bottom:4px;">Pengajuan Ulang</div>
                            <div style="font-size:12.5px; color:#1e40af; line-height:1.6;">
                                Data dari pengajuan <strong style="font-family:monospace;">{{ $suratLama->nomor_pengajuan }}</strong> sudah terisi otomatis.
                                Dokumen lama akan <strong>digunakan kembali</strong> jika Anda tidak upload file baru.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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

            @if (session('error'))
                <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:12px; padding:14px 18px; margin-bottom:20px; font-size:13.5px; color:#991b1b;">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <div id="alertValidasi" style="display:none; background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:12px; padding:16px 20px; margin-bottom:20px; align-items:flex-start; gap:12px;">
                <div style="flex-shrink:0; width:28px; height:28px; background:#dc2626; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">!</div>
                <div style="flex:1;">
                    <div style="font-weight:700; color:#991b1b; font-size:14px; margin-bottom:6px;">Form belum lengkap</div>
                    <ul id="alertList" style="margin:0; padding-left:18px; font-size:13px; color:#7f1d1d; line-height:1.7;"></ul>
                </div>
            </div>

            <form method="POST" action="{{ route('warga.surat.store') }}" enctype="multipart/form-data" id="formAjukan">
                @csrf
                @if (isset($suratLama))
                    <input type="hidden" name="reapply_from_id" value="{{ $suratLama->id }}">
                @endif

                {{-- SECTION 1: PILIH LAYANAN --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                        <div style="width:32px; height:32px; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">1</div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Pilih Jenis Layanan</div>
                            <div style="font-size:12px; color:#6b7280;">Klik kartu untuk memilih, klik ikon <strong>ℹ️</strong> untuk melihat syarat.</div>
                        </div>
                    </div>

                    @php
                        $jenisOptions = [
                            ['value' => 'Surat Keterangan Domisili', 'icon' => '🏠', 'label' => 'Keterangan Domisili', 'desc' => 'Untuk keterangan tempat tinggal', 'color' => '#2563eb', 'bg' => '#eff6ff', 'syarat' => '1. Pengantar dari RT<br>2. Fotokopi KTP dan KK<br>3. Bukti lunas PBB tahun 2026'],
                            ['value' => 'Surat Keterangan Tidak Mampu (SKTM)', 'icon' => '🤝', 'label' => 'SKTM', 'desc' => 'Untuk beasiswa / bantuan sosial', 'color' => '#d97706', 'bg' => '#fffbeb', 'syarat' => '1. Pengantar dari RT<br>2. Fotokopi KK dan KTP<br>3. Bukti lunas PBB'],
                            ['value' => 'Pengantar Nikah', 'icon' => '💍', 'label' => 'Pengantar Nikah', 'desc' => 'Untuk administrasi pernikahan', 'color' => '#be185d', 'bg' => '#fce7f3', 'syarat' => '1. Pengantar dari RT<br>2. Bukti lunas PBB<br>3. Fotokopi KTP & KK (yang bersangkutan, orang tua, & orang tua calon pasangan)<br>4. Pas foto ukuran 3x4 sebanyak 3 lembar<br>5. Surat keterangan kesehatan'],
                            ['value' => 'Surat Pernyataan Ahli Waris', 'icon' => '📜', 'label' => 'Ahli Waris', 'desc' => 'Untuk penentuan ahli waris', 'color' => '#4338ca', 'bg' => '#e0e7ff', 'syarat' => '1. Akta kematian<br>2. Fotokopi KTP & KK para ahli waris<br>3. Pengantar dari RT<br>4. Fotokopi KTP 2 (dua) orang saksi<br>5. Bukti lunas PBB tahun berjalan'],
                            ['value' => 'Surat Keterangan Belum Menikah', 'icon' => '👤', 'label' => 'Belum Menikah', 'desc' => 'Untuk keterangan status', 'color' => '#7e22ce', 'bg' => '#f3e8ff', 'syarat' => '1. Pengantar dari RT<br>2. Fotokopi KTP dan KK<br>3. Bukti lunas PBB tahun 2026 / tahun berjalan'],
                            ['value' => 'Surat Keterangan Belum Memiliki Rumah', 'icon' => '🏡', 'label' => 'Belum Punya Rumah', 'desc' => 'Untuk keterangan tempat tinggal', 'color' => '#065f46', 'bg' => '#d1fae5', 'syarat' => '1. Pengantar dari RT<br>2. Fotokopi KTP dan KK<br>3. Bukti lunas PBB tahun 2026 / tahun berjalan'],
                        ];
                        $oldJenis = old('jenis_surat', $oldJenisSurat ?? '');
                    @endphp

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:20px;">
                        @foreach ($jenisOptions as $opt)
                            <label class="jenis-card" style="cursor:pointer; position:relative; display:block;">
                                <input type="radio" name="jenis_surat" value="{{ $opt['value'] }}" style="position:absolute; opacity:0; pointer-events:none;" {{ $oldJenis === $opt['value'] ? 'checked' : '' }}>
                                <div class="jenis-inner" data-color="{{ $opt['color'] }}" data-bg="{{ $opt['bg'] }}" style="border:2px solid #e5e7eb; border-radius:12px; padding:20px 16px 16px; transition:0.2s; background:white; height:100%;">
                                    <div class="info-btn" onclick="event.preventDefault(); event.stopPropagation(); showSyarat(this)" data-title="{{ $opt['label'] }}" data-syarat="{{ $opt['syarat'] }}" style="position:absolute; top:10px; right:10px; width:24px; height:24px; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; color:#6b7280; cursor:pointer; z-index:10;" title="Lihat Syarat">ℹ️</div>
                                    <div style="font-size:28px; margin-bottom:8px; line-height:1;">{{ $opt['icon'] }}</div>
                                    <div style="font-weight:700; font-size:13.5px; color:#111827; margin-bottom:3px;">{{ $opt['label'] }}</div>
                                    <div style="font-size:11.5px; color:#6b7280; line-height:1.4;">{{ $opt['desc'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">Keterangan Tambahan <span style="font-size:11px; color:#9ca3af; font-weight:400;">(opsional)</span></label>
                        <input type="text" name="keperluan" value="{{ old('keperluan', $oldKeperluan ?? '') }}" placeholder="Contoh: Untuk pembuatan rekening bank" style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; color:#111827; background:white;">
                    </div>
                </div>

                {{-- SECTION 2: UPLOAD DOKUMEN (DINAMIS) --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                        <div style="width:32px; height:32px; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">2</div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Upload Dokumen</div>
                            <div style="font-size:12px; color:#6b7280;" id="upload-subtitle">Silakan pilih jenis layanan di atas untuk melihat dokumen yang dibutuhkan.</div>
                        </div>
                    </div>

                    {{-- Tempat Kotak Upload Dirender oleh JS --}}
                    <div id="dynamic-upload-container" style="display:none; flex-direction:column; gap:18px;"></div>
                </div>

                {{-- SECTION 3: SYARAT & KETENTUAN --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                        <div style="width:32px; height:32px; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">3</div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Syarat & Ketentuan</div>
                            <div style="font-size:12px; color:#6b7280;">Baca dengan teliti sebelum melanjutkan</div>
                        </div>
                    </div>
                    <div style="background:#fafbfc; border:1.5px solid #e5e7eb; border-radius:12px; padding:20px 22px; margin-bottom:18px; max-height:200px; overflow-y:auto;">
                        <ol style="margin:0; padding-left:20px; font-size:13px; color:#374151; line-height:1.85;">
                            <li style="margin-bottom:8px;"><strong>Kebenaran Data.</strong> Seluruh data yang saya isi adalah <strong>benar, akurat, dan dapat dipertanggungjawabkan</strong>.</li>
                            <li style="margin-bottom:8px;"><strong>Keaslian Dokumen.</strong> Dokumen yang saya upload adalah <strong>asli dan bukan hasil manipulasi</strong>.</li>
                            <li><strong>Pengambilan Surat.</strong> Saya memahami surat fisik diambil di kantor kelurahan.</li>
                        </ol>
                    </div>
                    <label for="setuju" id="labelSetuju" style="display:flex; align-items:flex-start; gap:12px; padding:16px 18px; background:#f0f9ff; border:2px solid #bfdbfe; border-radius:12px; cursor:pointer;">
                        <input type="checkbox" name="setuju" id="setuju" value="1" style="width:22px; height:22px; margin-top:1px; cursor:pointer; accent-color:#2563eb; flex-shrink:0;">
                        <div style="font-size:13.5px; color:#1e40af; line-height:1.6; font-weight:600;">Saya telah membaca dan <strong>menyetujui</strong> seluruh Syarat & Ketentuan di atas.</div>
                    </label>
                </div>

                {{-- TOMBOL --}}
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <button type="submit" id="btnSubmit" style="flex:1; min-width:200px; padding:14px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                        <span id="btnText">{{ isset($suratLama) ? '🔄 Kirim Pengajuan Ulang' : '🚀 Kirim ke RT' }}</span>
                    </button>
                    <a href="{{ route('warga.surat.index') }}" style="padding:14px 28px; background:white; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; display:inline-flex; align-items:center;">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL SYARAT BERKAS --}}
    <div id="modalSyarat" onclick="if(event.target === this) closeSyarat()" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); z-index:99999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:white; border-radius:16px; max-width:450px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden; animation:slideUp 0.3s ease-out;">
            <div style="padding:20px 24px; background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-bottom:1px solid #bfdbfe; display:flex; justify-content:space-between; align-items:center;">
                <div style="font-weight:800; font-size:16px; color:#1e40af;" id="modalSyaratTitle">Syarat Berkas</div>
                <button type="button" onclick="closeSyarat()" style="background:white; border:none; width:30px; height:30px; border-radius:8px; cursor:pointer; font-size:18px; color:#1e40af; display:flex; align-items:center; justify-content:center;">×</button>
            </div>
            <div style="padding:24px; font-size:13.5px; color:#374151; line-height:1.8;" id="modalSyaratBody"></div>
        </div>
    </div>

    <style>
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        input[type="radio"]:checked + .jenis-inner { border-color: var(--check-color); background: var(--check-bg); box-shadow: 0 4px 14px rgba(0,0,0,0.08); }
        .jenis-card:hover .jenis-inner { border-color: #93c5fd; transform: translateY(-2px); }
        input[type="radio"]:checked + .jenis-inner::before { content: '✓'; position: absolute; top: 8px; right: 10px; width: 22px; height: 22px; background: var(--check-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; z-index: 20; }
        input[type="radio"]:checked + .jenis-inner .info-btn { display: none; }
        .jenis-inner { position: relative; }
        .upload-box:hover .upload-inner { border-color: #93c5fd; background: #f0f9ff; }
        .upload-inner.has-file { border-style: solid; border-color: #10b981; background: #ecfdf5; }
        .upload-inner.error { border-color: #dc2626; background: #fef2f2; animation: shake 0.4s; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
        #labelSetuju:has(input:checked) { background: #ecfdf5; border-color: #6ee7b7; }
    </style>

    <script>
        const isReapply = {{ isset($suratLama) ? 'true' : 'false' }};

        // MAPPING LENGKAP DOKUMEN BERDASARKAN JENIS SURAT
        const docMapping = {
            'Surat Keterangan Domisili': [
                { field: 'ktp[]', label: 'Scan KTP', req: true, multiple: false },
                { field: 'kk[]', label: 'Scan KK', req: true, multiple: false },
                { field: 'foto[]', label: 'Bukti Lunas PBB', req: true, multiple: false }
            ],
            'Surat Keterangan Tidak Mampu (SKTM)': [
                { field: 'ktp[]', label: 'Scan KTP', req: true, multiple: false },
                { field: 'kk[]', label: 'Scan KK', req: true, multiple: false },
                { field: 'foto[]', label: 'Bukti Lunas PBB', req: true, multiple: false }
            ],
            'Pengantar Nikah': [
                { field: 'ktp[]', label: 'KTP Yang Bersangkutan', req: true, multiple: false },
                { field: 'kk[]', label: 'KK Yang Bersangkutan', req: true, multiple: false },
                { field: 'ktp[]', label: 'KTP Orang Tua', req: true, multiple: false },
                { field: 'kk[]', label: 'KK Orang Tua', req: true, multiple: false },
                { field: 'ktp[]', label: 'KTP Orang Tua Calon Pasangan', req: true, multiple: false },
                { field: 'kk[]', label: 'KK Orang Tua Calon Pasangan', req: true, multiple: false },
                { field: 'foto[]', label: 'Pas Foto 3x4 (Pilih 3 file)', req: true, multiple: true },
                { field: 'foto[]', label: 'Surat Keterangan Sehat', req: true, multiple: false },
                { field: 'foto[]', label: 'Bukti Lunas PBB', req: true, multiple: false } // <-- TAMBAHAN BARU
            ],
            'Surat Pernyataan Ahli Waris': [
                { field: 'ktp[]', label: 'KTP Ahli Waris', req: true, multiple: false },
                { field: 'kk[]', label: 'KK Ahli Waris', req: true, multiple: false },
                { field: 'foto[]', label: 'Akta Kematian', req: true, multiple: false },
                { field: 'foto[]', label: 'Bukti Lunas PBB Tahun Berjalan', req: true, multiple: false },
                { field: 'ktp[]', label: 'KTP Saksi 1', req: true, multiple: false },
                { field: 'ktp[]', label: 'KTP Saksi 2', req: true, multiple: false }
            ],
            'Surat Keterangan Belum Menikah': [
                { field: 'ktp[]', label: 'Scan KTP', req: true, multiple: false },
                { field: 'kk[]', label: 'Scan KK', req: true, multiple: false },
                { field: 'foto[]', label: 'Bukti Lunas PBB', req: true, multiple: false }
            ],
            'Surat Keterangan Belum Memiliki Rumah': [
                { field: 'ktp[]', label: 'Scan KTP', req: true, multiple: false },
                { field: 'kk[]', label: 'Scan KK', req: true, multiple: false },
                { field: 'foto[]', label: 'Bukti Lunas PBB', req: true, multiple: false }
            ]
        };

        // FUNGSI RENDER KOTAK UPLOAD DINAMIS
        function renderUploadBoxes(docs) {
            const container = document.getElementById('dynamic-upload-container');
            container.innerHTML = ''; 
            
            if (!docs) return;

            docs.forEach((doc, index) => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">
                        📄 ${doc.label} <span style="color:#dc2626;">${doc.req ? '*' : ''}</span>
                    </label>
                    <label class="upload-box" style="display:block; cursor:pointer;">
                        <input type="file" name="${doc.field}" id="inputDoc-${index}" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" ${doc.multiple ? 'multiple' : ''}>
                        <div class="upload-inner" id="boxDoc-${index}" style="border:2px dashed #d1d5db; border-radius:12px; padding:24px; text-align:center; transition:0.2s; background:#fafbfc;">
                            <div style="font-size:36px; margin-bottom:8px;" id="iconDoc-${index}">📄</div>
                            <div style="font-weight:600; font-size:13px; color:#374151; margin-bottom:4px;" id="textDoc-${index}">
                                ${isReapply ? 'Klik untuk ganti dokumen (opsional)' : 'Klik untuk pilih file'}
                            </div>
                        </div>
                    </label>
                `;
                container.appendChild(div);
                
                setupUpload(`inputDoc-${index}`, `boxDoc-${index}`, `iconDoc-${index}`, `textDoc-${index}`);
            });
        }

        // LOGIKA SAAT KARTU JENIS SURAT DIKLIK
        document.querySelectorAll('input[name="jenis_surat"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.jenis-inner').forEach(function(el) {
                    el.style.removeProperty('--check-color');
                    el.style.removeProperty('--check-bg');
                });
                
                if (this.checked) {
                    const inner = this.nextElementSibling;
                    inner.style.setProperty('--check-color', inner.dataset.color);
                    inner.style.setProperty('--check-bg', inner.dataset.bg);
                    document.querySelectorAll('.info-btn').forEach(btn => btn.style.display = 'flex');
                    inner.querySelector('.info-btn').style.display = 'none';

                    const docs = docMapping[this.value];
                    if (docs) {
                        document.getElementById('dynamic-upload-container').style.display = 'flex';
                        document.getElementById('upload-subtitle').innerHTML = `Dokumen wajib untuk <strong>${this.value}</strong> — Format: JPG/PNG/PDF (maks 2MB)`;
                        renderUploadBoxes(docs);
                    }
                }
            });
            if (radio.checked) radio.dispatchEvent(new Event('change'));
        });

        // FUNGSI UPDATE TEKS SAAT FILE DIPILIH
        function setupUpload(inputId, boxId, iconId, textId) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            const icon = document.getElementById(iconId);
            const text = document.getElementById(textId);

            input.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    if (this.files.length === 1) {
                        const file = this.files[0];
                        const size = (file.size / 1024).toFixed(0);
                        const ext = file.name.split('.').pop().toLowerCase();
                        let fileIcon = '📄';
                        if (['jpg', 'jpeg', 'png'].includes(ext)) fileIcon = '🖼️';
                        if (ext === 'pdf') fileIcon = '📕';
                        
                        icon.textContent = fileIcon;
                        text.textContent = file.name + ' (' + size + ' KB)';
                    } else {
                        icon.textContent = '🗂️';
                        text.textContent = this.files.length + ' File berhasil dipilih';
                    }
                    box.classList.add('has-file');
                    box.classList.remove('error');
                }
            });
        }

        // MODAL SYARAT
        function showSyarat(element) {
            document.getElementById('modalSyaratTitle').innerHTML = 'Syarat: ' + element.getAttribute('data-title');
            document.getElementById('modalSyaratBody').innerHTML = element.getAttribute('data-syarat');
            document.getElementById('modalSyarat').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeSyarat() {
            document.getElementById('modalSyarat').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeSyarat(); });

        // VALIDASI FORM SEBELUM SUBMIT
        document.getElementById('formAjukan').addEventListener('submit', function(e) {
            const errors = [];
            
            const jenisSurat = document.querySelector('input[name="jenis_surat"]:checked');
            if (!jenisSurat) {
                errors.push('Silakan pilih <strong>Jenis Layanan</strong>.');
            } else {
                if (!isReapply) {
                    // Hitung total file yang diupload per kategori
                    let ktpCount = 0, kkCount = 0, fotoCount = 0;
                    document.querySelectorAll('input[name="ktp[]"]').forEach(el => ktpCount += el.files.length);
                    document.querySelectorAll('input[name="kk[]"]').forEach(el => kkCount += el.files.length);
                    document.querySelectorAll('input[name="foto[]"]').forEach(el => fotoCount += el.files.length);

                    // Validasi Khusus Pengantar Nikah
                    if (jenisSurat.value === 'Pengantar Nikah') {
                        if (ktpCount < 3) errors.push('Wajib upload <strong>3 KTP</strong> (Warga, Ortu, Pasangan).');
                        if (kkCount < 3) errors.push('Wajib upload <strong>3 KK</strong> (Warga, Ortu, Pasangan).');
                        if (fotoCount < 3) errors.push('Wajib upload <strong>Pas Foto</strong>, <strong>Surat Sehat</strong>, & <strong>Bukti PBB</strong>.');
                    } 
                    // Validasi Khusus Ahli Waris
                    else if (jenisSurat.value === 'Surat Pernyataan Ahli Waris') {
                        if (ktpCount < 3) errors.push('Wajib upload <strong>KTP Ahli Waris + 2 Saksi</strong>.');
                        if (kkCount < 1) errors.push('Wajib upload <strong>KK Ahli Waris</strong>.');
                        if (fotoCount < 2) errors.push('Wajib upload <strong>Akta Kematian</strong> & <strong>Bukti Lunas PBB</strong>.');
                    } 
                    // Validasi Surat Lainnya
                    else {
                        if (ktpCount < 1) errors.push('Dokumen <strong>KTP</strong> wajib diupload.');
                        if (kkCount < 1) errors.push('Dokumen <strong>KK</strong> wajib diupload.');
                        if (fotoCount < 1) errors.push('Dokumen <strong>Pendukung/PBB</strong> wajib diupload.');
                    }
                }
            }

            if (!document.getElementById('setuju').checked) errors.push('Anda harus <strong>menyetujui Syarat & Ketentuan</strong>.');

            if (errors.length > 0) {
                e.preventDefault();
                const alertBox = document.getElementById('alertValidasi');
                document.getElementById('alertList').innerHTML = errors.map(er => '<li>' + er + '</li>').join('');
                alertBox.style.display = 'flex';
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => { alertBox.style.display = 'none'; }, 8000);
                return false;
            }

            document.getElementById('btnText').innerHTML = '⏳ Mengirim...';
            document.getElementById('btnSubmit').disabled = true;
            document.getElementById('btnSubmit').style.opacity = '0.7';
        });
    </script>
</x-app-layout>