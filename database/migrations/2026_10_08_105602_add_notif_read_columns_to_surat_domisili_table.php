<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_domisili', function (Blueprint $table) {
            // Kapan RT membaca notifikasi "ditolak kelurahan"
            $table->timestamp('notif_ditolak_rt_read_at')->nullable()->after('catatan_kelurahan');
        });
    }

    public function down(): void
    {
        Schema::table('surat_domisili', function (Blueprint $table) {
            $table->dropColumn('notif_ditolak_rt_read_at');
        });
    }
};