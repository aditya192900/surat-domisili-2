<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // ==========================================
            // IDENTITAS WILAYAH
            // ==========================================
            ['key' => 'nama_kelurahan',      'value' => 'Kelurahan Sungai Asam',       'tipe' => 'text',     'label' => 'Nama Kelurahan',       'grup' => 'umum'],
            ['key' => 'nama_kecamatan',      'value' => 'Kecamatan Danau Sipin',       'tipe' => 'text',     'label' => 'Nama Kecamatan',       'grup' => 'umum'],
            ['key' => 'nama_kota',           'value' => 'Kota Jambi',                  'tipe' => 'text',     'label' => 'Nama Kota/Kabupaten',  'grup' => 'umum'],
            ['key' => 'nama_provinsi',       'value' => 'Provinsi Jambi',              'tipe' => 'text',     'label' => 'Nama Provinsi',        'grup' => 'umum'],
            ['key' => 'kode_pos',            'value' => '36124',                       'tipe' => 'text',     'label' => 'Kode Pos',             'grup' => 'umum'],

            // ==========================================
            // ALAMAT & KONTAK
            // ==========================================
            ['key' => 'alamat_kelurahan',    'value' => 'Jl. Sungai Asam No. 1, Kelurahan Sungai Asam', 'tipe' => 'textarea', 'label' => 'Alamat Lengkap Kelurahan', 'grup' => 'kontak'],
            ['key' => 'telepon',             'value' => '(0741) 123456',               'tipe' => 'text',     'label' => 'No. Telepon',          'grup' => 'kontak'],
            ['key' => 'whatsapp',            'value' => '6281234567890',               'tipe' => 'text',     'label' => 'No. WhatsApp (format 62)', 'grup' => 'kontak'],
            ['key' => 'whatsapp_format',     'value' => '0812-3456-7890',              'tipe' => 'text',     'label' => 'WA (format tampil)',   'grup' => 'kontak'],
            ['key' => 'email',               'value' => 'kelurahan.sungaiasam@jambikota.go.id', 'tipe' => 'email', 'label' => 'Email Resmi', 'grup' => 'kontak'],

            // ==========================================
            // PEJABAT
            // ==========================================
            ['key' => 'nama_lurah',          'value' => '[Nama Lurah]',                'tipe' => 'text',     'label' => 'Nama Lurah',           'grup' => 'pejabat'],
            ['key' => 'nip_lurah',           'value' => '[NIP Lurah]',                 'tipe' => 'text',     'label' => 'NIP Lurah',            'grup' => 'pejabat'],
            ['key' => 'jabatan_lurah',       'value' => 'Lurah Sungai Asam',           'tipe' => 'text',     'label' => 'Jabatan Lurah',        'grup' => 'pejabat'],

            // ==========================================
            // PENGATURAN SISTEM
            // ==========================================
            ['key' => 'app_name',            'value' => 'Sistem Pelayanan Surat',      'tipe' => 'text',     'label' => 'Nama Aplikasi',        'grup' => 'sistem'],
            ['key' => 'app_full_name',       'value' => 'Sistem Pelayanan Surat Kelurahan Sungai Asam', 'tipe' => 'text', 'label' => 'Nama Aplikasi Lengkap', 'grup' => 'sistem'],
            ['key' => 'jam_operasional',     'value' => 'Senin - Jumat: 08.00 - 15.00 WIB', 'tipe' => 'text', 'label' => 'Jam Operasional', 'grup' => 'sistem'],

            // ==========================================
            // KEY ALIAS (untuk backward-compat view lama)
            // ==========================================
            ['key' => 'nama',                'value' => 'Kelurahan Sungai Asam',       'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
            ['key' => 'nama_upper',          'value' => 'KELURAHAN SUNGAI ASAM',       'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
            ['key' => 'kecamatan',           'value' => 'Kecamatan Danau Sipin',       'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
            ['key' => 'kecamatan_upper',     'value' => 'KECAMATAN DANAU SIPIN',       'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
            ['key' => 'kota',                'value' => 'Kota Jambi',                  'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
            ['key' => 'kota_upper',          'value' => 'KOTA JAMBI',                  'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
            ['key' => 'provinsi',            'value' => 'Provinsi Jambi',              'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
            ['key' => 'alamat',              'value' => 'Jl. Sungai Asam No. 1, Kelurahan Sungai Asam', 'tipe' => 'hidden', 'label' => null, 'grup' => 'alias'],
            ['key' => 'lurah',               'value' => '[Nama Lurah]',                'tipe' => 'hidden',   'label' => null,                   'grup' => 'alias'],
        ];

        foreach ($data as $item) {
            Pengaturan::updateOrCreate(
                ['key' => $item['key']],
                $item
            );
        }

        Pengaturan::clearCache();
    }
}