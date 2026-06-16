<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poin & Reward - SiResik</title>
    <meta name="description" content="Pantau saldo poin dan tukarkan reward menarik di SiResik.">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .wave-bottom { position: absolute; bottom: 0; left: 0; right: 0; pointer-events: none; line-height: 0; overflow: hidden; }
        .tbl-row { transition: background 0.12s; }
        .tbl-row:hover { background-color: #f0fdf4; }
        .badge-masuk  { background:#dcfce7; color:#16a34a; }
        .badge-keluar { background:#fee2e2; color:#dc2626; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">

<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- Sidebar Konsisten --}}
    <x-sidebar />

    {{-- ============================================================
         MAIN CONTENT
    ============================================================ --}}
    <main class="relative px-6 py-8 lg:px-10 overflow-hidden">

        {{-- ── Header ────────────────────────────────────────────── --}}
        <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-5xl font-black tracking-tight text-slate-900">Poin & Reward</h1>
                <p class="mt-2 text-lg text-slate-500">Pantau saldo poin dan riwayat aktivitas Anda</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button"
                        class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition">
                    Unduh Laporan
                </button>
            </div>
        </header>

        {{-- ── Baris 1: Kartu Total Poin + Kartu Estimasi Nilai ──── --}}
        <section class="mt-10 grid gap-6 lg:grid-cols-[1.6fr,1fr]">

            {{-- Kartu Total Poin (hijau gelap — persis mockup) --}}
            <div class="relative overflow-hidden rounded-[2rem] bg-[#1a5c45] p-8 text-white shadow-xl">
                {{-- Lingkaran dekorasi --}}
                <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/5"></div>
                <div class="pointer-events-none absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-white/5"></div>

                <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-300">Total Poin SiResik</p>
                <div class="mt-4 flex items-baseline gap-3">
                    <span class="text-6xl font-black tracking-tight">
                        {{ number_format($totalPoin, 0, ',', '.') }}
                    </span>
                    <span class="text-2xl font-bold text-emerald-300">PTS</span>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('reward.index') }}"
                       class="rounded-xl border-2 border-white/40 px-6 py-2.5 text-base font-bold text-white hover:bg-white/10 transition">
                        Tukarkan Poin
                    </a>
                    <a href="{{ route('poin.riwayat-reward') }}"
                       class="rounded-xl bg-white/20 px-6 py-2.5 text-base font-bold text-white hover:bg-white/30 transition">
                        Riwayat Transaksi
                    </a>
                </div>
            </div>

            {{-- Kartu Estimasi Nilai (putih) --}}
            <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                <div class="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full bg-emerald-50"></div>

                {{-- Ikon grafik naik --}}
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-2xl text-emerald-600 mb-4">
                    📈
                </div>

                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Estimasi Nilai</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-slate-800">
                    Rp {{ number_format($totalPoin * 10, 0, ',', '.') }}
                </p>
                <p class="mt-2 text-sm text-slate-400 italic">
                    *Nilai tukar fluktuatif sesuai kebijakan bank sampah
                </p>
            </div>
        </section>

        {{-- ── Baris 3: Daftar Reward Internal (Non-klik) ─────────── --}}
        @php
            if (!function_exists('getRewardIcon')) {
                function getRewardIcon($nama) {
                    $namaLower = strtolower($nama);
                    if (str_contains($namaLower, 'data') || str_contains($namaLower, 'internet') || str_contains($namaLower, 'kuota')) {
                        return ['icon' => 'fa-globe', 'bg' => 'bg-sky-50 text-sky-400'];
                    } elseif (str_contains($namaLower, 'bibit') || str_contains($namaLower, 'tanaman') || str_contains($namaLower, 'pohon') || str_contains($namaLower, 'bunga')) {
                        return ['icon' => 'fa-seedling', 'bg' => 'bg-emerald-50 text-emerald-500'];
                    } elseif (str_contains($namaLower, 'ovo') || str_contains($namaLower, 'gopay') || str_contains($namaLower, 'dana') || str_contains($namaLower, 'linkaja') || str_contains($namaLower, 'saldo') || str_contains($namaLower, 'pulsa')) {
                        return ['icon' => 'fa-mobile-screen-button', 'bg' => 'bg-purple-50 text-purple-400'];
                    } elseif (str_contains($namaLower, 'minyak') || str_contains($namaLower, 'sembako') || str_contains($namaLower, 'beras') || str_contains($namaLower, 'belanja') || str_contains($namaLower, 'voucher')) {
                        return ['icon' => 'fa-cart-shopping', 'bg' => 'bg-blue-50 text-blue-300'];
                    }
                    return ['icon' => 'fa-gift', 'bg' => 'bg-amber-50 text-amber-500'];
                }
            }
        @endphp

        <section class="mt-8">
            <div class="mb-6">
                <h2 class="text-2xl font-black text-slate-800">Daftar Reward SiResik</h2>
                <p class="text-sm text-slate-500">Pilihan reward menarik yang tersedia untuk penukaran poin Anda</p>
            </div>
            
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse($rewards as $reward)
                    @php
                        $styleData = getRewardIcon($reward->nama);
                    @endphp
                    <div class="bg-white rounded-3xl border border-slate-200/60 p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow relative">
                        {{-- Gambar / Icon Box --}}
                        @if($reward->gambar)
                            <div class="mb-4 w-full h-32 rounded-xl overflow-hidden bg-slate-100 ring-1 ring-slate-200">
                                <img src="{{ asset('storage/' . $reward->gambar) }}" alt="{{ $reward->nama }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-16 h-16 {{ $styleData['bg'] }} rounded-[1.25rem] flex items-center justify-center mb-4">
                                <i class="fa-solid {{ $styleData['icon'] }} text-2xl"></i>
                            </div>
                        @endif

                        {{-- Name --}}
                        <h3 class="text-base font-bold text-slate-800 tracking-tight leading-snug">{{ $reward->nama }}</h3>
                        
                        {{-- Description --}}
                        <p class="mt-2 text-[11px] text-slate-400 leading-normal max-w-[180px] min-h-[32px] line-clamp-2">
                            {{ $reward->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                        
                        {{-- Points --}}
                        <span class="mt-4 text-[13px] font-black text-emerald-600 tracking-wider">
                            {{ number_format($reward->poin_diperlukan, 0, ',', '.') }} POIN
                        </span>

                        {{-- Stock badge/text in small --}}
                        <div class="mt-3 text-[10px] text-slate-400 font-medium">
                            Stok: {{ $reward->stok }} • {{ $reward->stok > 0 ? 'Tersedia' : 'Habis' }}
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 p-8 text-center text-slate-500 bg-white">
                        Belum ada reward yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Spacer agar konten tidak tertimpa wave --}}
        <div class="h-24"></div>

        {{-- ── Aksen Gelombang (bottom) ────────────────────────────── --}}
        <div class="wave-bottom">
            <svg viewBox="0 0 1440 90" fill="none" xmlns="http://www.w3.org/2000/svg"
                 style="width:100%; height:90px; opacity:0.08;">
                <path d="M0 45C240 80 480 10 720 45C960 80 1200 10 1440 45L1440 90H0Z" fill="#0c5b49"/>
                <path d="M0 60C360 25 720 85 1080 55C1260 40 1380 70 1440 60L1440 90H0Z"
                      fill="#16a34a" opacity="0.6"/>
            </svg>
        </div>

        {{-- ── Logo SiResik sudut kanan bawah ─────────────────────── --}}
        <div class="absolute bottom-4 right-6 flex items-center gap-1.5 opacity-30 pointer-events-none select-none">
            <span class="text-base">♻</span>
            <span class="text-sm font-black tracking-tight text-[#0c5b49]">SiResik</span>
        </div>

    </main>
</div>

</body>
</html>
