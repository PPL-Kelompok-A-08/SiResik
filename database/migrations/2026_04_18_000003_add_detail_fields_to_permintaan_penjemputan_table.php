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
        Schema::table('permintaan_penjemputan', function (Blueprint $table) {
            $table->string('nomor_telepon', 20)->default('-')->after('alamat');
            $table->unsignedInteger('total_estimasi_poin')->default(0)->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_penjemputan', function (Blueprint $table) {
            $table->dropColumn(['nomor_telepon', 'total_estimasi_poin']);
        });
    }
};
