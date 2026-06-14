<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pendaftaran_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->timestamps();

            // Kunci unik ganda: Mencegah user mendaftar kegiatan yang sama dua kali
            $table->unique(['user_id', 'kegiatan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_kegiatan');
    }
};