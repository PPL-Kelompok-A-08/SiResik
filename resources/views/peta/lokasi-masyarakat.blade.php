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
    {{-- Sidebar Konsisten --}}
    <x-sidebar />

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Peta Lokasi</h1>
                    <p class="mt-2 text-lg text-slate-500">Dashboard Masyarakat</p>
                </div>
                <div class="flex flex-wrap gap-3">
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
