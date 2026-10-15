<?php

namespace App\Http\Controllers;

use App\Models\DataWargaRt;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class DataWargaRtController extends Controller
{
    // ==========================================
    // DAFTAR WARGA RT
    // ==========================================
    public function index(Request $request)
    {
        $user = auth()->user();
        $rt = $user->rt;

        $query = DataWargaRt::where('rt', $rt)->latest();

        // Filter pencarian
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('nik', 'like', "%{$cari}%")
                  ->orWhere('no_hp', 'like', "%{$cari}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(15)->withQueryString();

        $statistik = [
            'total'        => DataWargaRt::where('rt', $rt)->count(),
            'belum_daftar' => DataWargaRt::where('rt', $rt)->where('status', 'belum_daftar')->count(),
            'sudah_daftar' => DataWargaRt::where('rt', $rt)->where('status', 'sudah_daftar')->count(),
        ];

        return view('rt.data-warga.index', compact('data', 'statistik'));
    }

    // ==========================================
    // FORM TAMBAH WARGA
    // ==========================================
    public function create()
    {
        return view('rt.data-warga.create');
    }

    // ==========================================
    // SIMPAN WARGA BARU
    // ==========================================
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nik'            => 'required|string|size:16|unique:data_warga_rt,nik',
            'nama'           => 'required|string|max:255',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date|before:today',
            'alamat'         => 'required|string|max:500',
            'no_hp'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
        ], [
            'nik.required'           => 'NIK wajib diisi.',
            'nik.size'               => 'NIK harus 16 digit.',
            'nik.unique'             => 'NIK sudah terdaftar di sistem.',
            'nama.required'          => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
        ]);

        $warga = DataWargaRt::create([
            'nik'            => $request->nik,
            'nama'           => $request->nama,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'alamat'         => $request->alamat,
            'rt'             => $user->rt,
            'rw'             => $user->rw,
            'no_hp'          => $request->no_hp,
            'email'          => $request->email,
            'status'         => 'belum_daftar',
            'created_by'     => $user->id,
        ]);

        ActivityLogger::log(
            'tambah_data_warga',
            "RT {$user->rt} menambahkan data warga: {$warga->nama} (NIK: {$warga->nik})",
            null,
            null,
            ['nama' => $warga->nama, 'nik' => $warga->nik]
        );

        return redirect()->route('rt.data-warga.index')
            ->with('success', "Data warga {$warga->nama} berhasil ditambahkan.");
    }

    // ==========================================
    // FORM EDIT WARGA
    // ==========================================
    public function edit(DataWargaRt $dataWarga)
    {
        $user = auth()->user();

        // Cek hak akses: RT hanya bisa edit warga di RT-nya
        if ($dataWarga->rt !== $user->rt) {
            abort(403, 'Anda tidak berhak mengedit data warga ini.');
        }

        return view('rt.data-warga.edit', compact('dataWarga'));
    }

    // ==========================================
    // UPDATE WARGA
    // ==========================================
    public function update(Request $request, DataWargaRt $dataWarga)
    {
        $user = auth()->user();

        if ($dataWarga->rt !== $user->rt) {
            abort(403, 'Anda tidak berhak mengedit data warga ini.');
        }

        // NIK tidak bisa diubah kalau sudah daftar akun
        $nikRule = $dataWarga->status === 'sudah_daftar'
            ? 'required|string|size:16'
            : 'required|string|size:16|unique:data_warga_rt,nik,' . $dataWarga->id;

        $request->validate([
            'nik'            => $nikRule,
            'nama'           => 'required|string|max:255',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date|before:today',
            'alamat'         => 'required|string|max:500',
            'no_hp'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
        ], [
            'nik.required'           => 'NIK wajib diisi.',
            'nik.size'               => 'NIK harus 16 digit.',
            'nik.unique'             => 'NIK sudah terdaftar.',
            'nama.required'          => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
        ]);

        $dataLama = $dataWarga->only(['nik', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat']);

        $dataWarga->update([
            'nik'            => $request->nik,
            'nama'           => $request->nama,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'alamat'         => $request->alamat,
            'no_hp'          => $request->no_hp,
            'email'          => $request->email,
        ]);

        ActivityLogger::log(
            'edit_data_warga',
            "RT {$user->rt} mengedit data warga: {$dataWarga->nama}",
            null,
            $dataLama,
            $dataWarga->only(['nik', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat'])
        );

        return redirect()->route('rt.data-warga.index')
            ->with('success', "Data warga {$dataWarga->nama} berhasil diperbarui.");
    }

    // ==========================================
    // HAPUS WARGA
    // ==========================================
    public function destroy(DataWargaRt $dataWarga)
    {
        $user = auth()->user();

        if ($dataWarga->rt !== $user->rt) {
            abort(403, 'Anda tidak berhak menghapus data warga ini.');
        }

        // Kalau warga sudah daftar akun, tidak boleh dihapus
        if ($dataWarga->status === 'sudah_daftar') {
            return back()->with('error', 'Warga sudah mendaftar akun, data tidak bisa dihapus. Nonaktifkan saja jika perlu.');
        }

        $nama = $dataWarga->nama;
        $nik = $dataWarga->nik;

        $dataWarga->delete();

        ActivityLogger::log(
            'hapus_data_warga',
            "RT {$user->rt} menghapus data warga: {$nama} (NIK: {$nik})",
            null,
            ['nama' => $nama, 'nik' => $nik],
            null
        );

        return back()->with('success', "Data warga {$nama} berhasil dihapus.");
    }

    // ==========================================
    // DETAIL WARGA
    // ==========================================
    public function show(DataWargaRt $dataWarga)
    {
        $user = auth()->user();

        if ($dataWarga->rt !== $user->rt) {
            abort(403, 'Anda tidak berhak melihat data warga ini.');
        }

        return view('rt.data-warga.show', compact('dataWarga'));
    }
}