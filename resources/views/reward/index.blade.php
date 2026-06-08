<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Reward - SiResik</title>
    <meta name="description" content="Tukarkan poin Anda dengan berbagai reward menarik di SiResik.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Kartu reward hover */
        .reward-card {
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .reward-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.10);
        }
        /* Wave accent */
        .wave-bottom {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            pointer-events: none;
            line-height: 0;
            overflow: hidden;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

<div class="min-h-screen xl:grid xl:grid-cols-[300px,1fr]">

    {{-- ============================================================
         SIDEBAR (konsisten dengan poin/index.blade.php)
    ============================================================ --}}
    <aside class="bg-[#0c5b49] px-6 py-8 text-white">
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

        <form action="{{ route('logout') }}" method="POST" class="mt-8">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-50 transition hover:bg-white/5">
                <span class="text-xl">↪</span>
                <span>Keluar (Log Out)</span>
            </button>
        </form>

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
    <main class="relative px-6 py-8 lg:px-10 overflow-hidden" x-data="{ modalOpen: false, selectedReward: null }">

        {{-- ── Header ──────────────────────────────────────────── --}}
        <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-slate-400 mb-2">
                    <a href="{{ route('poin.index') }}" class="hover:text-emerald-600 transition">Poin & Reward</a>
                    <span>›</span>
                    <span class="text-slate-600 font-medium">Tukarkan Poin</span>
                </div>
                <h1 class="text-5xl font-black tracking-tight text-slate-900">Tukarkan Poin</h1>
                <p class="mt-2 text-lg text-slate-500">Pilih reward menarik dan tukarkan poin Anda sekarang</p>
            </div>

            {{-- Poin saat ini --}}
            <div class="flex-shrink-0 rounded-2xl bg-[#0c5b49] px-7 py-5 text-white shadow-lg">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-emerald-300">Poin Anda</p>
                <p class="mt-1 text-4xl font-black tracking-tight">
                    {{ number_format($totalPoin, 0, ',', '.') }}
                    <span class="text-lg font-bold text-emerald-300">PTS</span>
                </p>
                <a href="{{ route('poin.index') }}" class="mt-1 block text-xs text-emerald-200 hover:text-white transition">← Kembali ke Poin & Reward</a>
            </div>
        </header>

        {{-- ── Flash messages ──────────────────────────────────── --}}
        @if(session('success'))
            <div class="mt-6 flex items-center gap-4 rounded-2xl bg-emerald-100 px-6 py-4 text-emerald-800 shadow-sm">
                <span class="text-2xl">🎉</span>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mt-6 flex items-center gap-4 rounded-2xl bg-red-100 px-6 py-4 text-red-800 shadow-sm">
                <span class="text-2xl">⚠️</span>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        {{-- ── Judul seksi katalog ──────────────────────────────── --}}
        <div class="mt-10">
            <h2 class="text-2xl font-black text-slate-800 mb-6">Pilih Reward Menarik</h2>

            {{-- ── Grid Kartu Reward ──────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @forelse($rewards as $reward)
                    @php
                        $canAfford = $totalPoin >= $reward->poin_diperlukan;
                        $nameLower = strtolower($reward->nama);

                        // Pilih ikon & warna berdasarkan nama reward
                        if (str_contains($nameLower, 'ovo') || str_contains($nameLower, 'gopay') || str_contains($nameLower, 'dana') || str_contains($nameLower, 'saldo')) {
                            $icon = '📱'; $iconBg = 'bg-purple-100'; $iconColor = 'text-purple-600';
                        } elseif (str_contains($nameLower, 'listrik') || str_contains($nameLower, 'token') || str_contains($nameLower, 'pln')) {
                            $icon = '⚡'; $iconBg = 'bg-orange-100'; $iconColor = 'text-orange-500';
                        } elseif (str_contains($nameLower, 'minyak') || str_contains($nameLower, 'goreng') || str_contains($nameLower, 'sembako')) {
                            $icon = '🛒'; $iconBg = 'bg-violet-100'; $iconColor = 'text-violet-600';
                        } elseif (str_contains($nameLower, 'data') || str_contains($nameLower, 'kuota') || str_contains($nameLower, 'internet')) {
                            $icon = '🌐'; $iconBg = 'bg-sky-100'; $iconColor = 'text-sky-500';
                        } elseif (str_contains($nameLower, 'bibit') || str_contains($nameLower, 'tanaman') || str_contains($nameLower, 'pohon')) {
                            $icon = '🌱'; $iconBg = 'bg-emerald-100'; $iconColor = 'text-emerald-600';
                        } elseif (str_contains($nameLower, 'voucher') || str_contains($nameLower, 'belanja')) {
                            $icon = '🎫'; $iconBg = 'bg-pink-100'; $iconColor = 'text-pink-500';
                        } else {
                            $icon = '🎁'; $iconBg = 'bg-amber-100'; $iconColor = 'text-amber-500';
                        }
                    @endphp

                    <article class="reward-card relative bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-200 flex flex-col items-center text-center
                                    {{ !$canAfford ? 'opacity-70' : '' }}">

                        {{-- Badge stok menipis --}}
                        @if($reward->stok < 10)
                            <span class="absolute top-3 right-3 rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-black text-red-600">
                                Sisa {{ $reward->stok }}
                            </span>
                        @endif

                        {{-- Ikon --}}
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl {{ $iconBg }} text-4xl mb-4">
                            {{ $icon }}
                        </div>

                        {{-- Nama --}}
                        <h3 class="text-base font-black text-slate-800 leading-tight">{{ $reward->nama }}</h3>

                        {{-- Deskripsi singkat --}}
                        @if($reward->deskripsi)
                            <p class="mt-1 text-xs text-slate-400 line-clamp-2">{{ $reward->deskripsi }}</p>
                        @endif

                        {{-- Poin --}}
                        <p class="mt-3 text-sm font-black text-emerald-600 tracking-wide">
                            {{ number_format($reward->poin_diperlukan, 0, ',', '.') }} POIN
                        </p>

                        {{-- Tombol --}}
                        <div class="mt-4 w-full">
                            @if($canAfford)
                                <button
                                    @click="selectedReward = { id: {{ $reward->id }}, nama: '{{ addslashes($reward->nama) }}', poin: {{ $reward->poin_diperlukan }} }; modalOpen = true"
                                    class="w-full rounded-xl bg-[#0c5b49] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#0a4f3f] transition">
                                    Tukarkan
                                </button>
                            @else
                                <button disabled
                                    class="w-full rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-400 cursor-not-allowed">
                                    Poin Kurang
                                </button>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white py-16 text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-3xl mb-4">🎁</div>
                        <p class="text-base font-bold text-slate-500">Belum ada reward tersedia</p>
                        <p class="text-sm text-slate-400 mt-1">Pantau terus untuk reward baru!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Spacer --}}
        <div class="h-20"></div>

        {{-- ── Aksen Gelombang ──────────────────────────────────── --}}
        <div class="wave-bottom">
            <svg viewBox="0 0 1440 90" fill="none" xmlns="http://www.w3.org/2000/svg"
                 style="width:100%; height:90px; opacity:0.08;">
                <path d="M0 45C240 80 480 10 720 45C960 80 1200 10 1440 45L1440 90H0Z" fill="#0c5b49"/>
                <path d="M0 60C360 25 720 85 1080 55C1260 40 1380 70 1440 60L1440 90H0Z"
                      fill="#16a34a" opacity="0.6"/>
            </svg>
        </div>

        {{-- ── Logo SiResik sudut kanan bawah ──────────────────── --}}
        <div class="absolute bottom-4 right-6 flex items-center gap-1.5 opacity-30 pointer-events-none select-none">
            <span class="text-base">♻</span>
            <span class="text-sm font-black tracking-tight text-[#0c5b49]">SiResik</span>
        </div>

        {{-- ============================================================
             MODAL KONFIRMASI PENUKARAN
        ============================================================ --}}
        <div x-show="modalOpen"
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="display: none;">

            {{-- Backdrop --}}
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
                 @click="modalOpen = false">
            </div>

            {{-- Modal panel --}}
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative z-10 w-full max-w-sm mx-4 rounded-3xl bg-white shadow-2xl p-8">

                <div class="text-center">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-3xl">
                        🎁
                    </div>
                    <h3 class="text-2xl font-black text-slate-800">Konfirmasi Penukaran</h3>
                    <p class="mt-3 text-base text-slate-500">
                        Tukar <strong class="text-slate-700" x-text="selectedReward?.poin?.toLocaleString('id-ID')"></strong> poin
                        untuk mendapatkan <strong class="text-slate-700" x-text="selectedReward?.nama"></strong>?
                    </p>
                    <p class="mt-1 text-sm text-slate-400">Penukaran tidak dapat dibatalkan setelah dikonfirmasi.</p>
                </div>

                <form x-bind:action="`/reward/${selectedReward?.id}/redeem`" method="POST" class="mt-8 flex gap-3">
                    @csrf
                    <button type="button"
                            @click="modalOpen = false"
                            class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 rounded-xl bg-[#0c5b49] px-4 py-3 text-sm font-bold text-white hover:bg-[#0a4f3f] transition">
                        Ya, Tukarkan
                    </button>
                </form>
            </div>
        </div>

    </main>
</div>

</body>
</html>
