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
        Schema::table('kategori_sampah', function (Blueprint $table) {
            $table->decimal('harga_per_kg', 10, 2)->default(0)->after('poin_per_kg')->comment('Harga per kg dalam Rupiah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_sampah', function (Blueprint $table) {
            $table->dropColumn('harga_per_kg');
        });
    }
};
