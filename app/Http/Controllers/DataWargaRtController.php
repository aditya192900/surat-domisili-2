<?php

namespace App\Http\Controllers;

use App\Models\DataWargaRt;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('nik', 'like', "%{$cari}%")
                  ->orWhere('no_hp', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(15)->withQueryString();

        // 🚀 Optimasi: 1 query GROUP BY
        $perStatus = DB::table('data_warga_rt')
            ->where('rt', $rt)
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        $statistik = [
            'total'        => array_sum($perStatus),
            'belum_daftar' => $perStatus['belum_daftar'] ?? 0,
            'sudah_daftar' => $perStatus['sudah_daftar'] ?? 0,
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

        $akunWarga = User::where('nik', $dataWarga->nik)->first();

        return view('rt.data-warga.show', compact('dataWarga', 'akunWarga'));
    }

    // ==========================================
    // RESET PASSWORD WARGA
    // ==========================================
    public function resetPassword(Request $request, DataWargaRt $dataWarga)
    {
        $user = auth()->user();

        if ($dataWarga->rt !== $user->rt) {
            abort(403, 'Anda tidak berhak mereset password warga ini.');
        }

        if ($dataWarga->status !== 'sudah_daftar') {
            return back()->with('error', 'Warga belum mendaftar akun, tidak ada password untuk direset.');
        }

        $akunWarga = User::where('nik', $dataWarga->nik)->first();

        if (!$akunWarga) {
            return back()->with('error', 'Akun warga tidak ditemukan di sistem. Hubungi admin kelurahan.');
        }

        $passwordBaru = $request->filled('password_baru')
            ? trim($request->password_baru)
            : $this->generatePassword($dataWarga->nama);

        if (strlen($passwordBaru) < 8) {
            return back()->with('error', 'Password minimal 8 karakter.');
        }

        $akunWarga->update([
            'password' => Hash::make($passwordBaru),
        ]);

        ActivityLogger::log(
            'reset_password_warga',
            "RT {$user->rt} mereset password akun warga: {$dataWarga->nama} (NIK: {$dataWarga->nik})",
            null,
            null,
            ['nama' => $dataWarga->nama, 'nik' => $dataWarga->nik]
        );

        return back()
            ->with('success', "Password {$dataWarga->nama} berhasil direset.")
            ->with('password_baru', $passwordBaru)
            ->with('warga_nama', $dataWarga->nama);
    }

    private function generatePassword(string $nama): string
    {
        $kataPertama = explode(' ', trim($nama))[0];
        $kataPertama = preg_replace('/[^A-Za-z]/', '', $kataPertama);
        $kataPertama = ucfirst(strtolower($kataPertama));
        $tahun       = date('Y');
        $randomNum   = rand(10, 99);

        return $kataPertama . '@' . $tahun . $randomNum;
    }
}