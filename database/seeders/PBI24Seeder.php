<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PBI24Seeder extends Seeder
{
    public function run(): void
    {
        DB::table('edukasis')->truncate();

        DB::table('edukasis')->insert([
            [
                'judul' => 'Cara Mudah Memilah Sampah Organik di Rumah',
                'slug' => Str::slug('Cara Mudah Memilah Sampah Organik di Rumah'),
                'kategori' => 'Sampah Organik',
                'gambar' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80',
                'isi' => 'Memilah sampah organik seperti sisa makanan dan daun kering sangat berguna untuk pembuatan kompos alami. Anda bisa menggunakan wadah khusus tertutup agar tidak menimbulkan bau di dapur...',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'judul' => 'Panduan Daur Ulang Plastik Menjadi Pot Tanaman Estetik',
                'slug' => Str::slug('Panduan Daur Ulang Plastik Menjadi Pot Tanaman Estetik'),
                'kategori' => 'Daur Ulang',
                'gambar' => 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&w=600&q=80',
                'isi' => 'Jangan buang botol plastik bekas Anda! Dengan sedikit kreativitas, cat warna-warni, dan gunting, Anda bisa menyulap sampah plastik menjadi pot tanaman hias yang mempercantik pekarangan rumah...',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}