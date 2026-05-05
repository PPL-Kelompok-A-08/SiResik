<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Layanan Penjemputan - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen xl:grid xl:grid-cols-[300px,1fr]">

        {{-- SIDEBAR --}}
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
                    ['label' => 'Dashboard',         'href' => route('dashboard.masyarakat'), 'active' => false],
                    ['label' => 'Penjemputan',        'href' => route('permintaan-penjemputan.index'), 'active' => false],
                    ['label' => 'Status Layanan',     'href' => route('dashboard.masyarakat'), 'active' => false],
                    ['label' => 'Riwayat Layanan',    'href' => route('riwayat-layanan.index'), 'active' => true],
                    ['label' => 'Poin & Reward',      'href' => route('poin.index'), 'active' => false],
                    ['label' => 'Peta & Lokasi',      'href' => route('peta.lokasi'), 'active' => false],
                    ['label' => 'Usulkan Titik',      'href' => route('peta.usulan-titik'), 'active' => false],
                ];
            @endphp

            <nav class="mt-14 space-y-2">
                @foreach ($menuItems as $item)
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition
                              {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
                        <span class="text-xl">{{ $item['active'] ? '◉' : '◦' }}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
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

        {{-- KONTEN UTAMA --}}
        <main class="px-6 py-8 lg:px-10">

            {{-- Header --}}
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.25em] text-emerald-600">PBI 4</p>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Riwayat Layanan</h1>
                    <p class="mt-2 text-lg text-slate-500">Semua riwayat permintaan penjemputan sampah Anda.</p>
                </div>
                <a href="{{ route('permintaan-penjemputan.index') }}"
                   class="rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white hover:bg-emerald-600 transition">
                    + Ajukan Penjemputan
                </a>
            </header>

            {{-- STATISTIK --}}
            <section class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-3xl bg-[#0c5b49] p-5 text-white">
                    <p class="text-sm text-emerald-200">Total</p>
                    <p class="mt-3 text-4xl font-black">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Menunggu</p>
                    <p class="mt-3 text-4xl font-black text-sky-600">{{ $stats['menunggu'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Diproses</p>
                    <p class="mt-3 text-4xl font-black text-amber-600">{{ $stats['diproses'] }}</p>
                </div>
                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm text-slate-500">Selesai</p>
                    <p class="mt-3 text-4xl font-black text-emerald-600">{{ $stats['selesai'] }}</p>
                </div>
                <div class="rounded-3xl bg-emerald-50 p-5 ring-1 ring-emerald-200">
                    <p class="text-sm text-emerald-600">Total Poin Didapat</p>
                    <p class="mt-3 text-3xl font-black text-emerald-700">{{ number_format($stats['total_poin']) }}</p>
                </div>
            </section>

            {{-- FILTER --}}
            <section class="mt-8 rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 px-6 py-5">
                <form method="GET" action="{{ route('riwayat-layanan.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Status</label>
                        <select name="status" class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                            <option value="">Semua Status</option>
                            @foreach(['Menunggu','Diproses','Selesai'] as $s)
                                <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                               class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}"
                               class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    </div>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-600 transition">Cari</button>
                    @if(request()->anyFilled(['status','dari','sampai']))
                        <a href="{{ route('riwayat-layanan.index') }}" class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Reset</a>
                    @endif
                </form>
            </section>

            {{-- DAFTAR RIWAYAT --}}
            <section class="mt-6 space-y-4">
                @forelse ($riwayat as $item)
                    @php
                        $statusMeta = match ($item->status) {
                            'Selesai'  => ['color' => 'bg-emerald-100 text-emerald-800', 'icon' => '✅'],
                            'Diproses' => ['color' => 'bg-amber-100 text-amber-800',     'icon' => '🔄'],
                            default    => ['color' => 'bg-sky-100 text-sky-800',          'icon' => '⏳'],
                        };
                    @endphp
                    <article class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex-1">
                                {{-- Header card --}}
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="text-sm {{ $statusMeta['icon'] }}">{{ $statusMeta['icon'] }}</span>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusMeta['color'] }}">{{ $item->status }}</span>
                                    <span class="text-xs text-slate-400">#{{ $item->id }}</span>
                                    @if($item->bukti_penyelesaian)
                                        <span class="rounded-full px-2 py-1 text-xs font-bold bg-violet-100 text-violet-700">📷 Ada Bukti</span>
                                    @endif
                                </div>

                                {{-- Info utama --}}
                                <div class="mt-3 grid gap-1 sm:grid-cols-2">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Tanggal Pengajuan</p>
                                        <p class="text-sm font-semibold">{{ $item->tanggal }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Jadwal</p>
                                        <p class="text-sm font-semibold">{{ $item->jadwal }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Alamat</p>
                                        <p class="text-sm">{{ $item->alamat }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Petugas</p>
                                        <p class="text-sm">{{ $item->petugas?->name ?? 'Belum ditugaskan' }}</p>
                                    </div>
                                </div>

                                {{-- Kategori sampah --}}
                                @if($item->items->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($item->items as $i)
                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700">
                                                {{ $i->kategoriSampah?->nama }} · {{ $i->berat_kg }} kg
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Poin & Tombol --}}
                            <div class="flex flex-col items-end gap-3 min-w-[150px]">
                                <div class="rounded-2xl bg-emerald-50 px-4 py-3 text-right">
                                    <p class="text-xs text-emerald-600 font-semibold">Estimasi Poin</p>
                                    <p class="text-2xl font-black text-emerald-700">{{ number_format($item->total_estimasi_poin) }}</p>
                                </div>
                                <div class="rounded-2xl bg-rose-50 px-4 py-3 text-right">
                                    <p class="text-xs text-rose-600 font-semibold">Tagihan</p>
                                    <p class="text-lg font-black text-rose-700">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('riwayat-layanan.show', $item) }}"
                                   class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition w-full text-center">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>

                        {{-- Progress bar status --}}
                        <div class="h-1.5 bg-slate-100">
                            <div class="h-full rounded-full transition-all duration-500
                                @if($item->status === 'Menunggu') bg-sky-400 w-1/3
                                @elseif($item->status === 'Diproses') bg-amber-400 w-2/3
                                @else bg-emerald-500 w-full
                                @endif">
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white py-20 text-center">
                        <div class="text-5xl mb-4">📭</div>
                        <p class="text-lg font-bold text-slate-600">Belum ada riwayat layanan.</p>
                        <p class="text-sm text-slate-400 mt-2">Riwayat penjemputan Anda akan muncul di sini.</p>
                        <a href="{{ route('permintaan-penjemputan.index') }}"
                           class="mt-6 inline-block rounded-2xl bg-emerald-500 px-6 py-3 font-bold text-white hover:bg-emerald-600 transition">
                            + Ajukan Penjemputan Pertama
                        </a>
                    </div>
                @endforelse
            </section>

            {{-- PAGINATION --}}
            @if($riwayat->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $riwayat->links() }}
                </div>
            @endif

        </main>
    </div>
</body>
</html>
