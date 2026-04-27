<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zona_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('warna', 16)->default('#16a34a');
            $table->json('geojson');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zona_layanans');
    }
};

