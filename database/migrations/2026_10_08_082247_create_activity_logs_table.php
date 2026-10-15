<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable(); // Simpan nama juga, kalau user dihapus
            $table->string('user_role')->nullable(); // Simpan role juga
            $table->string('aksi', 100);              // contoh: 'verifikasi_rt', 'proses_kelurahan'
            $table->string('model_type', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('deskripsi')->nullable();
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('aksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};