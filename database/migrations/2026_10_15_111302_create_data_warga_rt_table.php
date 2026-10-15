<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_warga_rt', function (Blueprint $table) {
            $table->id();

            // Data Pribadi
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');

            // Wilayah
            $table->string('rt', 5)->default('001');
            $table->string('rw', 5)->nullable();

            // Kontak
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->nullable();

            // Status
            $table->enum('status', ['belum_daftar', 'sudah_daftar'])->default('belum_daftar');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Index untuk mempercepat filter
            $table->index('rt');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_warga_rt');
    }
};