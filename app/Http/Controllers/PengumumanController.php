<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    // Daftar pengumuman (admin)
    public function index()
    {
        $pengumuman = Pengumuman::latest()->paginate(10);
        return view('kelurahan.pengumuman.index', compact('pengumuman'));
    }

    // Form tambah
    public function create()
    {
        return view('kelurahan.pengumuman.create');
    }

    // Simpan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'            => 'required|string|max:255',
            'konten'           => 'required|string',
            'kategori'         => 'required|in:info,penting,kegiatan',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_published'     => 'boolean',
        ]);

        $validated['created_by']   = auth()->id();
        $validated['is_published'] = $request->has('is_published');

        Pengumuman::create($validated);

        return redirect()
            ->route('kelurahan.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    // Form edit
    public function edit(Pengumuman $pengumuman)
    {
        return view('kelurahan.pengumuman.edit', compact('pengumuman'));
    }

    // Update
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul'            => 'required|string|max:255',
            'konten'           => 'required|string',
            'kategori'         => 'required|in:info,penting,kegiatan',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_published'     => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $pengumuman->update($validated);

        return redirect()
            ->route('kelurahan.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    // Hapus
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus!');
    }

    // Tampilan publik untuk warga (detail) + pengumuman lainnya
    public function show(Pengumuman $pengumuman)
    {
        // Kalau tidak published, hanya bisa diakses admin
        if (!$pengumuman->is_published && (!auth()->check() || auth()->user()->role !== 'kelurahan')) {
            abort(404);
        }

        // Ambil 3 pengumuman lain untuk section "Pengumuman Lainnya"
        $lainnya = Pengumuman::published()
            ->aktif()
            ->where('id', '!=', $pengumuman->id)
            ->latest('tanggal_mulai')
            ->take(3)
            ->get();

        return view('pengumuman.show', compact('pengumuman', 'lainnya'));
    }
}