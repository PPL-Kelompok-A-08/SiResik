<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Masyarakat - SiResik</title>
    <meta name="description" content="Dashboard masyarakat SiResik – pantau setoran, poin, peta layanan, dan riwayat transaksi Anda.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        #map { z-index: 0; }
        .leaflet-container { border-radius: 1rem; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    <aside class="bg-[#0c5b49] flex flex-col px-5 py-7 text-white">
        <div class="flex items-center gap-3 px-2">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20 text-xl">♻</div>
            <div>
                <p class="text-3xl font-black tracking-tight leading-none">SiResik</p>
                <p class="mt-0.5 text-[10px] uppercase tracking-[0.2em] text-emerald-200">Sistem Informasi Resik</p>
            </div>
        </div>

        @php
            $nav = [
                ['label' => 'Dashboard',           'icon' => '⊞', 'href' => route('dashboard.masyarakat'),         'active' => true],
                ['label' => 'Penjemputan',          'icon' => '⊕', 'href' => route('permintaan-penjemputan.index'), 'active' => false],
                ['label' => 'Status Layanan',       'icon' => '◎', 'href' => route('status-layanan.index'),         'active' => false],
                ['label' => 'Riwayat Layanan',      'icon' => '◉', 'href' => route('riwayat-layanan.index'),        'active' => false],
                ['label' => 'Poin & Reward',        'icon' => '◈', 'href' => route('poin.index'),                   'active' => false],
                ['label' => 'Sampah Liar',          'icon' => '⊗', 'href' => route('dashboard.masyarakat'),         'active' => false],
                ['label' => 'Peta & Lokasi',        'icon' => '⊙', 'href' => route('peta.lokasi'),                  'active' => false],
                ['label' => 'Usulkan Titik',        'icon' => '⊕', 'href' => route('peta.usulan-titik'),            'active' => false],
                ['label' => 'Edukasi Lingkungan',   'icon' => '◧', 'href' => route('dashboard.masyarakat'),         'active' => false],
                ['label' => 'Kegiatan Lingkungan',  'icon' => '◨', 'href' => route('dashboard.masyarakat'),         'active' => false],
                ['label' => 'Notifikasi',           'icon' => '◇', 'href' => route('notifications.index'),          'active' => false],
            ];
        @endphp

        <nav class="mt-10 flex-1 space-y-0.5">
            @foreach ($nav as $item)
                <a href="{{ $item['href'] }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition-all
                          {{ $item['active']
                              ? 'bg-emerald-500 text-white shadow-md shadow-emerald-900/30'
                              : 'text-emerald-50/80 hover:bg-white/8 hover:text-white' }}">
                    <span class="w-5 text-center text-base opacity-75">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium text-emerald-50/80 transition hover:bg-white/8 hover:text-white">
                <span class="w-5 text-center">↪</span>
                <span>Keluar (Log Out)</span>
            </button>
        </form>

        <div class="mt-5 rounded-2xl bg-white/8 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-base font-black">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-[15px] font-bold">{{ $user->name }}</p>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-200">Warga Terverifikasi</p>
                </div>

                @if ($upcomingRequest)
                    <div class="rounded-[2rem] bg-[#0c5b49] px-7 py-5 text-white shadow-xl shadow-emerald-900/10 shrink-0">
                        <p class="text-sm font-black uppercase tracking-[0.18em] text-emerald-200">Jadwal Reguler Area</p>
                        <p class="mt-2 text-3xl font-black">{{ \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->translatedFormat('l, d M Y') }}</p>
                        <p class="mt-1 text-lg text-emerald-100">{{ \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->format('H:i') }} WIB bersama {{ $upcomingRequest->petugas?->name ?? 'Petugas' }}</p>
                    </div>
                @endif

                <div class="rounded-[2rem] bg-white px-7 py-5 shadow-xl shadow-slate-200/60 shrink-0">
                    <p class="text-sm font-black uppercase tracking-[0.18em] text-slate-500">Jam Operasional</p>
                    <p class="mt-2 text-3xl font-black text-slate-800">{{ $operationalHours[0]['jam'] ?? '08:00 - 16:00' }}</p>
                    <p class="mt-1 text-lg text-slate-500">Layanan penjemputan tersedia pada jam operasional resmi.</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════ MAIN ═══════════════════════ --}}
    <main class="flex flex-col gap-5 px-7 py-6">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-black tracking-tight text-slate-800">Dashboard Masyarakat</h1>
            <button type="button"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Unduh Laporan
            </button>
        </header>

        {{-- ═══ 4 Stats Cards ═══ --}}
        <section class="grid grid-cols-2 gap-4 xl:grid-cols-4">

            {{-- Total Setoran --}}
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Setoran</p>
                <p class="mt-2 text-3xl font-black text-slate-800">
                    {{ number_format($stats['total_berat'], 1) }}
                    <span class="text-xl font-bold text-slate-500">Kg</span>
                </p>
            </div>

            {{-- Poin Terkumpul --}}
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Poin Terkumpul</p>
                <p class="mt-2 text-3xl font-black text-emerald-600">
                    {{ number_format($stats['total_poin']) }}
                </p>
            </div>

            {{-- Jadwal Terdekat --}}
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Jadwal Terdekat</p>
                @if($upcomingRequest && $upcomingRequest->scheduled_at)
                    <p class="mt-2 text-lg font-black text-blue-600 leading-tight">
                        {{ \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->isToday() ? 'Hari ini' : (\Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->isTomorrow() ? 'Besok' : \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->translatedFormat('d M')) }},
                        {{ \Illuminate\Support\Carbon::parse($upcomingRequest->scheduled_at)->format('H:i') }} WIB
                    </p>
                @else
                    <p class="mt-2 text-base font-bold text-slate-400">Belum ada</p>
                @endif
            </div>

            {{-- Kontribusi CO2 --}}
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Kontribusi CO2</p>
                <p class="mt-2 text-3xl font-black text-slate-800">
                    -{{ number_format($stats['kontribusi_co2'], 1) }}
                    <span class="text-xl font-bold text-slate-500">Kg</span>
                </p>
            </div>
        </section>

        {{-- ═══ Map + Riwayat (2 kolom) ═══ --}}
        <section class="grid gap-4 xl:grid-cols-[1fr,340px]">

            {{-- Peta --}}
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-black text-slate-700">
                        Titik Layanan &amp; Laporan <span class="text-red-500">Liar</span>
                    </h2>
                    <a href="{{ route('peta.lokasi') }}"
                       class="text-xs font-semibold text-emerald-600 hover:underline">Lihat Semua →</a>
                </div>
            </section>

            <!-- Riwayat Penjemputan & Statistik Kontribusi -->
            <section class="mt-10">
                <div class="rounded-[2.5rem] bg-white p-6 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-black">Riwayat Penjemputan</h3>
                            <p class="text-sm text-slate-500">Daftar lengkap transaksi dan partisipasi kebersihan Anda.</p>
                        </div>
                        <button class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700">Unduh Laporan</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full table-auto text-sm">
                            <thead>
                                <tr class="text-slate-500 text-left">
                                    <th class="px-4 py-3">ID Transaksi</th>
                                    <th class="px-4 py-3">Jenis</th>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Lokasi</th>
                                    <th class="px-4 py-3">Detail Item</th>
                                    <th class="px-4 py-3 text-right">Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permintaan as $item)
                                    @php
                                        $kategoriText = $item->items->pluck('kategoriSampah.nama')->filter()->take(2)->implode(', ');
                                        $points = $item->total_estimasi_poin ?: 0;
                                    @endphp
                                    <tr class="border-t">
                                        <td class="px-4 py-4 font-bold">TRX-{{ $item->id }}</td>
                                        <td class="px-4 py-4">{{ $item->jenis ?? '-' }}</td>
                                        <td class="px-4 py-4">{{ optional($item->created_at)->translatedFormat('d M Y') }}</td>
                                        <td class="px-4 py-4">{{ $item->alamat ?? '-' }}</td>
                                        <td class="px-4 py-4 text-slate-400">{{ $kategoriText ?: '-' }}</td>
                                        <td class="px-4 py-4 text-right text-emerald-600 font-black">+{{ number_format($points) }} Pts</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="rounded-[1.5rem] bg-[#091428] p-6 text-white">
                        <p class="text-xs uppercase tracking-[0.18em] text-emerald-400">Statistik Kontribusi</p>
                        <h4 class="mt-4 text-4xl font-black">Total {{ rtrim(rtrim(number_format($totalKg, 2, '.', ''), '0'), '.') ?: 0 }} kg<br><span class="text-xl font-semibold text-slate-300">Sampah Terolah</span></h4>
                    </div>
                    <div class="rounded-[1.5rem] bg-white border p-6">
                        <p class="text-sm text-slate-500">Penjemputan</p>
                        <p class="mt-3 text-3xl font-black text-slate-800">{{ $totalPickups }}<span class="text-sm font-semibold text-slate-400"> Kali</span></p>
                    </div>
                    <div class="rounded-[1.5rem] bg-white border p-6">
                        <p class="text-sm text-slate-500">Poin Terkumpul</p>
                        <p class="mt-3 text-3xl font-black text-emerald-600">{{ number_format($totalPoints) }}<span class="text-sm font-semibold text-slate-400"> Pts</span></p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal Detail Permintaan Penjemputan -->
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <!-- Modal Card Container -->
        <div class="relative w-full max-w-[640px] transform scale-95 bg-white rounded-[2.5rem] shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-[#00c48c] px-10 py-8 text-white relative">
                <!-- Close Button -->
                <button onclick="closeDetailModal()" class="absolute top-6 right-6 flex h-10 w-10 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white font-black text-xl transition-all cursor-pointer">
                    &times;
                </button>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-white/80">Detail Permintaan Penjemputan</p>
                <h2 id="modalCode" class="text-6xl font-black italic tracking-tight mt-2">PK-001</h2>
                
                <div class="flex flex-wrap gap-3 mt-4">
                    <span id="modalStatusBadge" class="rounded-2xl bg-white/20 px-4 py-1.5 text-sm font-black text-white">SELESAI</span>
                    <span id="modalDateBadge" class="rounded-2xl bg-white/20 px-4 py-1.5 text-sm font-black text-white">15 MAR 2024</span>
                </div>
            </div>

            {{-- Riwayat Terbaru --}}
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 flex flex-col">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-black text-slate-700">Riwayat Layanan Terbaru</h2>
                    <a href="{{ route('riwayat-layanan.index') }}"
                       class="text-xs font-semibold text-emerald-600 hover:underline">Lihat Semua →</a>
                </div>

                <div class="flex-1 divide-y divide-slate-50">
                    {{-- Table Header --}}
                    <div class="grid grid-cols-[auto,1fr,auto] gap-2 px-5 py-2">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">ID</span>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Kategori</span>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Status</span>
                    </div>

                    @forelse ($trackingRequests as $index => $item)
                        @php
                            $trkId    = '#TR-' . str_pad($item->id, 3, '0', STR_PAD_LEFT);
                            $kategori = $item->items->pluck('kategoriSampah.nama')->filter()->take(1)->first() ?? 'Penjemputan';
                            $statusMeta = match ($item->status) {
                                'Selesai'  => ['label' => 'Selesai',      'class' => 'bg-emerald-100 text-emerald-700'],
                                'Diproses' => ['label' => 'Dijadwalkan',  'class' => 'bg-blue-100 text-blue-700'],
                                default    => ['label' => 'Menunggu',     'class' => 'bg-amber-100 text-amber-700'],
                            };
                        @endphp
                        <div class="grid grid-cols-[auto,1fr,auto] items-center gap-2 px-5 py-3 hover:bg-slate-50/60 transition">
                            <span class="text-xs font-bold text-slate-500 whitespace-nowrap">{{ $trkId }}</span>
                            <span class="text-xs font-semibold text-slate-700 truncate">{{ $kategori }}</span>
                            <span class="inline-flex rounded px-2 py-0.5 text-[10px] font-black whitespace-nowrap {{ $statusMeta['class'] }}">
                                {{ $statusMeta['label'] }}
                            </span>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                            <p class="text-3xl">📭</p>
                            <p class="mt-2 text-sm font-semibold">Belum ada riwayat</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ═══ Info Bar Bawah ═══ --}}
        <footer class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 px-6 py-3">
            <div class="flex flex-wrap items-center gap-x-8 gap-y-2 text-xs text-slate-500">
                <span>
                    <span class="font-black text-slate-700">Jam Operasional:</span>
                    <span class="ml-1 text-emerald-600 font-semibold">08:00 - 16:00 WIB</span>
                </span>
                <span class="hidden sm:block h-4 w-px bg-slate-200"></span>
                <span>
                    <span class="font-black text-slate-700">Area Layanan:</span>
                    <span class="ml-1 text-emerald-600 font-semibold">Kel. Sukamaju, Jakarta Timur</span>
                </span>
                <span class="hidden sm:block h-4 w-px bg-slate-200"></span>
                <span>
                    <span class="font-black text-slate-700">Total Warga Aktif:</span>
                    <span class="ml-1 text-emerald-600 font-semibold">{{ number_format($stats['total'] ?? 0) }} Setoran</span>
                </span>
                <span class="ml-auto flex items-center gap-1.5 text-emerald-600 font-bold">
                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    +14.8 Stabil
                </span>
            </div>
        </footer>

    </main>
</div>

{{-- ═══════════════════ Leaflet Map Script ═══════════════════ --}}
<script>
(function () {
    // Titik layanan dari server
    const titikLayanan = @json($titikLayanan ?? []);

    // Default center: Jakarta Timur jika tidak ada data
    const defaultLat = titikLayanan.length > 0 ? titikLayanan[0].latitude  : -6.2146;
    const defaultLng = titikLayanan.length > 0 ? titikLayanan[0].longitude : 106.8451;

    const map = L.map('map', { zoomControl: true, scrollWheelZoom: false })
        .setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    // Ikon custom Bank Sampah (biru)
    const blueIcon = L.divIcon({
        className: '',
        html: '<div style="width:14px;height:14px;background:#3b82f6;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.35)"></div>',
        iconSize: [14, 14],
        iconAnchor: [7, 7],
    });

    // Ikon Laporan Liar (merah) – dummy titik contoh
    const redIcon = L.divIcon({
        className: '',
        html: '<div style="width:12px;height:12px;background:#ef4444;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.35)"></div>',
        iconSize: [12, 12],
        iconAnchor: [6, 6],
    });

    // Render titik layanan (Bank Sampah)
    titikLayanan.forEach(function (t) {
        if (t.latitude && t.longitude) {
            L.marker([t.latitude, t.longitude], { icon: blueIcon })
                .addTo(map)
                .bindPopup(`<strong>${t.nama}</strong><br>${t.alamat ?? ''}`);
        }
    });

    // Beberapa titik laporan liar dummy (offset dari pusat)
    if (titikLayanan.length > 0) {
        const offsets = [[0.008, -0.012], [-0.005, 0.018], [0.013, 0.006], [-0.009, -0.007]];
        offsets.forEach(function (o) {
            L.marker([defaultLat + o[0], defaultLng + o[1]], { icon: redIcon })
                .addTo(map)
                .bindPopup('<strong>Laporan Liar</strong><br>Butuh penanganan');
        });
    }
})();
</script>
</body>
</html>
