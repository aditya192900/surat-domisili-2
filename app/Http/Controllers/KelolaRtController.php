<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaRtController extends Controller
{
    /**
     * Menampilkan daftar akun RT.
     */
    public function index()
    {
        $query = User::where('role', 'rt');

        // Ambil data untuk statistik
        $users = (clone $query)->latest()->paginate(10);

        $stats = [
            'total'          => (clone $query)->count(),
            'total_rt'       => (clone $query)->distinct('rt')->count('rt'),
            'total_rw'       => (clone $query)->distinct('rw')->count('rw'),
            'baru_bulan_ini' => (clone $query)
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count(),
        ];

        return view('kelurahan.kelola-rt.index', compact('users', 'stats'));
    }

    /**
     * Menampilkan form tambah akun RT.
     */
    public function create()
    {
        return view('kelurahan.kelola-rt.create');
    }

    /**
     * Menyimpan akun RT baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|max:16|unique:users,nik',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'rt'       => 'required|string|max:5',
            'rw'       => 'required|string|max:5',
        ], [
            'nik.required'      => 'NIK wajib diisi.',
            'nik.max'           => 'NIK maksimal 16 karakter.',
            'nik.unique'        => 'NIK sudah terdaftar.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.min'      => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name'     => $request->name,
            'nik'      => $request->nik,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'rt'       => $request->rt,
            'rw'       => $request->rw,
            'role'     => 'rt',
        ]);

        return redirect()
            ->route('kelurahan.kelola-rt.index')
            ->with('success', 'Akun Pengurus RT berhasil dibuat!');
    }

    /**
     * Menampilkan form edit akun RT.
     */
    public function edit(User $kelola_rt)
    {
        return view('kelurahan.kelola-rt.edit', ['user' => $kelola_rt]);
    }

    /**
     * Menyimpan perubahan data akun RT.
     */
    public function update(Request $request, User $kelola_rt)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'nik'   => 'required|string|max:16|unique:users,nik,' . $kelola_rt->id,
            'email' => 'required|email|unique:users,email,' . $kelola_rt->id,
            'rt'    => 'required|string|max:5',
            'rw'    => 'required|string|max:5',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique'   => 'NIK sudah digunakan user lain.',
            'email.unique' => 'Email sudah digunakan user lain.',
        ]);

        $data = $request->only(['name', 'nik', 'email', 'rt', 'rw']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8'], [
                'password.min' => 'Password minimal 8 karakter.',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $kelola_rt->update($data);

        return redirect()
            ->route('kelurahan.kelola-rt.index')
            ->with('success', 'Data Pengurus RT berhasil diperbarui!');
    }

    /**
     * Menghapus akun RT.
     */
    public function destroy(User $kelola_rt)
    {
        $kelola_rt->delete();

        return redirect()
            ->back()
            ->with('success', 'Akun berhasil dihapus!');
    }
}