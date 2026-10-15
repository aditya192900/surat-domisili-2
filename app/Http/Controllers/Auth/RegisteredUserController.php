<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DataWargaRt;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        $nik = $request->query('nik', '');

        $masterData = null;
        if ($nik && strlen($nik) === 16) {
            $masterData = DataWargaRt::where('nik', $nik)
                ->where('status', 'belum_daftar')
                ->first();
        }

        return view('auth.register', compact('nik', 'masterData'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik'      => ['required', 'string', 'size:16'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'    => ['nullable', 'string', 'max:20'],
            'syarat'   => ['accepted'],
        ], [
            'nik.required'    => 'NIK wajib diisi.',
            'nik.size'        => 'NIK harus 16 digit.',
            'syarat.accepted' => 'Anda harus menyetujui Syarat & Ketentuan sebelum mendaftar.',
        ]);

        // Validasi NIK harus ada di master
        $warga = DataWargaRt::where('nik', $request->nik)->first();

        if (!$warga) {
            throw ValidationException::withMessages([
                'nik' => 'NIK Anda belum terdaftar di sistem. Silakan hubungi Ketua RT untuk mendaftarkan data Anda terlebih dahulu.',
            ]);
        }

        if ($warga->status === 'sudah_daftar') {
            throw ValidationException::withMessages([
                'nik' => 'NIK ini sudah memiliki akun. Silakan login langsung.',
            ]);
        }

        if (User::where('nik', $request->nik)->exists()) {
            throw ValidationException::withMessages([
                'nik' => 'NIK ini sudah terdaftar sebagai akun. Silakan login.',
            ]);
        }

        // Buat akun user
        $user = User::create([
            'name'           => $warga->nama,
            'nik'            => $warga->nik,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'phone'          => $request->phone ?? $warga->no_hp,
            'role'           => 'warga',
            'rt'             => $warga->rt,
            'rw'             => $warga->rw,
            'alamat'         => $warga->alamat,
            'tempat_lahir'   => $warga->tempat_lahir,
            'tanggal_lahir'  => $warga->tanggal_lahir,
            'jenis_kelamin'  => $warga->jenis_kelamin,
        ]);

        // Update status master data
        $warga->update([
            'status'  => 'sudah_daftar',
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Silakan login dengan email dan kata sandi Anda.');
    }
}