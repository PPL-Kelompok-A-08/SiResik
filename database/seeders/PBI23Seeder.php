<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PBI23Seeder extends Seeder
{
    public function run(): void
    {
        DB::table('kegiatan')->insert([
            [
                'judul' => 'Aksi Bersih Pantai Lombang',
                'deskripsi' => 'Agenda mengumpulkan residu mikroplastik di garis pantai Lombang bersama tim relawan.',
                'lokasi' => 'Pesisir Pantai Lombang, Sumenep',
                'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
                'kuota' => 12,
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'judul' => 'Penanaman Mangrove Slopeng',
                'deskripsi' => 'Reboisasi ekor ekosistem laut guna menahan ancaman abrasi pantai utara.',
                'lokasi' => 'Pesisir Slopeng Tengah, Sumenep',
                'tanggal' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'kuota' => 20,
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);

        DB::table('pengumuman')->insert([
            [
                'judul' => 'Daftar Mitra Penukaran Reward Eksklusif Sembako',
                'isi' => 'Kami menginfokan penambahan titik depo penukaran reward sayuran segar & beras merah berlokasi di kecamatan Sumenep kota.',
                'tanggal_publish' => Carbon::today()->format('Y-m-d'),
                'created_at' => now(), 'updated_at' => now()
            ]
        ]);
    }
}