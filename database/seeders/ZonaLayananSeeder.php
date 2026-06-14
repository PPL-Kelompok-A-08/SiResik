<?php

namespace Database\Seeders;

use App\Models\ZonaLayanan;
use Illuminate\Database\Seeder;

class ZonaLayananSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'nama' => 'Zona A - Gedung Kuliah',
                'warna' => '#16a34a',
                'geojson' => [
                    'type' => 'Polygon',
                    'coordinates' => [[
                        [107.6298, -6.9779],
                        [107.6344, -6.9779],
                        [107.6344, -6.9736],
                        [107.6298, -6.9736],
                        [107.6298, -6.9779],
                    ]],
                ],
            ],
            [
                'nama' => 'Zona B - Asrama',
                'warna' => '#2563eb',
                'geojson' => [
                    'type' => 'Polygon',
                    'coordinates' => [[
                        [107.6264, -6.9748],
                        [107.6307, -6.9748],
                        [107.6307, -6.9716],
                        [107.6264, -6.9716],
                        [107.6264, -6.9748],
                    ]],
                ],
            ],
        ];

        foreach ($rows as $row) {
            ZonaLayanan::updateOrCreate(
                ['nama' => $row['nama']],
                $row
            );
        }
    }
}

