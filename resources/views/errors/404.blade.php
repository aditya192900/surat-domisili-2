<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Figtree', sans-serif; background: #f9fafb; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; text-align: center; padding: 20px; box-sizing: border-box; }
        .container { max-width: 500px; width: 100%; padding: 40px 20px; background: white; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .icon { font-size: 80px; margin-bottom: 20px; animation: float 3s ease-in-out infinite; }
        h1 { font-size: 80px; font-weight: 800; color: #1e40af; margin: 0; line-height: 1; }
        h2 { font-size: 22px; color: #111827; margin: 10px 0; }
        p { color: #6b7280; font-size: 15px; line-height: 1.6; margin-bottom: 30px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 14px rgba(37,99,235,0.3); transition: 0.2s; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔍</div>
        <h1>404</h1>
        <h2>Halaman Tidak Ditemukan</h2>
        <p>Maaf, halaman atau surat yang Anda cari tidak ada. Mungkin link sudah kedaluwarsa atau salah ketik.</p>
        <a href="{{ url('/') }}" class="btn">← Kembali ke Beranda</a>
    </div>
</body>
</html>