<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php 
    $role = auth()->user()->role ?? 'warga';
    $appType = $role === 'kelurahan' ? 'kelurahan' : ($role === 'rt' ? 'rt' : 'warga');
@endphp
<link rel="manifest" href="{{ asset('manifest-' . $appType . '.json') }}">
<meta name="theme-color" content="{{ $appType === 'rt' ? '#059669' : ($appType === 'kelurahan' ? '#7c3aed' : '#2563eb') }}">
<link rel="apple-touch-icon" href="{{ asset('images/icon-' . $appType . '.png') }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- NOTIFIKASI GLOBAL --}}
        <x-notification />

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
            <!-- ========================================== -->
    <!-- GLOBAL LOADING STATE & UI POLISH -->
    <!-- ========================================== -->
    <style>
        /* 1. Mencegah layar geser ke kanan */
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* 2. Mempercantik Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* 3. Animasi Spinner */
        @keyframes spin { 
            to { transform: rotate(360deg); } 
        }
        
        /* 4. Responsive Button Group (Untuk HP) */
        @media (max-width: 640px) {
            .btn-group-mobile {
                display: flex;
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }
            .btn-group-mobile > * {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <!-- HTML Loading Overlay -->
    <div id="globalLoader" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.85); backdrop-filter:blur(5px); z-index:99999; align-items:center; justify-content:center; flex-direction:column; gap:20px;">
        <div style="width:50px; height:50px; border:5px solid #e5e7eb; border-top-color:#2563eb; border-radius:50%; animation:spin 1s linear infinite;"></div>
        <div style="font-family:'Figtree', sans-serif; font-weight:700; color:#1e40af; font-size:15px; letter-spacing:0.5px;">Memproses...</div>
    </div>

      <!-- Script Loading Overlay (Versi Diperbaiki) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('globalLoader');

            // 1. Munculkan loader HANYA saat form di-submit (Simpan/Kirim/Filter)
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Jika ada validasi JS yang mencegah submit, jangan tampilkan loader
                    if (e.defaultPrevented) return;
                    loader.style.display = 'flex';
                });
            });

            // 2. Khusus untuk link, kita HAPUS efek loader agar tidak berkedip saat pindah menu
            // (Biarkan browser menggunakan loading bawaan di tab browser)
            
            // 3. Sembunyikan loader jika user klik tombol "Back" di browser
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    loader.style.display = 'none';
                }
            });
        });
    </script>
        <!-- SERVICE WORKER PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('ServiceWorker registration successful');
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
    </body>
        
</html>