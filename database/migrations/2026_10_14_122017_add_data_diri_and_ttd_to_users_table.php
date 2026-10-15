<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->after('alamat');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->string('nip')->nullable()->after('jenis_kelamin');
            $table->string('jabatan')->nullable()->after('nip');
            $table->string('ttd_path')->nullable()->after('foto');
            $table->string('stempel_path')->nullable()->after('ttd_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'nip', 'jabatan', 'ttd_path', 'stempel_path'
            ]);
        });
    }
};