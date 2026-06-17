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
        Schema::create('sampah_liars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('users')->cascadeOnDelete();
            $table->double('latitude');
            $table->double('longitude');
            $table->string('lokasi')->nullable();
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('status')->default('pending');
            $table->integer('jumlah_estimasi')->default(0);
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampah_liars');
    }
};
