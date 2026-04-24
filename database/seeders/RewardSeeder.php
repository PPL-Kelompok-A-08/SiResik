<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'nama' => 'Voucher Belanja',
                'deskripsi' => 'Voucher belanja senilai Rp 50.000 untuk toko retail terdekat',
                'poin_diperlukan' => 100,
                'stok' => 50,
                'aktif' => true,
            ],
            [
                'nama' => 'Diskon Laundry',
                'deskripsi' => 'Diskon 20% untuk layanan laundry selama 1 bulan',
                'poin_diperlukan' => 75,
                'stok' => 30,
                'aktif' => true,
            ],
            [
                'nama' => 'Paket Eco-Friendly',
                'deskripsi' => 'Paket lengkap produk ramah lingkungan (tas kain, botol reusable, dll)',
                'poin_diperlukan' => 150,
                'stok' => 20,
                'aktif' => true,
            ],
            [
                'nama' => 'Donasi Pohon',
                'deskripsi' => 'Donasi penanaman 1 pohon di area hijau kota',
                'poin_diperlukan' => 50,
                'stok' => 100,
                'aktif' => true,
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
