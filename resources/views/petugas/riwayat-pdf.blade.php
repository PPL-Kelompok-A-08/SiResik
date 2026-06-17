<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Penjemputan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; color: #0f172a; }
        p { margin: 0 0 15px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; text-transform: uppercase; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-selesai { background: #dcfce7; color: #166534; }
        .badge-proses { background: #fef9c3; color: #854d0e; }
        .badge-menunggu { background: #e0f2fe; color: #075985; }
    </style>
</head>
<body>
    <h1>Laporan Riwayat Penjemputan - SiResik</h1>
    <p>Dicetak oleh: {{ $user->name }} | Tanggal: {{ date('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Detail Item</th>
                <th class="text-right">Poin</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permintaan as $item)
                @php
                    $categories = $item->items->pluck('kategoriSampah.nama')->filter()->values();
                    $totalPoin  = $item->items->sum('estimasi_poin');
                    $detailItems = $categories->isEmpty() ? 'Sampah umum' : $categories->join(', ');
                    
                    $badgeClass = match($item->status) {
                        'Selesai' => 'badge-selesai',
                        'Diproses' => 'badge-proses',
                        default => 'badge-menunggu',
                    };
                @endphp
                <tr>
                    <td>TRX-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td>{{ $item->alamat ?: '-' }}</td>
                    <td>{{ $detailItems }}</td>
                    <td class="text-right">{{ number_format($totalPoin, 0, ',', '.') }} Poin</td>
                    <td class="text-center"><span class="badge {{ $badgeClass }}">{{ $item->status }}</span></td>
                </tr>
            @endforeach
            @if($permintaan->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data riwayat untuk kriteria ini.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
