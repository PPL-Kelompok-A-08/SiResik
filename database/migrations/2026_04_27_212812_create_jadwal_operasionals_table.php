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
        Schema::create('jadwal_operasionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titik_layanan_id')->constrained('titik_layanans')->onDelete('cascade');
            $table->string('hari');
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_operasionals');
    }
};
