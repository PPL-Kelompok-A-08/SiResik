<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sampah_liars', function (Blueprint $table) {
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete()->after('pengguna_id');
            $table->string('bukti_penanganan')->nullable()->after('catatan_admin');
            $table->timestamp('ditangani_at')->nullable()->after('bukti_penanganan');
        });
    }

    public function down(): void
    {
        Schema::table('sampah_liars', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
            $table->dropColumn(['petugas_id', 'bukti_penanganan', 'ditangani_at']);
        });
    }
};
