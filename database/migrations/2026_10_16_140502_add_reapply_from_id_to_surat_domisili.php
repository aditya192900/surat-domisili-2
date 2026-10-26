<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_domisili', function (Blueprint $table) {
            $table->unsignedBigInteger('reapply_from_id')->nullable()->after('user_id');
            $table->foreign('reapply_from_id')
                  ->references('id')
                  ->on('surat_domisili')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('surat_domisili', function (Blueprint $table) {
            $table->dropForeign(['reapply_from_id']);
            $table->dropColumn('reapply_from_id');
        });
    }
};