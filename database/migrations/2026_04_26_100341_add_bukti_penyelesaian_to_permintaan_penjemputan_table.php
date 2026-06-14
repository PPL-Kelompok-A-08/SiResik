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
            $table->string('bukti_penyelesaian')->nullable()->after('status');
            $table->text('catatan_penyelesaian')->nullable()->after('bukti_penyelesaian');
            $table->timestamp('diselesaikan_at')->nullable()->after('catatan_penyelesaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_penjemputan', function (Blueprint $table) {
            $table->dropColumn(['bukti_penyelesaian', 'catatan_penyelesaian', 'diselesaikan_at']);
        });
    }
};
