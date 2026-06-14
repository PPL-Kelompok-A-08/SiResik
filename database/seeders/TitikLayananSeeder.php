<?php

namespace Database\Seeders;

use App\Models\TitikLayanan;
use Illuminate\Database\Seeder;

class TitikLayananSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'nama' => 'TPS Kelurahan Cihapit',
                'jenis' => 'tps',
                'latitude' => -6.9142,
                'longitude' => 107.6234,
                'alamat' => 'Jl. Cihapit, Bandung Wetan, Kota Bandung',
                'jam_operasional' => '06:00–18:00 WIB',
                'jenis_sampah_diterima' => 'Campuran terpilah (organik & anorganik residu)',
            ],
            [
                'nama' => 'TPS Kecamatan Lengkong',
                'jenis' => 'tps',
                'latitude' => -6.9210,
                'longitude' => 107.6145,
                'alamat' => 'Jl. Pelajar Pejuang, Lengkong, Kota Bandung',
                'jam_operasional' => '05:30–17:30 WIB',
                'jenis_sampah_diterima' => 'Sampah domestik terpilah',
            ],
            [
                'nama' => 'Bank Sampah Mekar Sari',
                'jenis' => 'bank_sampah',
                'latitude' => -6.9078,
                'longitude' => 107.6289,
                'alamat' => 'Jl. Sadang Serang, Coblong, Kota Bandung',
                'jam_operasional' => '08:00–15:00 WIB (Senin–Sabtu)',
                'jenis_sampah_diterima' => 'Plastik, kertas, logam, elektronik kecil',
            ],
            [
                'nama' => 'Bank Sampah Asri',
                'jenis' => 'bank_sampah',
                'latitude' => -6.9245,
                'longitude' => 107.6078,
                'alamat' => 'Komplek permukiman, Bandung Kidul',
                'jam_operasional' => '09:00–14:00 WIB',
                'jenis_sampah_diterima' => 'Anorganik bernilai ekonomi, minyak jelantah',
            ],
            [
                'nama' => 'Usulan titik layanan — RW 08',
                'jenis' => 'usulan',
                'latitude' => -6.9183,
                'longitude' => 107.6248,
                'alamat' => 'Dekat pasar keliling, Arcamanik (menunggu verifikasi)',
                'jam_operasional' => null,
                'jenis_sampah_diterima' => 'Belum ditetapkan (usulan warga)',
            ],
        ];

        foreach ($rows as $row) {
            TitikLayanan::updateOrCreate(
                ['nama' => $row['nama']],
                $row
            );
        }
    }
}
