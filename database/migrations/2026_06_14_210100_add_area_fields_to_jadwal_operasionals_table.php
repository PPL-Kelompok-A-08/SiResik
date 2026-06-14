<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal_operasionals', function (Blueprint $table) {
            // Drop foreign key if it exists, make it nullable
            $table->foreignId('titik_layanan_id')->nullable()->change();
            
            // Add new fields for Area/Weekly Schedule
            if (!Schema::hasColumn('jadwal_operasionals', 'zona')) {
                $table->string('zona')->nullable()->after('hari');
            }
            if (!Schema::hasColumn('jadwal_operasionals', 'petugas_id')) {
                $table->foreignId('petugas_id')->nullable()->after('zona')->constrained('users')->nullOnDelete();
            }
        });

        // Seed default weekly schedules if we have a petugas
        $petugasId = DB::table('users')->where('role', 'petugas')->value('id');
        
        if ($petugasId) {
            DB::table('jadwal_operasionals')->insert([
                [
                    'titik_layanan_id' => null,
                    'hari' => 'Senin',
                    'zona' => 'Bojongsoang, Desa Buah batu',
                    'jam_buka' => '08:00:00',
                    'jam_tutup' => '10:00:00',
                    'petugas_id' => $petugasId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'titik_layanan_id' => null,
                    'hari' => 'Selasa',
                    'zona' => 'Bojongsoang, Desa Bojongsoang ',
                    'jam_buka' => '08:00:00',
                    'jam_tutup' => '10:00:00',
                    'petugas_id' => $petugasId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'titik_layanan_id' => null,
                    'hari' => 'Rabu',
                    'zona' => 'Baleendah, Kelurahan Jelekong',
                    'jam_buka' => '09:00:00',
                    'jam_tutup' => '11:00:00',
                    'petugas_id' => $petugasId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_operasionals', function (Blueprint $table) {
            $table->foreignId('titik_layanan_id')->nullable(false)->change();
            $table->dropForeign(['petugas_id']);
            $table->dropColumn(['zona', 'petugas_id']);
        });
    }
};
