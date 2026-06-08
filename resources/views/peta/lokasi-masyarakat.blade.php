<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Lokasi - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
    <div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">
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
                    ['label' => 'Dashboard',           'icon' => '⊞', 'href' => route('dashboard.masyarakat'),         'active' => false],
                    ['label' => 'Penjemputan',          'icon' => '⊕', 'href' => route('permintaan-penjemputan.index'), 'active' => false],
                    ['label' => 'Status Layanan',       'icon' => '◎', 'href' => route('status-layanan.index'),         'active' => false],
                    ['label' => 'Riwayat Layanan',      'icon' => '◉', 'href' => route('riwayat-layanan.index'),        'active' => false],
                    ['label' => 'Poin & Reward',        'icon' => '◈', 'href' => route('poin.index'),                   'active' => false],
                    ['label' => 'Sampah Liar',          'icon' => '⊗', 'href' => route('sampah-liar.index'),            'active' => false],
                    ['label' => 'Peta & Lokasi',        'icon' => '⊙', 'href' => route('peta.lokasi'),                  'active' => true],
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
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Peta Lokasi</h1>
                    <p class="mt-2 text-lg text-slate-500">Dashboard Masyarakat</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700">Unduh Laporan</button>
                    <a href="{{ route('permintaan-penjemputan.index') }}" class="rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white">+ Ajukan Penjemputan</a>
                </div>
            </header>

            <section class="mt-10 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm shadow-slate-200/50">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-4xl font-black tracking-tight text-slate-800">Peta Layanan & Bank Sampah</h2>
                        <p class="mt-2 text-xl text-slate-500">Cari titik penjemputan atau bank sampah terdekat dari lokasi Anda.</p>
                    </div>
                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
                        <label class="relative flex-1 min-w-[220px]">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">⌕</span>
                            <input type="search" id="cari-lokasi" autocomplete="off" placeholder="Cari lokasi..."
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-lg text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        </label>
                        <button type="button" id="peta-lokasi-saya"
                            class="whitespace-nowrap rounded-2xl border border-emerald-600 bg-emerald-50 px-5 py-3 text-lg font-semibold text-emerald-900 transition hover:bg-emerald-100">
                            Lokasi saya
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-6 text-sm font-semibold text-slate-600">
                    <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-green-600"></span> TPS</span>
                    <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-blue-600"></span> Bank Sampah</span>
                    <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-amber-600"></span> Usulan baru</span>
                </div>

                <div class="mt-6">
                    @include('peta._leaflet-map', ['titikLayanan' => $titikLayanan])
                </div>

                <p class="mt-4 text-sm text-slate-500">
                    Peta menggunakan ubin <a class="font-medium text-emerald-700 underline" href="https://www.openstreetmap.org/" target="_blank" rel="noopener">OpenStreetMap</a> (wilayah Kota Bandung dan sekitarnya). Data titik bersumber dari basis data aplikasi.
                </p>
            </section>
        </main>
    </div>
</body>
</html>
