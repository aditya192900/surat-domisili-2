<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                    📝 Ajukan Surat Baru
                </h2>
                <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                    Isi form di bawah untuk mengajukan surat keterangan
                </p>
            </div>
            <a href="{{ route('warga.surat.index') }}"
               style="padding:10px 18px; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:6px; background:white;">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:780px; margin:0 auto; padding:0 20px;">

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

            <form method="POST" action="{{ route('warga.surat.store') }}"
                  enctype="multipart/form-data" id="formAjukan">
                @csrf

                {{-- SECTION 1: PILIH LAYANAN --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                        <div style="width:32px; height:32px; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">1</div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Pilih Jenis Layanan</div>
                            <div style="font-size:12px; color:#6b7280;">Klik kartu di bawah untuk memilih</div>
                        </div>
                    </div>

                    @php
                        $jenisOptions = [
                            ['value' => 'Surat Keterangan Domisili', 'icon' => '🏠', 'label' => 'Keterangan Domisili', 'desc' => 'Untuk keterangan tempat tinggal', 'color' => '#2563eb', 'bg' => '#eff6ff'],
                            ['value' => 'Surat Keterangan Tidak Mampu (SKTM)', 'icon' => '🤝', 'label' => 'SKTM', 'desc' => 'Untuk beasiswa / bantuan sosial', 'color' => '#d97706', 'bg' => '#fffbeb'],
                            ['value' => 'Surat Keterangan Usaha (SKU)', 'icon' => '🏪', 'label' => 'Keterangan Usaha', 'desc' => 'Untuk keterangan kepemilikan usaha', 'color' => '#059669', 'bg' => '#ecfdf5'],
                        ];
                    @endphp

                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(210px, 1fr)); gap:12px; margin-bottom:20px;">
                        @foreach ($jenisOptions as $opt)
                            <label class="jenis-card" style="cursor:pointer; position:relative;">
                                <input type="radio" name="jenis_surat" value="{{ $opt['value'] }}"
                                       style="position:absolute; opacity:0; pointer-events:none;"
                                       {{ old('jenis_surat') === $opt['value'] ? 'checked' : '' }} required>
                                <div class="jenis-inner" data-color="{{ $opt['color'] }}" data-bg="{{ $opt['bg'] }}"
                                     style="border:2px solid #e5e7eb; border-radius:12px; padding:16px; transition:0.2s; background:white;">
                                    <div style="font-size:32px; margin-bottom:8px; line-height:1;">{{ $opt['icon'] }}</div>
                                    <div style="font-weight:700; font-size:14px; color:#111827; margin-bottom:3px;">{{ $opt['label'] }}</div>
                                    <div style="font-size:12px; color:#6b7280; line-height:1.4;">{{ $opt['desc'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px;">
                            Keterangan Tambahan
                            <span style="font-size:11px; color:#9ca3af; font-weight:400;">(opsional)</span>
                        </label>
                        <input type="text" name="keperluan" value="{{ old('keperluan') }}"
                               placeholder="Contoh: Untuk pembuatan rekening bank"
                               style="width:100%; padding:12px 16px; border:1.5px solid #e5e7eb; border-radius:10px; font-size:14px; font-family:inherit; color:#111827; background:white;">
                        <div style="font-size:11px; color:#9ca3af; margin-top:6px;">
                            💡 Isi dengan tujuan penggunaan surat supaya mempercepat proses verifikasi
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: UPLOAD DOKUMEN --}}
                <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); padding:26px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                        <div style="width:32px; height:32px; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">2</div>
                        <div>
                            <div style="font-weight:700; font-size:15px; color:#111827;">Upload Dokumen</div>
                            <div style="font-size:12px; color:#6b7280;">Format: JPG, PNG, atau PDF (maks 2MB)</div>
                        </div>
                    </div>

                    {{-- UPLOAD KTP --}}
                    <div style="margin-bottom:18px;">
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">
                            🪪 Scan KTP <span style="color:#dc2626;">*</span>
                        </label>
                        <label class="upload-box" style="display:block; cursor:pointer;">
                            <input type="file" name="ktp" id="inputKtp" accept=".jpg,.jpeg,.png,.pdf"
                                   style="display:none;" required>
                            <div class="upload-inner" id="boxKtp" style="border:2px dashed #d1d5db; border-radius:12px; padding:24px; text-align:center; transition:0.2s; background:#fafbfc;">
                                <div style="font-size:36px; margin-bottom:8px;" id="iconKtp">📄</div>
                                <div style="font-weight:600; font-size:13px; color:#374151; margin-bottom:4px;" id="textKtp">Klik untuk pilih file</div>
                                <div style="font-size:11px; color:#9ca3af;" id="subtextKtp">atau drag & drop file ke sini</div>
                            </div>
                        </label>
                    </div>

                    {{-- UPLOAD KK --}}
                    <div style="margin-bottom:18px;">
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">
                            📋 Scan KK <span style="color:#dc2626;">*</span>
                        </label>
                        <label class="upload-box" style="display:block; cursor:pointer;">
                            <input type="file" name="kk" id="inputKk" accept=".jpg,.jpeg,.png,.pdf"
                                   style="display:none;" required>
                            <div class="upload-inner" id="boxKk" style="border:2px dashed #d1d5db; border-radius:12px; padding:24px; text-align:center; transition:0.2s; background:#fafbfc;">
                                <div style="font-size:36px; margin-bottom:8px;" id="iconKk">📄</div>
                                <div style="font-weight:600; font-size:13px; color:#374151; margin-bottom:4px;" id="textKk">Klik untuk pilih file</div>
                                <div style="font-size:11px; color:#9ca3af;" id="subtextKk">atau drag & drop file ke sini</div>
                            </div>
                        </label>
                    </div>

                    {{-- UPLOAD PAS FOTO --}}
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">
                            📷 Pas Foto 3x4 <span style="color:#dc2626;">*</span>
                        </label>
                        <div style="display:flex; gap:16px; align-items:flex-start; flex-wrap:wrap;">
                            <label class="upload-box" style="flex:1; min-width:220px; cursor:pointer;">
                                <input type="file" name="foto" id="inputFoto" accept=".jpg,.jpeg,.png"
                                       style="display:none;" required>
                                <div class="upload-inner" id="boxFoto" style="border:2px dashed #d1d5db; border-radius:12px; padding:24px; text-align:center; transition:0.2s; background:#fafbfc;">
                                    <div style="font-size:36px; margin-bottom:8px;" id="iconFoto">📷</div>
                                    <div style="font-weight:600; font-size:13px; color:#374151; margin-bottom:4px;" id="textFoto">Klik untuk pilih pas foto</div>
                                    <div style="font-size:11px; color:#9ca3af;" id="subtextFoto">Format: JPG/PNG, maks 2MB</div>
                                </div>
                            </label>
                            <div style="padding:12px 16px; background:#fef3c7; border-radius:10px; font-size:12px; color:#92400e; line-height:1.5; max-width:220px;">
                                <strong>💡 Tips:</strong><br>
                                Gunakan pas foto formal dengan latar belakang merah atau biru, ukuran 3x4.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFO BOX --}}
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px 20px; margin-bottom:20px; display:flex; align-items:flex-start; gap:12px;">
                    <div style="font-size:20px; flex-shrink:0;">ℹ️</div>
                    <div style="font-size:13px; color:#1e40af; line-height:1.6;">
                        <strong>Informasi:</strong> Setelah dikirim, RT akan memverifikasi dan mencetak <strong>Surat Pengantar RT</strong>. Anda perlu membawa surat pengantar tersebut ke kantor kelurahan untuk diproses.
                    </div>
                </div>

                {{-- TOMBOL --}}
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <button type="submit" id="btnSubmit"
                            style="flex:1; min-width:200px; padding:14px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(37,99,235,0.3); font-family:inherit; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
                        <span id="btnText">🚀 Kirim ke RT</span>
                    </button>
                    <a href="{{ route('warga.surat.index') }}"
                       style="padding:14px 28px; background:white; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; color:#374151; font-size:14px; font-weight:600; display:inline-flex; align-items:center;">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    <style>
        input[type="radio"]:checked + .jenis-inner {
            border-color: var(--check-color);
            background: var(--check-bg);
            transform: scale(1.02);
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }
        .jenis-card:hover .jenis-inner {
            border-color: #93c5fd;
            transform: translateY(-2px);
        }
        input[type="radio"]:checked + .jenis-inner::before {
            content: '✓';
            position: absolute;
            top: 8px;
            right: 10px;
            width: 22px;
            height: 22px;
            background: var(--check-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }
        .jenis-inner { position: relative; }
        .upload-box:hover .upload-inner {
            border-color: #93c5fd;
            background: #f0f9ff;
        }
        .upload-inner.has-file {
            border-style: solid;
            border-color: #10b981;
            background: #ecfdf5;
        }
    </style>

    <script>
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
                }
            });
            if (radio.checked) {
                radio.dispatchEvent(new Event('change'));
            }
        });

        function setupUpload(inputId, boxId, iconId, textId, subtextId) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            const icon = document.getElementById(iconId);
            const text = document.getElementById(textId);
            const subtext = document.getElementById(subtextId);

            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const size = (file.size / 1024).toFixed(0);
                    const ext = file.name.split('.').pop().toLowerCase();

                    let fileIcon = '📄';
                    if (['jpg', 'jpeg', 'png'].includes(ext)) fileIcon = '🖼️';
                    if (ext === 'pdf') fileIcon = '📕';

                    icon.textContent = fileIcon;
                    text.textContent = file.name;
                    subtext.textContent = `${size} KB — Klik untuk ganti file`;
                    box.classList.add('has-file');
                }
            });
        }
        setupUpload('inputKtp', 'boxKtp', 'iconKtp', 'textKtp', 'subtextKtp');
        setupUpload('inputKk', 'boxKk', 'iconKk', 'textKk', 'subtextKk');
        setupUpload('inputFoto', 'boxFoto', 'iconFoto', 'textFoto', 'subtextFoto');

        document.getElementById('formAjukan').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
            btnText.innerHTML = '⏳ Mengirim...';
        });
    </script>
</x-app-layout>