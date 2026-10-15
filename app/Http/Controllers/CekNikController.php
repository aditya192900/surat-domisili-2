<?php

namespace App\Http\Controllers;

use App\Models\DataWargaRt;
use Illuminate\Http\Request;

class CekNikController extends Controller
{
    /**
     * Cek apakah NIK terdaftar di data_warga_rt.
     * Dipanggil dari beranda (AJAX).
     */
    public function cek(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|string|size:16',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size'     => 'NIK harus 16 digit.',
        ]);

        // Cari warga berdasarkan NIK
        $warga = DataWargaRt::where('nik', $request->nik)->first();

        // KASUS 1: NIK tidak ditemukan
        if (!$warga) {
            return response()->json([
                'status'  => 'not_found',
                'title'   => 'NIK Tidak Terdaftar',
                'message' => 'NIK Anda belum terdaftar di sistem. Silakan hubungi Ketua RT ' . config('kelurahan.rt_default') . ' untuk mendaftarkan data Anda terlebih dahulu.',
            ]);
        }

        // KASUS 2: NIK sudah pernah daftar akun
        if ($warga->status === 'sudah_daftar') {
            return response()->json([
                'status'      => 'already_registered',
                'title'       => 'NIK Sudah Terdaftar',
                'message'     => 'NIK ini sudah memiliki akun pelayanan. Silakan login menggunakan email dan kata sandi Anda, atau hubungi RT jika lupa.',
                'nama'        => $warga->nama,
                'login_url'   => route('login'),
            ]);
        }

        // KASUS 3: NIK ditemukan & belum daftar — SUKSES
        return response()->json([
            'status'  => 'found',
            'title'   => 'NIK Terverifikasi!',
            'message' => 'Data Anda ditemukan. Silakan lanjutkan pendaftaran akun pelayanan.',
            'data'    => [
                'nik'            => $warga->nik,
                'nama'           => $warga->nama,
                'jenis_kelamin'  => $warga->jenisKelaminLabel(),
                'tempat_lahir'   => $warga->tempat_lahir,
                'tanggal_lahir'  => $warga->tanggalLahirFormat(),
                'umur'           => $warga->umur() . ' tahun',
                'alamat'         => $warga->alamat,
                'rt'             => $warga->rt,
                'rw'             => $warga->rw,
                'no_hp'          => $warga->no_hp ?: '-',
            ],
            'register_url' => route('register', ['nik' => $warga->nik]),
        ]);
    }
}