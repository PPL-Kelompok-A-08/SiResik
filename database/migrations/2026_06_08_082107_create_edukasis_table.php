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
        Schema::create('edukasis', function (Blueprint $table) {
            $table->id();                                    // Primary Key
            $table->string('judul', 255);                     // Judul artikel edukasi
            $table->string('slug', 255)->unique();           // Slug unik untuk URL SEO-friendly
            $table->string('kategori', 100);                 // Kategori (e.g. Sampah Organik, Daur Ulang)
            $table->string('gambar')->nullable();            // Jalur penyimpanan gambar
            $table->text('isi');                             // Konten artikel edukasi
            $table->timestamps();                            // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edukasis');
    }
};