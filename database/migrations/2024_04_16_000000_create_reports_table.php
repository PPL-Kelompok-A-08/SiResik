<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kategori_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama pelapor
            $table->text('deskripsi')->nullable(); // Deskripsi sampah
            $table->integer('poin_per_kg'); // Poin per kg
            $table->string('status')->default('pending'); // pending, proses, selesai
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reports');
    }
};