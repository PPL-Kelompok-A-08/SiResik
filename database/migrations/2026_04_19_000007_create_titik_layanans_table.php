<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titik_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis', 32);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('alamat');
            $table->string('jam_operasional')->nullable();
            $table->text('jenis_sampah_diterima')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titik_layanans');
    }
};
