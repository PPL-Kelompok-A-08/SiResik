<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Sampah Liar - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen xl:grid xl:grid-cols-[300px,1fr]">
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
                    ['label' => 'Dashboard', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Penjemputan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Status Layanan', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Riwayat Layanan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Poin & Reward', 'active' => false, 'href' => route('poin.index')],
                    ['label' => 'Sampah Liar', 'active' => true, 'href' => route('sampah-liar.index')],
                    ['label' => 'Peta & Lokasi', 'active' => false, 'href' => route('peta.lokasi')],
                    ['label' => 'Usulkan Titik', 'active' => false, 'href' => route('peta.usulan-titik')],
                    ['label' => 'Edukasi Lingkungan', 'active' => false, 'disabled' => true],
                    ['label' => 'Kegiatan Lingkungan', 'active' => false, 'disabled' => true],
                    ['label' => 'Notifikasi', 'active' => false, 'disabled' => true],
                ];
            @endphp

            <nav class="mt-14 space-y-2">
                @foreach ($menuItems as $item)
                    @if (!empty($item['href']))
                        <a href="{{ $item['href'] }}"
                            class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
                            <span class="text-xl">{{ $item['active'] ? '◉' : '◦' }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @else
                        <div class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-200 opacity-70 cursor-not-allowed">
                            <span class="text-xl">◦</span>
                            <span>{{ $item['label'] }}</span>
                        </div>
                    @endif
                @endforeach
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="mt-8">
                @csrf
                <button type="submit" class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-50 transition hover:bg-white/5">
                    <span class="text-xl">↪</span>
                    <span>Keluar (Log Out)</span>
                </button>
            </form>

            <div class="mt-10 rounded-3xl bg-white/5 px-4 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <p class="text-xl font-bold">{{ auth()->user()->name }}</p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-6 py-4 text-emerald-800">
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-800">Lapor Sampah Liar</h1>
                    <p class="mt-2 text-lg text-slate-500">Bantu kami menjaga kebersihan lingkungan dengan melaporkan titik pumpukan sampah liar.</p>
                </div>
                <a href="{{ route('sampah-liar.create') }}" class="rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">+ Buat Laporan</a>
            </header>

            <section class="mt-12 grid gap-8 xl:grid-cols-[1.5fr,1fr]">
                <!-- Laporan Saya -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-base font-black uppercase tracking-[0.2em] text-slate-400">Laporan Saya</p>
                            <h2 class="text-3xl font-bold text-slate-800 mt-1">Riwayat Laporan Sampah Liar</h2>
                        </div>
                    </div>

                    @forelse ($myReports as $report)
                        <div class="mb-5 rounded-2xl bg-white p-6 shadow-sm hover:shadow-md transition">
                            <div class="flex gap-5">
                                @if ($report->foto)
                                    <img src="{{ asset('storage/' . $report->foto) }}" alt="{{ $report->lokasi }}" class="w-24 h-24 rounded-xl object-cover flex-shrink-0">
                                @else
                                    <div class="w-24 h-24 rounded-xl bg-slate-200 flex items-center justify-center flex-shrink-0">
                                        <span class="text-4xl">📷</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-800">{{ $report->lokasi }}</h3>
                                            <p class="text-sm text-slate-500 mt-1">{{ $report->deskripsi }}</p>
                                            <div class="mt-3 flex gap-3">
                                                <span class="inline-block rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">{{ $report->jumlah_estimasi }} ember</span>
                                                <span class="inline-block rounded-full {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($report->status === 'diverifikasi' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }} px-3 py-1 text-sm font-semibold">{{ ucfirst($report->status) }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-xs text-slate-500">{{ $report->created_at->diffForHumans() }}</p>
                                            <a href="{{ route('sampah-liar.show', $report) }}" class="mt-2 inline-block text-emerald-600 hover:text-emerald-700 font-semibold">Lihat Detail →</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-white p-12 text-center shadow-sm">
                            <p class="text-2xl mb-2">📋</p>
                            <p class="text-slate-600 font-semibold mb-3">Anda belum membuat laporan sampah liar</p>
                            <p class="text-slate-500 mb-6">Mulai berkontribusi dengan membuat laporan pertama Anda sekarang</p>
                            <a href="{{ route('sampah-liar.create') }}" class="inline-block rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white hover:bg-emerald-600 transition">+ Buat Laporan Sekarang</a>
                        </div>
                    @endforelse

                    @if ($myReports->hasPages())
                        <div class="mt-6">
                            {{ $myReports->links() }}
                        </div>
                    @endif
                </div>

                <!-- Laporan di Sekitar Anda -->
                <div>
                    <p class="text-base font-black uppercase tracking-[0.2em] text-slate-400 mb-6">Laporan di Sekitar Anda</p>

                    <div class="space-y-4">
                        @forelse ($nearbyReports as $report)
                            <a href="{{ route('sampah-liar.show', $report) }}" class="group block rounded-2xl bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                                <div class="aspect-video overflow-hidden bg-slate-200">
                                    @if ($report->foto)
                                        <img src="{{ asset('storage/' . $report->foto) }}" alt="{{ $report->lokasi }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-5xl">📷</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start gap-2 mb-2">
                                        <span class="inline-block rounded-full bg-orange-100 text-orange-700 px-2 py-1 text-xs font-semibold">LAPORAN LOKAL</span>
                                    </div>
                                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">{{ $report->lokasi }}</h3>
                                    <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $report->deskripsi }}</p>
                                    <div class="mt-3 flex items-center gap-2 text-xs text-slate-600">
                                        <span>👤 {{ $report->pengguna->name }}</span>
                                        <span>📅 {{ $report->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-2xl bg-white p-8 text-center shadow-sm">
                                <p class="text-2xl mb-2">🌍</p>
                                <p class="text-slate-600 font-semibold">Belum ada laporan dari warga lain</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
