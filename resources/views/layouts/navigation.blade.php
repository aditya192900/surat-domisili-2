<nav x-data="{ open: false }" class="bg-white border-b border-gray-100" style="box-shadow:0 1px 3px rgba(0,0,0,0.04);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT: Logo + Menu --}}
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
                        <div style="width:38px; height:38px; background:linear-gradient(135deg, #2563eb, #7c3aed); border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; font-size:20px; box-shadow:0 4px 12px rgba(37,99,235,0.25);">
                            📄
                        </div>
                        <div style="display:flex; flex-direction:column; line-height:1.1;">
                            <span style="font-weight:700; font-size:14px; color:#111827;">Pelayanan Surat</span>
                            <span style="font-weight:500; font-size:11px; color:#6b7280;">{{ setting('nama_kelurahan', 'Kelurahan') }}</span>
                        </div>
                    </a>
                </div>

                @php $role = auth()->user()->role; @endphp

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    {{-- MENU UNTUK WARGA --}}
                    @if ($role === 'warga')
                        <x-nav-link :href="route('warga.dashboard')" :active="request()->routeIs('warga.dashboard')">
                            🏠 Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('warga.surat.index')" :active="request()->routeIs('warga.surat.*') && !request()->routeIs('warga.surat.create') && !request()->routeIs('warga.dashboard')">
                            📋 Surat Saya
                        </x-nav-link>
                        <x-nav-link :href="route('warga.surat.create')" :active="request()->routeIs('warga.surat.create')">
                            ➕ Ajukan Surat
                        </x-nav-link>
                    @endif

                    {{-- MENU UNTUK RT --}}
                    @if ($role === 'rt')
                        <x-nav-link :href="route('rt.surat.index')" :active="request()->routeIs('rt.surat.index')">
                            ✅ Verifikasi
                        </x-nav-link>
                        <x-nav-link :href="route('rt.surat.riwayat')" :active="request()->routeIs('rt.surat.riwayat')">
                            📚 Riwayat
                        </x-nav-link>
                        <x-nav-link :href="route('rt.data-warga.index')" :active="request()->routeIs('rt.data-warga.*')">
                            👥 Data Warga
                        </x-nav-link>
                    @endif

                    {{-- MENU UNTUK KELURAHAN --}}
                    @if ($role === 'kelurahan')
                        <x-nav-link :href="route('kelurahan.dashboard')" :active="request()->routeIs('kelurahan.dashboard')">
                            📊 Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('kelurahan.surat.index')" :active="request()->routeIs('kelurahan.surat.index')">
                            ⚙️ Proses Surat
                        </x-nav-link>
                        <x-nav-link :href="route('kelurahan.surat.riwayat')" :active="request()->routeIs('kelurahan.surat.riwayat')">
                            📚 Riwayat
                        </x-nav-link>
                        <x-nav-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                            📜 Log Aktivitas
                        </x-nav-link>
                        <x-nav-link :href="route('kelurahan.pengaturan.index')" :active="request()->routeIs('kelurahan.pengaturan.*')">
                            ⚙️ Pengaturan
                        </x-nav-link>
                    @endif

                    {{-- MENU UNTUK ADMIN --}}
                    @if ($role === 'admin')
                        <x-nav-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                            📜 Log Aktivitas
                        </x-nav-link>
                    @endif

                </div>
            </div>

            {{-- RIGHT: User Dropdown --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{-- Avatar --}}
                            @if (Auth::user()->foto)
                                <img src="{{ route('profile.foto', Auth::user()) }}"
                                     alt="Avatar"
                                     style="width:34px; height:34px; border-radius:50%; object-fit:cover; margin-right:8px; border:2px solid #e5e7eb;">
                            @else
                                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white;
                                            display:flex; align-items:center; justify-content:center; font-size:13px;
                                            font-weight:700; margin-right:8px;">
                                    {{ Auth::user()->inisial() }}
                                </div>
                            @endif

                            <div style="max-width:130px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ Auth::user()->name }}
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Info user --}}
                        <div style="padding:10px 16px; border-bottom:1px solid #f3f4f6;">
                            <div style="font-weight:600; font-size:13px; color:#111827;">{{ Auth::user()->name }}</div>
                            <div style="font-size:11px; color:#6b7280; margin-top:2px;">{{ Auth::user()->email }}</div>
                            <div style="margin-top:6px;">
                                <span style="padding:2px 8px; background:#e0e7ff; color:#3730a3; border-radius:4px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" style="display:flex; align-items:center; gap:8px;">
                            <span>👤</span> Profil Saya
                        </x-dropdown-link>

                        @if (Auth::user()->role === 'kelurahan')
                            <x-dropdown-link :href="route('kelurahan.pengaturan.index')" style="display:flex; align-items:center; gap:8px;">
                                <span>⚙️</span> Pengaturan Kelurahan
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    style="display:flex; align-items:center; gap:8px; color:#dc2626;">
                                <span>🚪</span> Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <div class="pt-2 pb-3 space-y-1">

            @if ($role === 'warga')
                <x-responsive-nav-link :href="route('warga.dashboard')" :active="request()->routeIs('warga.dashboard')">
                    🏠 Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('warga.surat.index')" :active="request()->routeIs('warga.surat.*') && !request()->routeIs('warga.surat.create')">
                    📋 Surat Saya
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('warga.surat.create')" :active="request()->routeIs('warga.surat.create')">
                    ➕ Ajukan Surat
                </x-responsive-nav-link>
            @endif

            @if ($role === 'rt')
                <x-responsive-nav-link :href="route('rt.surat.index')" :active="request()->routeIs('rt.surat.index')">
                    ✅ Verifikasi
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rt.surat.riwayat')" :active="request()->routeIs('rt.surat.riwayat')">
                    📚 Riwayat
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('rt.data-warga.index')" :active="request()->routeIs('rt.data-warga.*')">
                    👥 Data Warga
                </x-responsive-nav-link>
            @endif

            @if ($role === 'kelurahan')
                <x-responsive-nav-link :href="route('kelurahan.dashboard')" :active="request()->routeIs('kelurahan.dashboard')">
                    📊 Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kelurahan.surat.index')" :active="request()->routeIs('kelurahan.surat.index')">
                    ⚙️ Proses Surat
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kelurahan.surat.riwayat')" :active="request()->routeIs('kelurahan.surat.riwayat')">
                    📚 Riwayat
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                    📜 Log Aktivitas
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kelurahan.pengaturan.index')" :active="request()->routeIs('kelurahan.pengaturan.*')">
                    ⚙️ Pengaturan
                </x-responsive-nav-link>
            @endif

            @if ($role === 'admin')
                <x-responsive-nav-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                    📜 Log Aktivitas
                </x-responsive-nav-link>
            @endif

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div style="display:flex; align-items:center; gap:10px;">
                    @if (Auth::user()->foto)
                        <img src="{{ route('profile.foto', Auth::user()) }}"
                             alt="Avatar"
                             style="width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb;">
                    @else
                        <div style="width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg, #2563eb, #7c3aed); color:white;
                                    display:flex; align-items:center; justify-content:center; font-size:15px; font-weight:700;">
                            {{ Auth::user()->inisial() }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    👤 Profil Saya
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        🚪 Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>