<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('tipe', 30)->default('text'); // text, email, image, number, textarea
            $table->string('label', 150)->nullable();
            $table->string('grup', 50)->default('umum'); // umum, kontak, pejabat, dokumen
            $table->timestamps();

            $table->index('grup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};