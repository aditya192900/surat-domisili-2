<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pengumuman->judul }} - Pelayanan Surat Kelurahan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; color: #111827; line-height: 1.6; }
        a { text-decoration: none; color: inherit; }

        /* NAVBAR */
        .navbar { background: white; border-bottom: 1px solid #e5e7eb; padding: 16px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); position: sticky; top: 0; z-index: 50; }
        .navbar-inner { max-width: 900px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .logo { display: flex; align-items: center; gap: 12px; }
        .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #2563eb, #7c3aed); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .logo-title { font-weight: 700; font-size: 14px; color: #111827; }
        .logo-sub { font-size: 12px; color: #6b7280; }
        .btn-back { padding: 8px 16px; background: #eff6ff; color: #1e40af; border-radius: 8px; font-size: 13px; font-weight: 600; transition: 0.2s; }
        .btn-back:hover { background: #dbeafe; }

        /* MAIN */
        main { max-width: 900px; margin: 0 auto; padding: 24px 24px 60px; }

        /* BREADCRUMB */
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #6b7280; margin-bottom: 20px; flex-wrap: wrap; }
        .breadcrumb a { color: #6b7280; transition: 0.15s; }
        .breadcrumb a:hover { color: #2563eb; }
        .breadcrumb-sep { color: #d1d5db; }
        .breadcrumb-current { color: #111827; font-weight: 600; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ARTICLE */
        article { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); padding: 40px; border: 1px solid #f3f4f6; }

        .meta { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; }
        .badge-kategori { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .tanggal { font-size: 12.5px; color: #9ca3af; }
        .waktu-baca { font-size: 12.5px; color: #9ca3af; }

        h1 { font-size: 30px; font-weight: 800; color: #111827; margin-bottom: 24px; line-height: 1.3; }

        .konten { font-size: 15.5px; color: #374151; line-height: 1.85; white-space: pre-line; }

        .footer-article { margin-top: 36px; padding-top: 24px; border-top: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
        .penerbit { font-size: 13px; color: #6b7280; }
        .penerbit b { color: #374151; }

        /* SHARE */
        .share-group { display: flex; align-items: center; gap: 8px; }
        .share-label { font-size: 12px; color: #6b7280; font-weight: 600; }
        .share-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; text-decoration: none; }
        .share-wa { background: #dcfce7; color: #166534; }
        .share-wa:hover { background: #bbf7d0; }
        .share-copy { background: #eff6ff; color: #1e40af; }
        .share-copy:hover { background: #dbeafe; }

        /* CTA */
        .cta-box { margin-top: 28px; padding: 28px 24px; background: linear-gradient(135deg, #f0f9ff 0%, #ede9fe 100%); border-radius: 16px; text-align: center; border: 1px solid #e0e7ff; }
        .cta-box h4 { font-size: 16px; font-weight: 700; color: #1e3a8a; margin-bottom: 6px; }
        .cta-box p { font-size: 13px; color: #6b7280; margin-bottom: 16px; }
        .btn-cta { display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #2563eb, #7c3aed); color: white; border-radius: 10px; font-weight: 700; font-size: 13.5px; box-shadow: 0 4px 14px rgba(37,99,235,0.3); transition: 0.2s; }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }

        /* PENGUMUMAN LAINNYA */
        .lainnya-section { margin-top: 40px; }
        .lainnya-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .lainnya-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 12px rgba(139,92,246,0.25); }
        .lainnya-header h3 { font-size: 18px; font-weight: 700; color: #111827; }
        .lainnya-header p { font-size: 12.5px; color: #6b7280; margin-top: 2px; }

        .lainnya-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .lainnya-card { background: white; border-radius: 14px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); border: 1px solid #f3f4f6; transition: 0.25s; display: block; position: relative; overflow: hidden; }
        .lainnya-card::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; background: linear-gradient(180deg, #8b5cf6, #7c3aed); }
        .lainnya-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(139,92,246,0.12); border-color: #ddd6fe; }

        .lainnya-meta { display: flex; align-items: center; gap: 6px; margin-bottom: 10px; flex-wrap: wrap; }
        .lainnya-kategori { padding: 3px 10px; border-radius: 20px; font-size: 10.5px; font-weight: 700; }
        .lainnya-tanggal { font-size: 10.5px; color: #9ca3af; }
        .lainnya-card h4 { font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 8px; line-height: 1.4; }
        .lainnya-card p { font-size: 12px; color: #6b7280; line-height: 1.5; }
        .lainnya-more { font-size: 11.5px; color: #7c3aed; font-weight: 600; margin-top: 12px; }

        /* RESPONSIVE */
        @media (max-width: 640px) {
            article { padding: 24px; }
            h1 { font-size: 22px; }
            .konten { font-size: 14px; }
            .navbar { padding: 12px 16px; }
            .btn-back { padding: 7px 12px; font-size: 12px; }
            main { padding: 16px 16px 40px; }
            .breadcrumb-current { max-width: 120px; }
            .footer-article { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('landing') }}" class="logo">
                <div class="logo-icon">📄</div>
                <div>
                    <div class="logo-title">Pelayanan Surat Kelurahan</div>
                    <div class="logo-sub">{{ setting('nama_kelurahan', 'Kelurahan') }}</div>
                </div>
            </a>
            <a href="{{ route('landing') }}" class="btn-back">← Kembali</a>
        </div>
    </nav>

    {{-- MAIN --}}
    <main>

        {{-- BREADCRUMB --}}
        <nav class="breadcrumb">
            <a href="{{ route('landing') }}">🏠 Beranda</a>
            <span class="breadcrumb-sep">/</span>
            <a href="{{ route('landing') }}#pengumuman">📢 Pengumuman</a>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-current">{{ $pengumuman->judul }}</span>
        </nav>

        @php $badge = $pengumuman->badgeKategori(); @endphp

        {{-- ARTICLE --}}
        <article>

            <div class="meta">
                <span class="badge-kategori" style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }};">
                    {{ $badge['icon'] }} {{ $badge['label'] }}
                </span>
                <span class="tanggal">
                    📅 {{ $pengumuman->tanggal_mulai->translatedFormat('d F Y') }}
                    @if($pengumuman->tanggal_selesai)
                        — {{ $pengumuman->tanggal_selesai->translatedFormat('d F Y') }}
                    @endif
                </span>
                <span class="waktu-baca">
                    · ⏱️ {{ max(1, ceil(str_word_count(strip_tags($pengumuman->konten)) / 200)) }} menit baca
                </span>
            </div>

            <h1>{{ $pengumuman->judul }}</h1>

            <div class="konten">{{ $pengumuman->konten }}</div>

            <div class="footer-article">
                @if($pengumuman->creator)
                    <div class="penerbit">
                        ✍️ Diterbitkan oleh <b>{{ $pengumuman->creator->name }}</b>
                    </div>
                @endif

                {{-- SHARE --}}
                <div class="share-group">
                    <span class="share-label">Bagikan:</span>
                    <a href="https://wa.me/?text={{ urlencode($pengumuman->judul . ' - ' . url()->current()) }}"
                       target="_blank"
                       class="share-btn share-wa"
                       title="Bagikan ke WhatsApp">
                        💬 WhatsApp
                    </a>
                    <button type="button" class="share-btn share-copy" onclick="copyLink(this)" title="Salin tautan">
                        📋 Salin Link
                    </button>
                </div>
            </div>

        </article>

        {{-- CTA --}}
        <div class="cta-box">
            <h4>Butuh Ajukan Surat Keterangan?</h4>
            <p>Layanan surat online gratis, tanpa antre, kapan saja.</p>
            <a href="{{ route('landing') }}" class="btn-cta">🚀 Ajukan Sekarang</a>
        </div>

        {{-- PENGUMUMAN LAINNYA --}}
        @if(isset($lainnya) && $lainnya->count() > 0)
        <section class="lainnya-section">
            <div class="lainnya-header">
                <div class="lainnya-icon">📢</div>
                <div>
                    <h3>Pengumuman Lainnya</h3>
                    <p>Informasi terbaru dari kelurahan</p>
                </div>
            </div>

            <div class="lainnya-grid">
                @foreach($lainnya as $item)
                    @php $b = $item->badgeKategori(); @endphp
                    <a href="{{ route('pengumuman.show', $item->id) }}" class="lainnya-card">
                        <div class="lainnya-meta">
                            <span class="lainnya-kategori" style="background:{{ $b['bg'] }}; color:{{ $b['color'] }};">
                                {{ $b['icon'] }} {{ $b['label'] }}
                            </span>
                            <span class="lainnya-tanggal">
                                {{ $item->tanggal_mulai->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <h4>{{ $item->judul }}</h4>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->konten), 70) }}</p>
                        <div class="lainnya-more">Baca →</div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

    </main>

    {{-- SCRIPT COPY LINK --}}
    <script>
        function copyLink(btn) {
            const url = window.location.href;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    btn.innerHTML = '✅ Tersalin!';
                    setTimeout(() => { btn.innerHTML = '📋 Salin Link'; }, 2000);
                });
            } else {
                const textArea = document.createElement('textarea');
                textArea.value = url;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    btn.innerHTML = '✅ Tersalin!';
                    setTimeout(() => { btn.innerHTML = '📋 Salin Link'; }, 2000);
                } catch (e) {
                    alert('Gagal menyalin. Silakan copy URL di address bar.');
                }
                document.body.removeChild(textArea);
            }
        }
    </script>

</body>
</html>