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
        $hargaData = [
            'Plastik (PET)' => 5000,
            'Kertas & Karton' => 3000,
            'Logam (Aluminium)' => 15000,
            'Sisa Makanan' => 500,
            'Baterai & Elektronik' => 20000,
        ];

        foreach ($hargaData as $nama => $harga) {
            DB::table('kategori_sampah')
                ->where('nama', $nama)
                ->update(['harga_per_kg' => $harga]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('kategori_sampah')
            ->update(['harga_per_kg' => 0]);
    }
};
