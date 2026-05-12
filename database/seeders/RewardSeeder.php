<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan FK check sementara agar truncate bisa berjalan
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Reward::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rewards = [
            [
                'nama'           => 'Saldo OVO 10rb',
                'deskripsi'      => 'Top-up saldo OVO senilai Rp 10.000 langsung ke akun Anda.',
                'poin_diperlukan'=> 10000,
                'stok'           => 50,
                'aktif'          => true,
            ],
            [
                'nama'           => 'Voucher Listrik',
                'deskripsi'      => 'Token listrik PLN senilai Rp 25.000 untuk kebutuhan rumah tangga.',
                'poin_diperlukan'=> 25000,
                'stok'           => 30,
                'aktif'          => true,
            ],
            [
                'nama'           => 'Minyak Goreng 1L',
                'deskripsi'      => 'Minyak goreng kemasan 1 liter, dapat diambil di kantor bank sampah.',
                'poin_diperlukan'=> 15000,
                'stok'           => 40,
                'aktif'          => true,
            ],
            [
                'nama'           => 'Paket Data 2GB',
                'deskripsi'      => 'Kuota internet 2GB berlaku 7 hari untuk semua operator.',
                'poin_diperlukan'=> 5000,
                'stok'           => 100,
                'aktif'          => true,
            ],
            [
                'nama'           => 'Voucher Belanja 50rb',
                'deskripsi'      => 'Voucher belanja senilai Rp 50.000 untuk toko retail terdekat.',
                'poin_diperlukan'=> 40000,
                'stok'           => 20,
                'aktif'          => true,
            ],
            [
                'nama'           => 'Bibit Tanaman',
                'deskripsi'      => 'Paket 3 bibit tanaman sayuran pilihan untuk berkebun di rumah.',
                'poin_diperlukan'=> 8000,
                'stok'           => 60,
                'aktif'          => true,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
