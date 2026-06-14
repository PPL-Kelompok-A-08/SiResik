<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Masyarakat - SiResik</title>
    <meta name="description" content="Dashboard masyarakat SiResik – pantau setoran, poin, peta layanan, dan riwayat transaksi Anda.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * { box-sizing: border-box; font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; }
        body { background: #f1f5f1; }
        #map { z-index: 0; }
        .leaflet-container { border-radius: 1rem; }

        /* ════ FIXED SIDEBAR ════ */
        #masyarakat-sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 240px;
            z-index: 60;
            display: flex;
            flex-direction: column;
            background: linear-gradient(170deg, #053d2e 0%, #065f46 55%, #047857 100%);
            overflow: hidden;
        }
        #masyarakat-sidebar::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
            pointer-events: none;
        }
        #masyarakat-sidebar::after {
            content: '';
            position: absolute;
            bottom: 100px; left: -40px;
            width: 140px; height: 140px;
            border-radius: 50%;
            background: rgba(255,255,255,.03);
            pointer-events: none;
        }
        #masyarakat-content-wrap {
            margin-left: 240px;
            min-height: 100vh;
        }
        .mas-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            color: rgba(209,250,229,.75);
            text-decoration: none;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            transition: background .15s, color .15s, transform .12s;
            position: relative;
        }
        .mas-nav-link:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
            transform: translateX(2px);
        }
        .mas-nav-link.active {
            background: rgba(255,255,255,.16);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.12);
        }
        .mas-nav-link.active::before {
            content: '';
            position: absolute;
            left: -20px;
            width: 3px; height: 22px;
            border-radius: 0 3px 3px 0;
            background: #6ee7b7;
        }
        .mas-nav-link .nav-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
            transition: background .15s;
        }
        .mas-nav-link.active .nav-icon,
        .mas-nav-link:hover .nav-icon {
            background: rgba(255,255,255,.18);
        }
        .mas-logout-nav { color: rgba(252,165,165,.85) !important; }
        .mas-logout-nav:hover { background: rgba(239,68,68,.15) !important; color: #fca5a5 !important; }
        .mas-logout-nav .nav-icon { background: rgba(239,68,68,.12) !important; }
        #mas-nav-scroll { overflow-y: auto; flex: 1; padding: 0 16px; }
        #mas-nav-scroll::-webkit-scrollbar { width: 4px; }
        #mas-nav-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 999px; }
        @media (max-width: 1023px) {
            #masyarakat-sidebar { transform: translateX(-100%); transition: transform .25s; }
            #masyarakat-sidebar.open { transform: translateX(0); }
            #masyarakat-content-wrap { margin-left: 0; }
        }
    </style>
</head>
<body class="text-slate-900">

<x-sidebar />

{{-- Mobile top bar --}}
<div id="mas-mobile-bar" style="position:fixed;top:0;left:0;right:0;z-index:50;display:none;align-items:center;gap:12px;padding:10px 16px;background:#065f46;box-shadow:0 2px 12px rgba(0,0,0,.2);">
    <button onclick="toggleMasSidebar()" style="color:#fff;background:rgba(255,255,255,.15);border:none;border-radius:8px;padding:7px;cursor:pointer;">
        <i class="fas fa-bars" style="font-size:16px;"></i>
    </button>
    <span style="font-size:16px;font-weight:900;color:#fff;">SiResik</span>
</div>
<div id="mas-overlay" onclick="toggleMasSidebar()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:49;"></div>

{{-- ════ CONTENT ════ --}}
<div id="masyarakat-content-wrap">
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
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span class="inline-block h-3 w-3 rounded-full bg-blue-500"></span> Titik Layanan
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span class="inline-block h-3 w-3 rounded-full bg-red-500"></span> Laporan Liar
                        </span>
                        <a href="{{ route('peta.lokasi') }}" class="text-xs font-semibold text-emerald-600 hover:underline">Lihat Semua →</a>
                    </div>
                </div>
                <div id="map" style="height: 320px; width: 100%;"></div>
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

        <!-- Riwayat Penjemputan -->
        <section class="mt-6">
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
</div>{{-- end masyarakat-content-wrap --}}

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
    </div>
</div>

{{-- ═══════════════════ Leaflet Map Script ═══════════════════ --}}
<script>
(function () {
    // Data dari server
    const titikLayanan = @json($titikLayanan ?? []);
    const laporanLiar  = @json($laporanSampahLiar ?? []);

    // Default center: dari titik layanan pertama jika ada
    const defaultLat = titikLayanan.length > 0 ? titikLayanan[0].latitude  : -6.9175;
    const defaultLng = titikLayanan.length > 0 ? titikLayanan[0].longitude : 107.6191;

    const map = L.map('map', { zoomControl: true, scrollWheelZoom: false })
        .setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    // Ikon titik layanan (biru)
    const blueIcon = L.divIcon({
        className: '',
        html: '<div style="width:14px;height:14px;background:#3b82f6;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.35)"></div>',
        iconSize: [14, 14],
        iconAnchor: [7, 7],
    });

    // Ikon laporan liar (merah)
    const redIcon = L.divIcon({
        className: '',
        html: '<div style="width:12px;height:12px;background:#ef4444;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.35)"></div>',
        iconSize: [12, 12],
        iconAnchor: [6, 6],
    });

    // Render titik layanan
    titikLayanan.forEach(function (t) {
        if (t.latitude && t.longitude) {
            L.marker([t.latitude, t.longitude], { icon: blueIcon })
                .addTo(map)
                .bindPopup(`<strong>${t.nama}</strong><br>${t.alamat ?? ''}`);
        }
    });

    // Render laporan sampah liar milik user (dari database)
    laporanLiar.forEach(function (l) {
        if (l.latitude && l.longitude) {
            L.marker([l.latitude, l.longitude], { icon: redIcon })
                .addTo(map)
                .bindPopup(`<strong>Laporan Liar</strong><br>${l.lokasi ?? l.deskripsi ?? 'Tanpa keterangan'}<br><small>Status: ${l.status}</small>`);
        }
    });
})();
</script>
<script>
    function toggleMasSidebar() {
        const sidebar = document.getElementById('masyarakat-sidebar');
        const overlay = document.getElementById('mas-overlay');
        sidebar.classList.toggle('open');
        overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
    }
    function handleMasResponsive() {
        const bar = document.getElementById('mas-mobile-bar');
        if (window.innerWidth < 1024) {
            bar.style.display = 'flex';
            document.getElementById('masyarakat-content-wrap').style.paddingTop = '52px';
        } else {
            bar.style.display = 'none';
            document.getElementById('masyarakat-content-wrap').style.paddingTop = '0';
            document.getElementById('masyarakat-sidebar').classList.remove('open');
            document.getElementById('mas-overlay').style.display = 'none';
        }
    }
    window.addEventListener('resize', handleMasResponsive);
    document.addEventListener('DOMContentLoaded', handleMasResponsive);
    handleMasResponsive();
</script>
</body>
</html>
