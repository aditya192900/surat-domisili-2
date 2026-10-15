<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Warga
        User::create([
            'name'     => 'Budi Warga',
            'nik'      => '3201010101010001',
            'email'    => 'warga@test.com',
            'password' => 'password',
            'phone'    => '081234567890',
            'role'     => 'warga',
            'rt'       => '001',
            'rw'       => '002',
            'alamat'   => 'Jl. Mawar No. 10',
        ]);

        // Ketua RT
        User::create([
            'name'     => 'Pak RT 001',
            'nik'      => '3201010101010002',
            'email'    => 'rt@test.com',
            'password' => 'password',
            'phone'    => '081234567891',
            'role'     => 'rt',
            'rt'       => '001',
            'rw'       => '002',
        ]);

        // Petugas Kelurahan
        User::create([
            'name'     => 'Bu Kelurahan',
            'nik'      => '3201010101010003',
            'email'    => 'kelurahan@test.com',
            'password' => 'password',
            'phone'    => '081234567892',
            'role'     => 'kelurahan',
        ]);
    }
}