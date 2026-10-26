<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index untuk surat_domisili
        Schema::table('surat_domisili', function (Blueprint $table) {
            $table->index('status', 'idx_surat_status');
            $table->index('user_id', 'idx_surat_user');
            $table->index('created_at', 'idx_surat_created');
            $table->index('rt_verified_at', 'idx_surat_rt_verified');
            $table->index(['status', 'created_at'], 'idx_surat_status_created');
        });

        // Index untuk users
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index('rt', 'idx_users_rt');
            $table->index('nik', 'idx_users_nik');
        });

        // Index untuk data_warga_rt
        Schema::table('data_warga_rt', function (Blueprint $table) {
            $table->index('rt', 'idx_warga_rt');
            $table->index('status', 'idx_warga_status');
            $table->index('nik', 'idx_warga_nik');
            $table->index(['rt', 'status'], 'idx_warga_rt_status');
        });

        // Index untuk pengumuman
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->index('is_published', 'idx_pengumuman_published');
            $table->index('tanggal_mulai', 'idx_pengumuman_tanggal');
            $table->index(['is_published', 'tanggal_mulai'], 'idx_pengumuman_pub_tgl');
        });
    }

    public function down(): void
    {
        Schema::table('surat_domisili', function (Blueprint $table) {
            $table->dropIndex('idx_surat_status');
            $table->dropIndex('idx_surat_user');
            $table->dropIndex('idx_surat_created');
            $table->dropIndex('idx_surat_rt_verified');
            $table->dropIndex('idx_surat_status_created');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_rt');
            $table->dropIndex('idx_users_nik');
        });

        Schema::table('data_warga_rt', function (Blueprint $table) {
            $table->dropIndex('idx_warga_rt');
            $table->dropIndex('idx_warga_status');
            $table->dropIndex('idx_warga_nik');
            $table->dropIndex('idx_warga_rt_status');
        });

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropIndex('idx_pengumuman_published');
            $table->dropIndex('idx_pengumuman_tanggal');
            $table->dropIndex('idx_pengumuman_pub_tgl');
        });
    }
};