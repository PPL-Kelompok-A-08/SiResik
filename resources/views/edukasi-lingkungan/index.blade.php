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
    <x-sidebar />

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
                    'url'       => url('/edukasi-lingkungan/pengolahan-organik'),
                ],
                [
                    'kategori'  => 'DAUR ULANG',
                    'judul'     => 'Mengubah Sampah Plastik Menjadi Kerajinan Bernilai',
                    'ringkasan' => 'Botol plastik bekas bisa menjadi pot tanaman, lampu hias, atau bahan baku kerajinan yang memiliki nilai jual tinggi.',
                    'views'     => '1.832 Pembaca',
                    'img'       => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=700&q=80',
                    'url'       => url('/edukasi-lingkungan/daur-ulang-plastik'),
                ],
                [
                    'kategori'  => 'EDUKASI',
                    'judul'     => 'Mengenal Jenis Sampah dan Cara Pemilahan yang Benar',
                    'ringkasan' => 'Pemilahan sampah adalah langkah pertama dalam pengelolaan lingkungan. Pelajari perbedaan organik, anorganik, dan B3 agar daur ulang lebih efektif.',
                    'views'     => '3.140 Pembaca',
                    'img'       => 'https://images.unsplash.com/photo-1604187351574-c75ca79f5807?w=700&q=80',
                    'url'       => url('/edukasi-lingkungan/mengenal-pemilahan'),
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
                            {{-- Mengubah button menjadi tag anchor (link) --}}
                            <a href="{{ $article['url'] }}"
                               class="inline-block rounded-xl bg-emerald-500 px-5 py-2 text-sm font-bold text-white transition hover:bg-emerald-600">
                                Baca Selengkapnya
                            </a>
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