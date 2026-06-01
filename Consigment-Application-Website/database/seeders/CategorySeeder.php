<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN IMPORT INI

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PERBAIKAN: Nonaktifkan pengecekan foreign key sementara
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel categories terlebih dahulu untuk menghindari duplikat
        DB::table('categories')->truncate();

        // PERBAIKAN: Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();

        // Daftar kategori yang akan dimasukkan
        $categories = [
            ['name' => 'Elektronik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perabot', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lifestyle', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Akademik', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Masukkan data ke dalam tabel
        Category::insert($categories);
    }
}