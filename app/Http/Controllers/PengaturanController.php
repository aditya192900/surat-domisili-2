<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $semua = Pengaturan::orderBy('grup')->orderBy('id')->get();
        $grup = $semua->groupBy('grup');

        return view('kelurahan.pengaturan.index', compact('grup'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_kelurahan'   => 'required|string|max:150',
            'nama_kecamatan'   => 'required|string|max:150',
            'nama_kota'        => 'required|string|max:150',
            'nama_provinsi'    => 'nullable|string|max:150',
            'kode_pos'         => 'nullable|string|max:10',
            'alamat_kelurahan' => 'required|string|max:500',
            'telepon'          => 'nullable|string|max:30',
            'whatsapp'         => 'nullable|string|max:20',
            'whatsapp_format'  => 'nullable|string|max:30',
            'email'            => 'nullable|email|max:150',
            'nama_lurah'       => 'required|string|max:150',
            'nip_lurah'        => 'nullable|string|max:30',
            'jabatan_lurah'    => 'required|string|max:150',
            'app_name'         => 'nullable|string|max:150',
            'app_full_name'    => 'nullable|string|max:200',
            'jam_operasional'  => 'nullable|string|max:150',
            'logo'             => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
        ], [
            'nama_kelurahan.required'   => 'Nama kelurahan wajib diisi.',
            'nama_kecamatan.required'   => 'Nama kecamatan wajib diisi.',
            'nama_kota.required'        => 'Nama kota wajib diisi.',
            'alamat_kelurahan.required' => 'Alamat kelurahan wajib diisi.',
            'nama_lurah.required'       => 'Nama Lurah wajib diisi.',
            'jabatan_lurah.required'    => 'Jabatan Lurah wajib diisi.',
        ]);

        $dataLama = Pengaturan::pluck('value', 'key')->toArray();

        $fields = [
            'nama_kelurahan', 'nama_kecamatan', 'nama_kota', 'nama_provinsi', 'kode_pos',
            'alamat_kelurahan', 'telepon', 'whatsapp', 'whatsapp_format', 'email',
            'nama_lurah', 'nip_lurah', 'jabatan_lurah',
            'app_name', 'app_full_name', 'jam_operasional',
        ];

        foreach ($fields as $key) {
            if ($request->has($key)) {
                Pengaturan::set($key, $request->input($key));
            }
        }

        // Auto-generate KEY ALIAS (untuk view lama)
        if ($request->filled('nama_kelurahan')) {
            Pengaturan::set('nama', $request->nama_kelurahan);
            Pengaturan::set('nama_upper', strtoupper($request->nama_kelurahan));
        }
        if ($request->filled('nama_kecamatan')) {
            Pengaturan::set('kecamatan', $request->nama_kecamatan);
            Pengaturan::set('kecamatan_upper', strtoupper($request->nama_kecamatan));
        }
        if ($request->filled('nama_kota')) {
            Pengaturan::set('kota', $request->nama_kota);
            Pengaturan::set('kota_upper', strtoupper($request->nama_kota));
        }
        if ($request->filled('nama_provinsi')) {
            Pengaturan::set('provinsi', $request->nama_provinsi);
        }
        if ($request->filled('alamat_kelurahan')) {
            Pengaturan::set('alamat', $request->alamat_kelurahan);
        }
        if ($request->filled('nama_lurah')) {
            Pengaturan::set('lurah', $request->nama_lurah);
        }

        // Upload logo (kalau ada)
        if ($request->hasFile('logo')) {
            $logoLama = Pengaturan::ambil('logo_path');
            if ($logoLama && Storage::disk('local')->exists($logoLama)) {
                Storage::disk('local')->delete($logoLama);
            }

            $path = $request->file('logo')->store('private/logo', 'local');
            Pengaturan::set('logo_path', $path);
        }

        Pengaturan::clearCache();

        $dataBaru = Pengaturan::pluck('value', 'key')->toArray();

        ActivityLogger::log(
            'update_pengaturan',
            'Lurah memperbarui pengaturan kelurahan',
            null,
            $dataLama,
            $dataBaru
        );

        return back()->with('success', 'Pengaturan kelurahan berhasil diperbarui. Semua halaman & PDF sudah otomatis terupdate.');
    }

    public function hapusLogo()
    {
        $path = Pengaturan::ambil('logo_path');

        if ($path && Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }

        Pengaturan::set('logo_path', null);
        Pengaturan::clearCache();

        ActivityLogger::log(
            'hapus_logo',
            'Lurah menghapus logo kelurahan',
            null,
            ['logo_path' => $path],
            null
        );

        return back()->with('success', 'Logo kelurahan berhasil dihapus.');
    }

    public function logo()
    {
        $path = Pengaturan::ambil('logo_path');

        if (!$path) {
            abort(404);
        }

        $fullPath = storage_path('app/private/' . $path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath);
    }
}