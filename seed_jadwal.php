<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$titik = App\Models\TitikLayanan::first();

if (!$titik) {
    $titik = App\Models\TitikLayanan::create([
        'nama' => 'Bank Sampah Melati',
        'jenis' => 'bank_sampah',
        'latitude' => -6.9175,
        'longitude' => 107.6191,
        'alamat' => 'Jl. Merdeka No. 123, Bandung',
        'jam_operasional' => '08:00 - 16:00',
        'jenis_sampah_diterima' => 'Plastik, Kertas, Logam'
    ]);
}

if ($titik) {
    if ($titik->jadwalOperasional()->count() == 0) {
        $titik->jadwalOperasional()->create(['hari' => 'Senin-Kamis', 'jam_buka' => '08:00:00', 'jam_tutup' => '18:00:00']);
        $titik->jadwalOperasional()->create(['hari' => 'Jumat', 'jam_buka' => '08:00:00', 'jam_tutup' => '11:00:00', 'keterangan' => 'Sesi Pagi']);
        $titik->jadwalOperasional()->create(['hari' => 'Jumat', 'jam_buka' => '14:00:00', 'jam_tutup' => '18:00:00', 'keterangan' => 'Sesi Sore']);
        echo "Jadwal berhasil ditambahkan untuk " . $titik->nama . "\n";
    } else {
        echo "Jadwal sudah ada untuk " . $titik->nama . "\n";
    }
} else {
    echo "Belum ada Titik Layanan di database.\n";
}
