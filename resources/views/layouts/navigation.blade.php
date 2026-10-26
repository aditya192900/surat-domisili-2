<nav x-data="{ open: false }" style="background:white; border-bottom:1px solid #e5e7eb; box-shadow:0 1px 3px rgba(0,0,0,0.04); position:sticky; top:0; z-index:50; overflow:visible;">
    <div style="max-width:1400px; margin:0 auto; padding:0 20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; height:64px; gap:16px;">

            {{-- LEFT: Logo --}}
            <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none; flex-shrink:0;">
                <div style="width:38px; height:38px; background:linear-gradient(135deg, #2563eb, #7c3aed); border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; font-size:20px; box-shadow:0 4px 12px rgba(37,99,235,0.25);">
                    📄
                </div>
                <div style="display:flex; flex-direction:column; line-height:1.15;">
                    <span style="font-weight:700; font-size:13.5px; color:#111827; white-space:nowrap;">Pelayanan Surat</span>
                    <span style="font-weight:500; font-size:11px; color:#6b7280; white-space:nowrap;">{{ setting('nama_kelurahan', 'Kelurahan') }}</span>
                </div>
            </a>

            @php $role = auth()->user()->role; @endphp

            {{-- CENTER: Menu (Desktop) --}}
            <div style="display:none; flex:1; justify-content:center; align-items:center; gap:2px;" class="nav-desktop">

                @if ($role === 'kelurahan')
                    <a href="{{ route('kelurahan.dashboard') }}"
                       class="nav-item {{ request()->routeIs('kelurahan.dashboard') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">📊</span>
                        <span>Dasbor</span>
                    </a>

                    <a href="{{ route('kelurahan.surat.index') }}"
                       class="nav-item {{ request()->routeIs('kelurahan.surat.index') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">⚙️</span>
                        <span>Proses Surat</span>
                    </a>

                    <a href="{{ route('kelurahan.surat.riwayat') }}"
                       class="nav-item {{ request()->routeIs('kelurahan.surat.riwayat') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">📚</span>
                        <span>Riwayat</span>
                    </a>

                    {{-- Dropdown Kelola --}}
                    <div class="nav-dropdown" id="ddKelola">
                        <button type="button" onclick="toggleDropdown(event, 'ddKelola')"
                                class="nav-item {{ request()->routeIs('kelurahan.kelola-rt.*') || request()->routeIs('kelurahan.pengumuman.*') ? 'nav-item-active' : '' }}"
                                style="border:none; cursor:pointer; font-family:inherit;">
                            <span style="font-size:15px; line-height:1;">🗂️</span>
                            <span>Kelola</span>
                            <svg class="dropdown-arrow" style="width:12px; height:12px; margin-left:2px; transition:0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu"
                             style="display:none; position:absolute; top:calc(100% + 8px); left:0; min-width:220px; background:white; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1); padding:6px; z-index:60;">
                            <a href="{{ route('kelurahan.kelola-rt.index') }}"
                               style="display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:8px; text-decoration:none; color:#374151; font-size:13.5px; font-weight:500; transition:0.15s;"
                               onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                                <span style="font-size:17px;">🏘️</span>
                                <div>
                                    <div style="font-weight:600;">Kelola RT</div>
                                    <div style="font-size:11px; color:#9ca3af;">Manajemen akun RT</div>
                                </div>
                            </a>
                            <a href="{{ route('kelurahan.pengumuman.index') }}"
                               style="display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:8px; text-decoration:none; color:#374151; font-size:13.5px; font-weight:500; transition:0.15s;"
                               onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                                <span style="font-size:17px;">📢</span>
                                <div>
                                    <div style="font-weight:600;">Pengumuman</div>
                                    <div style="font-size:11px; color:#9ca3af;">Info untuk warga</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Dropdown Sistem --}}
                    <div class="nav-dropdown" id="ddSistem">
                        <button type="button" onclick="toggleDropdown(event, 'ddSistem')"
                                class="nav-item {{ request()->routeIs('kelurahan.backup.*') || request()->routeIs('admin.logs') || request()->routeIs('kelurahan.pengaturan.*') ? 'nav-item-active' : '' }}"
                                style="border:none; cursor:pointer; font-family:inherit;">
                            <span style="font-size:15px; line-height:1;">⚙️</span>
                            <span>Sistem</span>
                            <svg class="dropdown-arrow" style="width:12px; height:12px; margin-left:2px; transition:0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu"
                             style="display:none; position:absolute; top:calc(100% + 8px); right:0; min-width:220px; background:white; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1); padding:6px; z-index:60;">
                            <a href="{{ route('kelurahan.backup.index') }}"
                               style="display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:8px; text-decoration:none; color:#374151; font-size:13.5px; font-weight:500; transition:0.15s;"
                               onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                                <span style="font-size:17px;">💾</span>
                                <div>
                                    <div style="font-weight:600;">Backup Database</div>
                                    <div style="font-size:11px; color:#9ca3af;">Cadangan data</div>
                                </div>
                            </a>
                            <a href="{{ route('admin.logs') }}"
                               style="display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:8px; text-decoration:none; color:#374151; font-size:13.5px; font-weight:500; transition:0.15s;"
                               onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                                <span style="font-size:17px;">📜</span>
                                <div>
                                    <div style="font-weight:600;">Log Aktivitas</div>
                                    <div style="font-size:11px; color:#9ca3af;">Jejak aktivitas user</div>
                                </div>
                            </a>
                            <div style="height:1px; background:#f3f4f6; margin:6px 8px;"></div>
                            <a href="{{ route('kelurahan.pengaturan.index') }}"
                               style="display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:8px; text-decoration:none; color:#374151; font-size:13.5px; font-weight:500; transition:0.15s;"
                               onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                                <span style="font-size:17px;">🔧</span>
                                <div>
                                    <div style="font-weight:600;">Pengaturan</div>
                                    <div style="font-size:11px; color:#9ca3af;">Profil kelurahan</div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($role === 'rt')
                    <a href="{{ route('rt.surat.index') }}"
                       class="nav-item {{ request()->routeIs('rt.surat.index') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">✅</span>
                        <span>Verifikasi</span>
                    </a>
                    <a href="{{ route('rt.surat.riwayat') }}"
                       class="nav-item {{ request()->routeIs('rt.surat.riwayat') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">📚</span>
                        <span>Riwayat</span>
                    </a>
                    <a href="{{ route('rt.data-warga.index') }}"
                       class="nav-item {{ request()->routeIs('rt.data-warga.*') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">👥</span>
                        <span>Data Warga</span>
                    </a>
                @endif

                @if ($role === 'warga')
                    <a href="{{ route('warga.dashboard') }}"
                       class="nav-item {{ request()->routeIs('warga.dashboard') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">🏠</span>
                        <span>Dasbor</span>
                    </a>
                    <a href="{{ route('warga.surat.index') }}"
                       class="nav-item {{ request()->routeIs('warga.surat.index') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">📋</span>
                        <span>Surat Saya</span>
                    </a>
                    <a href="{{ route('warga.surat.create') }}"
                       class="nav-item {{ request()->routeIs('warga.surat.create') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px; line-height:1;">➕</span>
                        <span>Ajukan Surat</span>
                    </a>
                @endif

                @if ($role === 'admin')
                    <a href="{{ route('admin.logs') }}"
                       class="nav-item {{ request()->routeIs('admin.logs') ? 'nav-item-active' : '' }}">
                        <span style="font-size:15px;">📜</span>
                        <span>Log Aktivitas</span>
                    </a>
                @endif

            </div>

            {{-- RIGHT: Notif + User --}}
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">

                @auth
                    @if (auth()->user()->role === 'warga' && isset($jumlahNotif) && $jumlahNotif > 0)
                        <a href="{{ route('warga.surat.index', ['kategori' => 'proses']) }}"
                           title="{{ $jumlahNotif }} surat siap diunduh"
                           style="position:relative; display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border:1.5px solid #fcd34d; text-decoration:none;">
                            <span style="font-size:18px;">🔔</span>
                            <span style="position:absolute; top:-5px; right:-5px; min-width:20px; height:20px; padding:0 5px; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color:white; border-radius:10px; font-size:10.5px; font-weight:800; display:flex; align-items:center; justify-content:center; border:2px solid white;">
                                {{ $jumlahNotif }}
                            </span>
                        </a>
                    @endif
                @endauth

                {{-- User Dropdown --}}
                <div class="nav-dropdown" id="ddUser">
                    <button type="button" onclick="toggleDropdown(event, 'ddUser')"
                            style="display:inline-flex; align-items:center; gap:8px; padding:5px 10px 5px 5px; background:white; border:1.5px solid #e5e7eb; border-radius:10px; cursor:pointer; font-family:inherit;">
                        @if (Auth::user()->foto)
                            <img src="{{ route('profile.foto', Auth::user()) }}" alt="Avatar"
                                 style="width:32px; height:32px; border-radius:8px; object-fit:cover;">
                        @else
                            <div style="width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white; display:flex; align-items:center; justify-content:center; font-size:12.5px; font-weight:700;">
                                {{ Auth::user()->inisial() }}
                            </div>
                        @endif

                        <div style="max-width:130px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:13px; font-weight:600; color:#374151; text-align:left;">
                            {{ Auth::user()->name }}
                        </div>

                        <svg class="dropdown-arrow" style="width:14px; height:14px; color:#9ca3af; transition:0.2s;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu"
                         style="display:none; position:absolute; top:calc(100% + 8px); right:0; min-width:250px; background:white; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1); padding:6px; z-index:60;">
                        <div style="padding:10px 14px; border-bottom:1px solid #f3f4f6;">
                            <div style="font-weight:700; font-size:13px; color:#111827;">{{ Auth::user()->name }}</div>
                            <div style="font-size:11px; color:#6b7280; margin-top:2px;">{{ Auth::user()->email }}</div>
                            <div style="margin-top:6px;">
                                <span style="padding:2px 8px; background:#e0e7ff; color:#3730a3; border-radius:4px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                           style="display:flex; align-items:center; gap:8px; padding:10px 14px; border-radius:8px; text-decoration:none; color:#374151; font-size:13.5px; font-weight:500;"
                           onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                            <span>👤</span> Profil Saya
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               style="display:flex; align-items:center; gap:8px; padding:10px 14px; border-radius:8px; text-decoration:none; color:#dc2626; font-size:13.5px; font-weight:500;"
                               onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                                <span>🚪</span> Keluar
                            </a>
                        </form>
                    </div>
                </div>

                {{-- Hamburger (Mobile) --}}
                <button @click="open = ! open" class="nav-hamburger"
                        style="display:none; padding:8px; background:white; border:1.5px solid #e5e7eb; border-radius:10px; cursor:pointer;">
                    <svg style="width:20px; height:20px; color:#374151;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{'block': open, 'hidden': ! open}" style="display:none;" class="nav-mobile">
        <div style="padding:12px 20px; display:flex; flex-direction:column; gap:4px;">

            @if ($role === 'kelurahan')
                <a href="{{ route('kelurahan.dashboard') }}" class="nav-mobile-item {{ request()->routeIs('kelurahan.dashboard') ? 'active' : '' }}">
                    <span style="font-size:17px;">📊</span> Dasbor
                </a>
                <a href="{{ route('kelurahan.surat.index') }}" class="nav-mobile-item {{ request()->routeIs('kelurahan.surat.index') ? 'active' : '' }}">
                    <span style="font-size:17px;">⚙️</span> Proses Surat
                </a>
                <a href="{{ route('kelurahan.surat.riwayat') }}" class="nav-mobile-item {{ request()->routeIs('kelurahan.surat.riwayat') ? 'active' : '' }}">
                    <span style="font-size:17px;">📚</span> Riwayat
                </a>
                <div style="padding:8px 14px 4px; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.5px;">Kelola</div>
                <a href="{{ route('kelurahan.kelola-rt.index') }}" class="nav-mobile-item {{ request()->routeIs('kelurahan.kelola-rt.*') ? 'active' : '' }}">
                    <span style="font-size:17px;">🏘️</span> Kelola RT
                </a>
                <a href="{{ route('kelurahan.pengumuman.index') }}" class="nav-mobile-item {{ request()->routeIs('kelurahan.pengumuman.*') ? 'active' : '' }}">
                    <span style="font-size:17px;">📢</span> Pengumuman
                </a>
                <div style="padding:8px 14px 4px; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.5px;">Sistem</div>
                <a href="{{ route('kelurahan.backup.index') }}" class="nav-mobile-item {{ request()->routeIs('kelurahan.backup.*') ? 'active' : '' }}">
                    <span style="font-size:17px;">💾</span> Backup
                </a>
                <a href="{{ route('admin.logs') }}" class="nav-mobile-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                    <span style="font-size:17px;">📜</span> Log Aktivitas
                </a>
                <a href="{{ route('kelurahan.pengaturan.index') }}" class="nav-mobile-item {{ request()->routeIs('kelurahan.pengaturan.*') ? 'active' : '' }}">
                    <span style="font-size:17px;">🔧</span> Pengaturan
                </a>
            @endif

            @if ($role === 'rt')
                <a href="{{ route('rt.surat.index') }}" class="nav-mobile-item {{ request()->routeIs('rt.surat.index') ? 'active' : '' }}">
                    <span style="font-size:17px;">✅</span> Verifikasi
                </a>
                <a href="{{ route('rt.surat.riwayat') }}" class="nav-mobile-item {{ request()->routeIs('rt.surat.riwayat') ? 'active' : '' }}">
                    <span style="font-size:17px;">📚</span> Riwayat
                </a>
                <a href="{{ route('rt.data-warga.index') }}" class="nav-mobile-item {{ request()->routeIs('rt.data-warga.*') ? 'active' : '' }}">
                    <span style="font-size:17px;">👥</span> Data Warga
                </a>
            @endif

            @if ($role === 'warga')
                <a href="{{ route('warga.dashboard') }}" class="nav-mobile-item {{ request()->routeIs('warga.dashboard') ? 'active' : '' }}">
                    <span style="font-size:17px;">🏠</span> Dasbor
                </a>
                <a href="{{ route('warga.surat.index') }}" class="nav-mobile-item {{ request()->routeIs('warga.surat.index') ? 'active' : '' }}">
                    <span style="font-size:17px;">📋</span> Surat Saya
                </a>
                <a href="{{ route('warga.surat.create') }}" class="nav-mobile-item {{ request()->routeIs('warga.surat.create') ? 'active' : '' }}">
                    <span style="font-size:17px;">➕</span> Ajukan Surat
                </a>
            @endif

        </div>
    </div>
</nav>

<style>
    .nav-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        transition: 0.15s;
        color: #4b5563;
        background: transparent;
    }
    .nav-item:hover {
        background: #f3f4f6;
        color: #1e40af;
    }
    .nav-item-active {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%) !important;
        color: #1e40af !important;
        box-shadow: 0 2px 8px rgba(37,99,235,0.12);
    }

    .nav-dropdown {
        position: relative;
        display: inline-block;
    }

    .nav-mobile-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        color: #4b5563;
        transition: 0.15s;
    }
    .nav-mobile-item.active {
        background: #eff6ff;
        color: #1e40af;
    }

    @media (min-width: 1024px) {
        .nav-desktop { display: flex !important; }
        .nav-hamburger { display: none !important; }
        .nav-mobile { display: none !important; }
    }

    @media (max-width: 1023px) {
        .nav-desktop { display: none !important; }
        .nav-hamburger { display: inline-flex !important; }
        .nav-mobile { display: block; }
        .nav-mobile.hidden { display: none; }
    }

        /* Hapus overflow di nav biar dropdown gak terpotong */
    body { overflow-x: hidden; }
</style>

<script>
    function toggleDropdown(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(id);
        if (!dropdown) return;
        const menu = dropdown.querySelector('.dropdown-menu');
        const arrow = dropdown.querySelector('.dropdown-arrow');

        const isOpen = menu.style.display === 'block';

        // Tutup semua dropdown lain
        document.querySelectorAll('.nav-dropdown').forEach(function (el) {
            const elMenu = el.querySelector('.dropdown-menu');
            const elArrow = el.querySelector('.dropdown-arrow');
            if (elMenu) elMenu.style.display = 'none';
            if (elArrow) elArrow.style.transform = 'rotate(0deg)';
        });

        // Buka dropdown yang diklik (kalau sebelumnya tertutup)
        if (!isOpen) {
            menu.style.display = 'block';
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        }
    }

    // Tutup semua dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.nav-dropdown')) {
            document.querySelectorAll('.nav-dropdown .dropdown-menu').forEach(function (menu) {
                menu.style.display = 'none';
            });
            document.querySelectorAll('.dropdown-arrow').forEach(function (arrow) {
                arrow.style.transform = 'rotate(0deg)';
            });
        }
    });
</script>