<?php

namespace Database\Seeders;

use App\Models\JadwalOperasional;
use App\Models\TitikLayanan;
use Illuminate\Database\Seeder;

class JadwalOperasionalSeeder extends Seeder
{
    /**
     * Seed jadwal operasional untuk titik layanan yang sudah ada.
     * Logika ini dipindahkan dari seed_jadwal.php (root) ke seeder resmi Laravel.
     */
    public function run(): void
    {
        // Data jadwal: [nama titik layanan => [jadwal]]
        $data = [
            'Bank Sampah Mekar Sari' => [
                ['hari' => 'Senin',  'jam_buka' => '08:00:00', 'jam_tutup' => '15:00:00'],
                ['hari' => 'Selasa', 'jam_buka' => '08:00:00', 'jam_tutup' => '15:00:00'],
                ['hari' => 'Rabu',   'jam_buka' => '08:00:00', 'jam_tutup' => '15:00:00'],
                ['hari' => 'Kamis',  'jam_buka' => '08:00:00', 'jam_tutup' => '15:00:00'],
                ['hari' => 'Jumat',  'jam_buka' => '08:00:00', 'jam_tutup' => '11:00:00', 'keterangan' => 'Sesi Pagi'],
                ['hari' => 'Jumat',  'jam_buka' => '14:00:00', 'jam_tutup' => '15:00:00', 'keterangan' => 'Sesi Sore'],
                ['hari' => 'Sabtu',  'jam_buka' => '08:00:00', 'jam_tutup' => '13:00:00'],
            ],
            'Bank Sampah Asri' => [
                ['hari' => 'Senin',  'jam_buka' => '09:00:00', 'jam_tutup' => '14:00:00'],
                ['hari' => 'Rabu',   'jam_buka' => '09:00:00', 'jam_tutup' => '14:00:00'],
                ['hari' => 'Jumat',  'jam_buka' => '09:00:00', 'jam_tutup' => '14:00:00'],
            ],
            'TPS Kelurahan Cihapit' => [
                ['hari' => 'Senin',  'jam_buka' => '06:00:00', 'jam_tutup' => '18:00:00'],
                ['hari' => 'Selasa', 'jam_buka' => '06:00:00', 'jam_tutup' => '18:00:00'],
                ['hari' => 'Rabu',   'jam_buka' => '06:00:00', 'jam_tutup' => '18:00:00'],
                ['hari' => 'Kamis',  'jam_buka' => '06:00:00', 'jam_tutup' => '18:00:00'],
                ['hari' => 'Jumat',  'jam_buka' => '06:00:00', 'jam_tutup' => '18:00:00'],
                ['hari' => 'Sabtu',  'jam_buka' => '06:00:00', 'jam_tutup' => '18:00:00'],
            ],
            'TPS Kecamatan Lengkong' => [
                ['hari' => 'Senin',  'jam_buka' => '05:30:00', 'jam_tutup' => '17:30:00'],
                ['hari' => 'Selasa', 'jam_buka' => '05:30:00', 'jam_tutup' => '17:30:00'],
                ['hari' => 'Rabu',   'jam_buka' => '05:30:00', 'jam_tutup' => '17:30:00'],
                ['hari' => 'Kamis',  'jam_buka' => '05:30:00', 'jam_tutup' => '17:30:00'],
                ['hari' => 'Jumat',  'jam_buka' => '05:30:00', 'jam_tutup' => '17:30:00'],
                ['hari' => 'Sabtu',  'jam_buka' => '05:30:00', 'jam_tutup' => '17:30:00'],
            ],
        ];

        foreach ($data as $namaTitik => $jadwalList) {
            $titik = TitikLayanan::where('nama', $namaTitik)->first();

            if (!$titik) {
                $this->command->warn("Titik layanan '{$namaTitik}' tidak ditemukan, skip.");
                continue;
            }

            // Hanya tambahkan jika belum ada jadwal untuk titik ini
            if ($titik->jadwalOperasional()->count() === 0) {
                foreach ($jadwalList as $jadwal) {
                    $titik->jadwalOperasional()->create($jadwal);
                }
                $this->command->info("✓ Jadwal ditambahkan untuk: {$namaTitik}");
            } else {
                $this->command->line("  Skip '{$namaTitik}': jadwal sudah ada.");
            }
        }
    }
}
