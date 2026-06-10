<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wawasan Lingkungan - SiResik</title>
    <meta name="description" content="Pusat edukasi lingkungan SiResik — pelajari cara hidup bersih dan berkelanjutan.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
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
                ['label' => 'Dashboard',           'icon' => '⊞', 'href' => route('dashboard.masyarakat'),         'active' => false],
                ['label' => 'Penjemputan',          'icon' => '⊕', 'href' => route('permintaan-penjemputan.index'), 'active' => false],
                ['label' => 'Status Layanan',       'icon' => '◎', 'href' => route('status-layanan.index'),         'active' => false],
                ['label' => 'Riwayat Layanan',      'icon' => '◉', 'href' => route('riwayat-layanan.index'),        'active' => false],
                ['label' => 'Poin & Reward',        'icon' => '◈', 'href' => route('poin.index'),                   'active' => false],
                ['label' => 'Sampah Liar',          'icon' => '⊗', 'href' => route('sampah-liar.index'),            'active' => false],
                ['label' => 'Peta & Lokasi',        'icon' => '⊙', 'href' => route('peta.lokasi'),                  'active' => false],
                ['label' => 'Usulkan Titik',        'icon' => '⊕', 'href' => route('peta.usulan-titik'),            'active' => false],
                ['label' => 'Edukasi Lingkungan',   'icon' => '◧', 'href' => route('edukasi-lingkungan.index'),     'active' => true],
                ['label' => 'Kegiatan Lingkungan',  'icon' => '◨', 'href' => route('kegiatan-lingkungan.index'),    'active' => false],
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

    {{-- ═══════════════════════ MAIN ═══════════════════════ --}}
    <main class="flex flex-col gap-5 px-7 py-6">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-black tracking-tight text-slate-800">Wawasan Lingkungan</h1>
            <button type="button"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Unduh Laporan
            </button>
        </header>

        {{-- Hero --}}
        <div class="flex flex-col items-center py-6 text-center">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-3xl">🌿</div>
            <h2 class="mt-4 text-2xl font-black text-slate-800">Pusat Edukasi SiResik</h2>
            <p class="mt-2 max-w-lg text-sm text-slate-500 leading-relaxed">
                Membangun budaya bersih bukan hanya soal membuang sampah, tapi bagaimana kita<br>
                memahami dampaknya dan cara pengelolaannya yang benar.
            </p>
        </div>

        {{-- ═══ Artikel List ═══ --}}
        @php
            $articles = [
                [
                    'kategori'  => 'PENGOLAHAN',
                    'judul'     => 'Cara Mengolah Sampah Organik di Rumah',
                    'ringkasan' => 'Pelajari teknik komposting sederhana menggunakan keranjang Takakura atau lubang biopori untuk mengubah sisa dapur menjadi pupuk bernutrisi.',
                    'views'     => '2.481 Pembaca',
                    'img'       => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=700&q=80',
                ],
                [
                    'kategori'  => 'DAUR ULANG',
                    'judul'     => 'Mengubah Sampah Plastik Menjadi Kerajinan Bernilai',
                    'ringkasan' => 'Botol plastik bekas bisa menjadi pot tanaman, lampu hias, atau bahan baku kerajinan yang memiliki nilai jual tinggi.',
                    'views'     => '1.832 Pembaca',
                    'img'       => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=700&q=80',
                ],
                [
                    'kategori'  => 'EDUKASI',
                    'judul'     => 'Mengenal Jenis Sampah dan Cara Pemilahan yang Benar',
                    'ringkasan' => 'Pemilahan sampah adalah langkah pertama dalam pengelolaan lingkungan. Pelajari perbedaan organik, anorganik, dan B3 agar daur ulang lebih efektif.',
                    'views'     => '3.140 Pembaca',
                    'img'       => 'https://images.unsplash.com/photo-1604187351574-c75ca79f5807?w=700&q=80',
                ],
                [
                    'kategori'  => 'LINGKUNGAN',
                    'judul'     => 'Dampak Sampah Plastik Terhadap Ekosistem Laut',
                    'ringkasan' => 'Setiap tahun lebih dari 8 juta ton plastik masuk ke lautan. Pelajari bagaimana dampaknya terhadap rantai makanan dan apa yang bisa kita lakukan.',
                    'views'     => '4.215 Pembaca',
                    'img'       => 'https://images.unsplash.com/photo-1488531258993-0b76cd4f10d7?w=700&q=80',
                ],
            ];
            $badgeColors = [
                'PENGOLAHAN' => 'bg-emerald-100 text-emerald-700',
                'DAUR ULANG' => 'bg-blue-100 text-blue-700',
                'EDUKASI'    => 'bg-purple-100 text-purple-700',
                'LINGKUNGAN' => 'bg-amber-100 text-amber-700',
            ];
        @endphp

        <div class="space-y-4">
            @foreach ($articles as $article)
                <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 lg:flex">
                    {{-- Image --}}
                    <div class="lg:w-[40%] shrink-0">
                        <img src="{{ $article['img'] }}"
                             alt="{{ $article['judul'] }}"
                             class="h-56 w-full object-cover lg:h-full">
                    </div>
                    {{-- Content --}}
                    <div class="flex flex-col justify-center gap-3 p-6">
                        <span class="w-fit rounded-full px-3 py-1 text-[10px] font-black uppercase tracking-widest
                                     {{ $badgeColors[$article['kategori']] ?? 'bg-slate-100 text-slate-600' }}">
                            🌿 {{ $article['kategori'] }}
                        </span>
                        <h3 class="text-xl font-black text-slate-800 leading-snug">{{ $article['judul'] }}</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $article['ringkasan'] }}</p>
                        <div class="flex items-center gap-4 mt-1">
                            <button type="button"
                                    class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-bold text-white transition hover:bg-emerald-600">
                                Baca Selengkapnya
                            </button>
                            <span class="text-xs text-slate-400">👁 {{ $article['views'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </main>
</div>
</body>
</html>
