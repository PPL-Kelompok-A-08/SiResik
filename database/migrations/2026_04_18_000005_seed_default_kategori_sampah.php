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
        $defaults = [
            [
                'nama' => 'Plastik (PET)',
                'deskripsi' => 'Botol plastik dan kemasan PET yang bersih.',
                'poin_per_kg' => 800,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Kertas & Karton',
                'deskripsi' => 'Kertas, kardus, dan karton kering.',
                'poin_per_kg' => 1200,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Logam (Aluminium)',
                'deskripsi' => 'Kaleng dan bahan aluminium bekas.',
                'poin_per_kg' => 1800,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Sisa Makanan',
                'deskripsi' => 'Sampah organik yang dapat diolah.',
                'poin_per_kg' => 300,
                'status' => 'aktif',
            ],
            [
                'nama' => 'Baterai & Elektronik',
                'deskripsi' => 'E-waste ringan dan baterai bekas.',
                'poin_per_kg' => 2500,
                'status' => 'aktif',
            ],
        ];

        foreach ($defaults as $item) {
            $exists = DB::table('kategori_sampah')->where('nama', $item['nama'])->exists();

            if ($exists) {
                continue;
            }

            DB::table('kategori_sampah')->insert([
                ...$item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
