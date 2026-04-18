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
        Schema::create('permintaan_penjemputan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_penjemputan_id')->constrained('permintaan_penjemputan')->cascadeOnDelete();
            $table->foreignId('kategori_sampah_id')->constrained('kategori_sampah')->cascadeOnDelete();
            $table->decimal('berat_kg', 8, 2);
            $table->unsignedInteger('estimasi_poin')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_penjemputan_items');
    }
};
