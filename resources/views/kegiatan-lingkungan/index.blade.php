<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan Lingkungan - SiResik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
{{-- Perbaikan di baris ini: Menggunakan spasi [260px_1fr] bukan koma --}}
<div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    <x-sidebar />

    {{-- ═══════════════════════ MAIN CONTENT ═══════════════════════ --}}
    <main class="flex flex-col gap-8 px-8 py-6">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-bold tracking-tight text-slate-800">Informasi SiResik</h1>
            <button type="button"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Unduh Laporan
            </button>
        </header>

        {{-- Section Title & Filter --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-[#1e293b]" style="letter-spacing: -0.5px">KEGIATAN LINGKUNGAN</h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Ikut serta melindungi bumi kita</p>
            </div>
            
            {{-- Filter Buttons --}}
            <div class="flex items-center gap-2 self-end text-xs font-bold">
                <span class="text-slate-400 mr-2"><i class="fas fa-sliders-h"></i></span>
                <button class="rounded-lg bg-slate-200 px-4 py-2 text-slate-700 uppercase tracking-wider">SEMUA</button>
                <button class="rounded-lg bg-white px-4 py-2 text-slate-400 border border-slate-200 uppercase tracking-wider hover:bg-slate-50 transition">TERDEKAT</button>
            </div>
        </div>

        {{-- ═══ Kegiatan Grid ═══ --}}
        @php
            $activities = [
                [
                    'tanggal'    => '25 APRIL 2024',
                    'judul'      => 'Bersih Pantai Ancol',
                    'lokasi'     => 'Pantai Indah, Ancol',
                    'partisipan' => '124 Partisipan',
                    'img'        => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80',
                ],
                [
                    'tanggal'    => '02 MEI 2024',
                    'judul'      => 'Workshop Daur Ulang Kreatif',
                    'lokasi'     => 'Community Hub SiResik',
                    'partisipan' => '45 Partisipan',
                    'img'        => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=600&q=80',
                ],
                [
                    'tanggal'    => '10 MEI 2024',
                    'judul'      => 'Penanaman 1000 Mangrove',
                    'lokasi'     => 'Hutan Mangrove PIK',
                    'partisipan' => '350 Partisipan',
                    'img'        => 'https://images.unsplash.com/photo-1545239351-ef35f43d514b?w=600&q=80',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($activities as $activity)
                <div class="overflow-hidden rounded-3xl bg-white shadow-sm border border-slate-100 flex flex-col justify-between">
                    
                    {{-- Card Header Image & Badge --}}
                    <div class="relative h-48 w-full bg-slate-100 shrink-0">
                        <img src="{{ $activity['img'] }}" alt="{{ $activity['judul'] }}" class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 rounded-lg bg-white/90 backdrop-blur-sm px-3 py-1.5 text-[10px] font-black text-slate-800 uppercase tracking-wider">
                            <i class="far fa-calendar-alt text-emerald-600"></i> {{ $activity['tanggal'] }}
                        </span>
                    </div>

                    {{-- Card Body Content --}}
                    <div class="p-6 flex flex-col flex-grow gap-4">
                        <div>
                            <h3 class="text-lg font-black text-slate-800 leading-snug mb-3">{{ $activity['judul'] }}</h3>
                            <div class="space-y-1.5 text-xs font-medium text-slate-400">
                                <p class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-slate-300 w-4 text-center"></i> {{ $activity['lokasi'] }}
                                </p>
                                <p class="flex items-center gap-2">
                                    <i class="fas fa-users text-slate-300 w-4 text-center"></i> {{ $activity['partisipan'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <button type="button" 
                                class="w-full rounded-xl bg-[#111827] py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-slate-800 shadow-sm mt-auto">
                            IKUT AKSI
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

    </main>
</div>
</body>
</html>