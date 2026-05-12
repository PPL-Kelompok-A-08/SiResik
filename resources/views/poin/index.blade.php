<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poin & Reward - SiResik</title>
    <meta name="description" content="Pantau saldo poin dan tukarkan reward menarik di SiResik.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Wave SVG di bagian bawah */
        .wave-bottom {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            pointer-events: none;
            line-height: 0;
            overflow: hidden;
        }
        /* Hover row tabel */
        .tbl-row { transition: background 0.12s; }
        .tbl-row:hover { background-color: #f0fdf4; }
        /* Badge tipe */
        .badge-masuk  { background:#dcfce7; color:#16a34a; }
        .badge-keluar { background:#fee2e2; color:#dc2626; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

<div class="min-h-screen xl:grid xl:grid-cols-[300px,1fr]">

    {{-- ============================================================
         SIDEBAR  (mengikuti pola masyarakat.blade.php)
    ============================================================ --}}
    <aside class="bg-[#0c5b49] px-6 py-8 text-white">
        {{-- Logo --}}
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/20 text-2xl">♻</div>
            <div>
                <p class="text-4xl font-black tracking-tight">SiResik</p>
                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-emerald-100">Sistem Informasi Resik</p>
            </div>
        </div>

        @php
            $menuItems = [
                ['label' => 'Dashboard',          'active' => false, 'href' => route('dashboard.masyarakat')],
                ['label' => 'Penjemputan',         'active' => false, 'href' => route('permintaan-penjemputan.index')],
                ['label' => 'Status Layanan',      'active' => false, 'href' => route('dashboard.masyarakat')],
                ['label' => 'Riwayat Layanan',     'active' => false, 'href' => route('riwayat-layanan.index')],
                ['label' => 'Poin & Reward',       'active' => true,  'href' => route('poin.index')],
                ['label' => 'Sampah Liar',         'active' => false, 'disabled' => true],
                ['label' => 'Peta & Lokasi',       'active' => false, 'href' => route('peta.lokasi')],
                ['label' => 'Usulkan Titik',       'active' => false, 'href' => route('peta.usulan-titik')],
                ['label' => 'Edukasi Lingkungan',  'active' => false, 'disabled' => true],
                ['label' => 'Kegiatan Lingkungan', 'active' => false, 'disabled' => true],
                ['label' => 'Notifikasi',          'active' => false, 'disabled' => true],
            ];
        @endphp

        <nav class="mt-14 space-y-2">
            @foreach ($menuItems as $item)
                @if (!empty($item['href']))
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition
                              {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
                        <span class="text-xl">{{ $item['active'] ? '◉' : '◦' }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @else
                    <div class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-200 opacity-60 cursor-not-allowed">
                        <span class="text-xl">◦</span>
                        <span>{{ $item['label'] }}</span>
                    </div>
                @endif
            @endforeach
        </nav>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-8">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-50 transition hover:bg-white/5">
                <span class="text-xl">↪</span>
                <span>Keluar (Log Out)</span>
            </button>
        </form>

        {{-- User card --}}
        <div class="mt-10 rounded-3xl bg-white/5 px-4 py-5">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-xl font-bold">{{ $user->name }}</p>
                    <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                </div>
            </div>
        </div>
    </aside>

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
                    <a href="{{ route('riwayat-layanan.index') }}"
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

        {{-- ── Baris 2: Kartu Reward Ditukar ──────────────────────── --}}
        <section class="mt-6">
            <div class="relative overflow-hidden rounded-[2rem] bg-white p-7 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400 mb-3">Reward Ditukar</p>
                        <p class="text-5xl font-black tracking-tight text-slate-800">
                            {{ number_format($totalRewardDitukar, 0, ',', '.') }}
                        </p>
                        <p class="mt-2 text-sm text-slate-400">total penukaran reward</p>
                    </div>
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-orange-100 text-3xl">
                        🎁
                </div>
                <div class="pointer-events-none absolute -bottom-6 -right-6 h-24 w-24 rounded-full bg-orange-50"></div>
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
