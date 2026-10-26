<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 14px rgba(139,92,246,0.3);">
                    📢
                </div>
                <div>
                    <h2 style="font-size:22px; font-weight:700; color:#111827; margin:0;">
                        Kelola Pengumuman
                    </h2>
                    <p style="font-size:13px; color:#6b7280; margin:4px 0 0 0;">
                        Kelola informasi yang tampil di halaman beranda
                    </p>
                </div>
            </div>
            <a href="{{ route('kelurahan.pengumuman.create') }}"
               style="padding:10px 20px; background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:600; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 14px rgba(139,92,246,0.3);">
                ➕ Buat Pengumuman
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div style="max-width:1100px; margin:0 auto; padding:0 20px;">

            @if(session('success'))
                <div style="background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1px solid #6ee7b7; border-left:5px solid #10b981; border-radius:14px; padding:16px 22px; margin-bottom:20px; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:44px; height:44px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px;">✓</div>
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:14.5px; color:#065f46;">Berhasil</div>
                        <div style="font-size:12.5px; color:#059669; margin-top:2px;">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            <div style="background:white; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden;">

                <div style="padding:20px 24px; background:linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%); border-bottom:1px solid #e9d5ff; display:flex; align-items:center; gap:14px;">
                    <div style="flex-shrink:0; width:48px; height:48px; background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; box-shadow:0 4px 12px rgba(139,92,246,0.3);">📋</div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#5b21b6; margin:0;">
                            {{ $pengumuman->total() }} Pengumuman
                        </h3>
                        <p style="font-size:12px; color:#7c3aed; margin:3px 0 0 0;">
                            Daftar informasi yang sudah dibuat
                        </p>
                    </div>
                </div>

                @if($pengumuman->count() > 0)
                    <div style="padding:20px 24px; display:flex; flex-direction:column; gap:12px;">
                        @foreach($pengumuman as $item)
                            @php $badge = $item->badgeKategori(); @endphp
                            <div style="background:white; border:1px solid #f3f4f6; border-radius:14px; padding:18px 20px; transition:0.2s;"
                                 onmouseover="this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)'; this.style.transform='translateY(-2px)'"
                                 onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">

                                <div style="display:flex; justify-content:space-between; align-items:start; gap:16px; margin-bottom:10px;">
                                    <div style="flex:1; min-width:0;">
                                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px; flex-wrap:wrap;">
                                            <span style="padding:3px 10px; background:{{ $badge['bg'] }}; color:{{ $badge['color'] }}; border-radius:6px; font-size:11px; font-weight:700;">
                                                {{ $badge['icon'] }} {{ $badge['label'] }}
                                            </span>

                                            @if($item->is_published)
                                                <span style="padding:3px 10px; background:#d1fae5; color:#065f46; border-radius:6px; font-size:11px; font-weight:700;">
                                                    ✓ Published
                                                </span>
                                            @else
                                                <span style="padding:3px 10px; background:#fee2e2; color:#991b1b; border-radius:6px; font-size:11px; font-weight:700;">
                                                    ✕ Draft
                                                </span>
                                            @endif
                                        </div>

                                        <h4 style="font-size:15px; font-weight:700; color:#111827; margin:0 0 6px 0;">
                                            {{ $item->judul }}
                                        </h4>

                                        <p style="font-size:13px; color:#6b7280; margin:0; line-height:1.5;">
                                            {{ Str::limit(strip_tags($item->konten), 130) }}
                                        </p>

                                        <div style="font-size:11.5px; color:#9ca3af; margin-top:10px;">
                                            📅 {{ $item->tanggal_mulai->translatedFormat('d M Y') }}
                                            @if($item->tanggal_selesai)
                                                &nbsp;—&nbsp; {{ $item->tanggal_selesai->translatedFormat('d M Y') }}
                                            @endif
                                            @if($item->creator)
                                                &nbsp;•&nbsp; oleh {{ $item->creator->name }}
                                            @endif
                                        </div>
                                    </div>

                                    <div style="flex-shrink:0; display:flex; gap:6px;">
                                        <a href="{{ route('kelurahan.pengumuman.edit', $item->id) }}"
                                           style="padding:8px 14px; background:#eff6ff; color:#1e40af; border-radius:8px; text-decoration:none; font-size:12px; font-weight:700;">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('kelurahan.pengumuman.destroy', $item->id) }}"
                                              method="POST" onsubmit="return confirm('Hapus pengumuman ini?');"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="padding:8px 14px; background:#fef2f2; color:#991b1b; border:none; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($pengumuman->hasPages())
                        <div style="padding:16px 24px; border-top:1px solid #f3f4f6; background:#fafbfc;">
                            {{ $pengumuman->links() }}
                        </div>
                    @endif
                @else
                    <div style="padding:70px 20px; text-align:center;">
                        <div style="font-size:64px; margin-bottom:14px;">📢</div>
                        <div style="font-size:16px; font-weight:700; color:#374151; margin-bottom:6px;">
                            Belum Ada Pengumuman
                        </div>
                        <div style="font-size:13px; color:#6b7280; margin-bottom:22px;">
                            Buat pengumuman pertama untuk ditampilkan di beranda
                        </div>
                        <a href="{{ route('kelurahan.pengumuman.create') }}"
                           style="padding:12px 24px; background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color:white; border-radius:10px; text-decoration:none; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px;">
                            ➕ Buat Pengumuman
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>