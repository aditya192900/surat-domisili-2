<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_domisili', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Warga yang mengajukan
            $table->string('keperluan');
            $table->string('ktp_path'); // Lokasi file KTP
            $table->string('kk_path');  // Lokasi file KK
            $table->enum('status', [
                'diajukan_ke_rt',
                'diverifikasi_rt',
                'ditolak_rt',
                'diproses_kelurahan',
                'selesai',
                'ditolak_kelurahan',
            ])->default('diajukan_ke_rt');
            
            // Catatan dan verifikasi RT
            $table->text('catatan_rt')->nullable();
            $table->foreignId('rt_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rt_verified_at')->nullable();
            
            // Catatan dan proses Kelurahan
            $table->text('catatan_kelurahan')->nullable();
            $table->foreignId('kelurahan_processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('kelurahan_processed_at')->nullable();
            
            // Hasil akhir
            $table->string('nomor_surat', 50)->nullable();
            $table->string('file_surat_path')->nullable();
            
            $table->timestamps();

            $table->index(['user_id', 'status']); // Index untuk mempercepat pencarian
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_domisili');
    }
};