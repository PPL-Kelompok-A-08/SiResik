<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING FITUR TAGIHAN ===\n";

// 1. Cek kategori sampah dengan harga
echo "1. Kategori Sampah dengan Harga:\n";
$kategoris = \App\Models\KategoriSampah::all();
foreach($kategoris as $kategori) {
    echo $kategori->nama . ': Rp ' . number_format($kategori->harga_per_kg, 0, ',', '.') . '/kg' . "\n";
}
echo "\n";

// 2. Cek permintaan penjemputan yang ada
echo "2. Permintaan Penjemputan:\n";
$permintaan = \App\Models\PermintaanPenjemputan::with('items.kategoriSampah')->first();
if($permintaan) {
    echo "ID: {$permintaan->id}\n";
    echo "Status: {$permintaan->status}\n";
    echo "Total Poin: {$permintaan->total_estimasi_poin}\n";
    echo "Total Tagihan: Rp " . number_format($permintaan->total_tagihan, 0, ',', '.') . "\n";
    echo "Items:\n";
    foreach($permintaan->items as $item) {
        echo "  - {$item->kategoriSampah->nama}: {$item->berat_kg}kg, {$item->estimasi_poin} poin, Rp " . number_format($item->total_tagihan, 0, ',', '.') . "\n";
    }
} else {
    echo "Belum ada permintaan penjemputan.\n";
}
echo "\n";

// 3. Test perhitungan tagihan
echo "3. Test Perhitungan Tagihan:\n";
$plastik = \App\Models\KategoriSampah::where('nama', 'Plastik (PET)')->first();
if($plastik) {
    $berat = 2.5;
    $tagihan = $berat * $plastik->harga_per_kg;
    echo "Plastik 2.5kg: Rp " . number_format($tagihan, 0, ',', '.') . " (harusnya Rp 12,500)\n";
}

echo "\n=== TESTING SELESAI ===\n";