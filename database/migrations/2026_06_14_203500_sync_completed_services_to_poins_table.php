<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $completedServices = DB::table('permintaan_penjemputan')
            ->where('status', 'Selesai')
            ->get();

        foreach ($completedServices as $service) {
            $trkId = 'TRK-' . str_pad($service->id, 4, '0', STR_PAD_LEFT);
            $keterangan = 'Poin Layanan Penjemputan Sampah ' . $trkId;

            // Cek apakah poin sudah tercatat sebelumnya
            $exists = DB::table('poins')
                ->where('user_id', $service->pengguna_id)
                ->where('tipe', 'masuk')
                ->where('keterangan', $keterangan)
                ->exists();

            if (!$exists && $service->total_estimasi_poin > 0) {
                DB::table('poins')->insert([
                    'user_id' => $service->pengguna_id,
                    'jumlah' => $service->total_estimasi_poin,
                    'tipe' => 'masuk',
                    'keterangan' => $keterangan,
                    'tanggal' => $service->diselesaikan_at ?? $service->updated_at ?? now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $completedServices = DB::table('permintaan_penjemputan')
            ->where('status', 'Selesai')
            ->get();

        foreach ($completedServices as $service) {
            $trkId = 'TRK-' . str_pad($service->id, 4, '0', STR_PAD_LEFT);
            $keterangan = 'Poin Layanan Penjemputan Sampah ' . $trkId;

            DB::table('poins')
                ->where('user_id', $service->pengguna_id)
                ->where('tipe', 'masuk')
                ->where('keterangan', $keterangan)
                ->delete();
        }
    }
};
