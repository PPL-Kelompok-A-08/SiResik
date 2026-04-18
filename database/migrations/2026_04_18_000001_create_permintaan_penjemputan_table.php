<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permintaan_penjemputan', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal', 45);
            $table->string('jadwal', 45);
            $table->string('status', 45);
            $table->string('alamat', 45);
            $table->string('catatan', 45);
            $table->foreignId('pengguna_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_penjemputan');
    }
};
