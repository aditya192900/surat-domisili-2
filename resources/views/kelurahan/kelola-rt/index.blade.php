<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                    🏘️
                </div>
                <div>
                    <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                        Manajemen Pengurus RT
                    </h2>
                    <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                        Kelola akun pengurus RT di lingkungan {{ setting('nama_kelurahan', 'Kelurahan') }}
                    </p>
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('kelurahan.dashboard') }}"
                   style="padding:10px 20px; background:white; color:#374151; border:1.5px solid #e5e7eb; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px;">
                    📊 Dasbor
                </a>
                <a href="{{ route('kelurahan.kelola-rt.create') }}"
                   style="padding:10px 20px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                    ➕ Tambah Akun RT
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1280px; margin:0 auto; padding:0 20px;">

            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1px solid #6ee7b7; border-left:5px solid #10b981; border-radius:14px; padding:16px 22px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                        ✓
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:14.5px; color:#065f46;">Berhasil</div>
                        <div style="font-size:12.5px; color:#059669; margin-top:2px;">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            {{-- STATISTIK CARDS --}}
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:24px;">

                {{-- TOTAL PENGURUS --}}
                <div style="background:linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(37,99,235,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">👥 Total Pengurus</div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $stats['total'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Akun terdaftar</div>
                </div>

                {{-- TOTAL RT --}}
                <div style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(245,158,11,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">🏠 Total RT</div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $stats['total_rt'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">RT terdaftar</div>
                </div>

                {{-- TOTAL RW --}}
                <div style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(16,185,129,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">🏢 Total RW</div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $stats['total_rw'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">RW terdaftar</div>
                </div>

                {{-- BARU BULAN INI --}}
                <div style="background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border-radius:16px; padding:22px; box-shadow:0 4px 16px rgba(139,92,246,0.3); position:relative; overflow:hidden;">
                    <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255,255,255,0.08); border-radius:50%;"></div>
                    <div style="font-size:11px; opacity:0.9; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; position:relative; z-index:1;">✨ Baru Bulan Ini</div>
                    <div style="font-size:38px; font-weight:800; margin-top:10px; line-height:1; position:relative; z-index:1;">{{ $stats['baru_bulan_ini'] }}</div>
                    <div style="font-size:11.5px; opacity:0.85; margin-top:6px; position:relative; z-index:1;">Akun baru</div>
                </div>
            </div>

            {{-- DAFTAR PENGURUS RT --}}
            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                {{-- Header Section --}}
                <div style="padding:20px 24px; background:linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-bottom:1px solid #fde68a; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                        🏘️
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#78350f; margin:0;">
                            {{ $stats['total'] }} Pengurus RT Terdaftar
                        </h3>
                        <p style="font-size:12px; color:#92400e; margin:3px 0 0 0;">
                            Daftar akun pengurus RT yang dapat memverifikasi surat warga
                        </p>
                    </div>
                </div>

                {{-- List Data --}}
                @if($users->count() > 0)
                    <div style="padding:20px 24px; display:flex; flex-direction:column; gap:12px; background:linear-gradient(180deg, #fffbeb 0%, #ffffff 30%);">
                        @foreach($users as $user)
                            <div style="background:white; border:1px solid #f3f4f6; border-radius:14px; padding:16px 20px; display:flex; align-items:center; gap:16px; transition:0.2s;"
                                 onmouseover="this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)'; this.style.transform='translateY(-2px)'"
                                 onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">

                                {{-- Avatar --}}
                                <div style="flex-shrink:0; width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:700; box-shadow:0 4px 12px rgba(124,58,237,0.25);">
                                    {{ $user->inisial() }}
                                </div>

                                {{-- Info --}}
                                <div style="flex:1; min-width:0;">
                                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:5px;">
                                        <span style="padding:3px 10px; background:#dbeafe; color:#1e40af; border-radius:6px; font-size:11px; font-weight:700;">
                                            RT {{ $user->rt }} / RW {{ $user->rw }}
                                        </span>
                                        <span style="padding:3px 10px; background:#f3e8ff; color:#6b21a8; border-radius:6px; font-size:11px; font-weight:700; text-transform:uppercase;">
                                            {{ $user->role }}
                                        </span>
                                    </div>
                                    <div style="font-size:14px; font-weight:700; color:#111827; margin-bottom:3px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:12px; color:#6b7280; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        📧 {{ $user->email }}
                                        @if($user->nik)
                                            &nbsp;•&nbsp; 🆔 {{ $user->nik }}
                                        @endif
                                    </div>
                                </div>

                                {{-- Aksi --}}
                                <div style="flex-shrink:0; display:flex; gap:8px;">
                                    <a href="{{ route('kelurahan.kelola-rt.edit', $user->id) }}"
                                       style="padding:9px 16px; background:#eff6ff; color:#1e40af; border-radius:8px; text-decoration:none; font-size:12.5px; font-weight:700; display:inline-flex; align-items:center; gap:6px; transition:0.15s;"
                                       onmouseover="this.style.background='#dbeafe'"
                                       onmouseout="this.style.background='#eff6ff'">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('kelurahan.kelola-rt.destroy', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus akun ini?');"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="padding:9px 16px; background:#fef2f2; color:#991b1b; border:none; border-radius:8px; font-size:12.5px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:0.15s;"
                                                onmouseover="this.style.background='#fee2e2'"
                                                onmouseout="this.style.background='#fef2f2'">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($users->hasPages())
                        <div style="padding:16px 24px; border-top:1px solid #f3f4f6; background:#fafbfc;">
                            {{ $users->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div style="padding:70px 20px; text-align:center;">
                        <div style="font-size:64px; margin-bottom:14px;">🏘️</div>
                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                            Belum Ada Pengurus RT
                        </div>
                        <div style="font-size:13px; color:#6b7280; margin-bottom:22px;">
                            Mulai dengan menambahkan akun pengurus RT pertama
                        </div>
                        <a href="{{ route('kelurahan.kelola-rt.create') }}"
                           style="padding:12px 24px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(37,99,235,0.3);">
                            ➕ Tambah Akun RT
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>