<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal Penjemputan - SiResik</title>
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

            <div class="mt-12">
                <p class="text-sm font-black uppercase tracking-[0.2em] text-emerald-300">Mode Supervisi</p>
                <div class="mt-4 flex items-center justify-between rounded-2xl bg-emerald-600/70 px-4 py-3">
                    <span class="text-sm font-bold">Akses: Administrator</span>
                    <span class="text-lg">⌄</span>
                </div>
            </div>

            @php
                $menuItems = [
                    ['label' => 'Admin Dashboard', 'active' => false],
                    ['label' => 'Kelola Jadwal', 'active' => true],
                    ['label' => 'Verifikasi Laporan', 'active' => false],
                    ['label' => 'Kategori & Reward', 'active' => false],
                    ['label' => 'Kelola Reward', 'active' => false],
                    ['label' => 'Area Layanan', 'active' => false],
                    ['label' => 'Pantau Petugas', 'active' => false],
                    ['label' => 'Riwayat Layanan', 'active' => false],
                    ['label' => 'Laporan Periodik', 'active' => false],
                    ['label' => 'Konten Edukasi', 'active' => false],
                    ['label' => 'Broadcast', 'active' => false],
                ];
            @endphp

            <nav class="mt-10 space-y-2">
                @foreach ($menuItems as $item)
                    <a href="{{ $item['active'] ? route('dashboard.admin') : '#' }}"
                        class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
                        <span class="text-xl">{{ $item['active'] ? '▣' : '◦' }}</span>
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
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">R</div>
                    <div>
                        <p class="text-xl font-bold">{{ $user->name }}</p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Kelola Jadwal Penjemputan</h1>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700">Unduh Laporan</button>
                    <a href="{{ route('permintaan-penjemputan.index') }}" class="rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white">+ Ajukan Penjemputan</a>
                </div>
            </header>

            @if (session('success'))
                <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                    <p class="font-semibold">Penjadwalan belum bisa diproses.</p>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="mt-8 overflow-hidden rounded-[2rem] bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                    <h2 class="text-2xl font-black text-slate-800">Daftar Permintaan Penjemputan (Menunggu)</h2>
                    <span class="rounded-2xl bg-amber-100 px-4 py-2 text-sm font-black uppercase tracking-[0.1em] text-amber-600">{{ $stats['menunggu'] }} Antrean</span>
                </div>

                <div class="divide-y divide-slate-200">
                    @forelse ($pendingRequests as $index => $item)
                        <article class="grid gap-6 px-6 py-6 xl:grid-cols-[1.2fr,0.9fr] xl:items-center">
                            <div>
                                <div class="flex flex-wrap items-center gap-4">
                                    <span class="text-2xl font-black text-emerald-500">REQ-{{ 101 + $index }}</span>
                                    <h3 class="text-3xl font-black text-slate-800">{{ $item->pengguna?->name }}</h3>
                                </div>
                                <p class="mt-3 text-xl text-slate-500">{{ $item->alamat }}</p>
                                <p class="mt-2 text-lg text-slate-400">{{ $item->nomor_telepon }}</p>
                                <p class="mt-3 text-lg text-slate-400">
                                    Item:
                                    @foreach ($item->items as $detail)
                                        <span class="font-semibold text-slate-500">{{ $detail->kategoriSampah?->nama }}</span>{{ $loop->last ? '' : ', ' }}
                                    @endforeach
                                    <span class="text-slate-300">•</span>
                                    Diajukan {{ $item->created_at?->diffForHumans() }}
                                </p>
                            </div>

                            <form action="{{ route('dashboard.admin.schedule', $item) }}" method="POST" class="grid gap-3 md:grid-cols-[1.2fr,1fr,auto] md:items-end">
                                @csrf
                                <label class="block">
                                    <span class="mb-2 block text-sm font-black uppercase tracking-[0.2em] text-slate-400">Input Jadwal</span>
                                    <input
                                        type="datetime-local"
                                        name="scheduled_at"
                                        value="{{ old('scheduled_at') }}"
                                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base outline-none transition focus:border-emerald-400"
                                        required
                                    >
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-black uppercase tracking-[0.2em] text-slate-400">Pilih Petugas</span>
                                    <select name="petugas_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base outline-none transition focus:border-emerald-400" required>
                                        <option value="">Pilih Petugas</option>
                                        @foreach ($petugas as $petugasItem)
                                            <option value="{{ $petugasItem->id }}">{{ $petugasItem->name }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <button type="submit" class="rounded-2xl bg-emerald-500 px-6 py-3 text-lg font-bold text-white shadow-lg shadow-emerald-500/20">
                                    Jadwalkan
                                </button>
                            </form>
                        </article>
                    @empty
                        <div class="px-6 py-16 text-center text-slate-500">Tidak ada permintaan dengan status menunggu.</div>
                    @endforelse
                </div>
            </section>

            <section class="mt-8 overflow-hidden rounded-[2rem] bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                    <h2 class="text-2xl font-black text-slate-800">Master Jadwal Reguler Mingguan</h2>
                    <button type="button" class="rounded-2xl bg-emerald-500 px-5 py-3 text-base font-bold text-white shadow-lg shadow-emerald-500/20">+ Tambah Jadwal Area</button>
                </div>

                <div class="grid gap-5 px-6 py-6 md:grid-cols-2 xl:grid-cols-3">
                    @php
                        $weeklySchedules = [
                            ['hari' => 'Senin', 'zona' => 'Zona A', 'jam' => '08:00 WIB', 'petugas' => 'Ahmad'],
                            ['hari' => 'Selasa', 'zona' => 'Zona B', 'jam' => '08:00 WIB', 'petugas' => 'Bambang'],
                            ['hari' => 'Rabu', 'zona' => 'Zona A', 'jam' => '08:00 WIB', 'petugas' => 'Cecep'],
                        ];
                    @endphp

                    @foreach ($weeklySchedules as $schedule)
                        <article class="rounded-[2rem] border border-slate-200 bg-slate-50 p-5">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-base font-black uppercase tracking-[0.1em] text-emerald-500">{{ $schedule['hari'] }}</p>
                                <p class="text-sm font-bold text-slate-400">{{ $schedule['jam'] }}</p>
                            </div>
                            <h3 class="mt-3 text-3xl font-black text-slate-800">{{ $schedule['zona'] }}</h3>
                            <p class="mt-5 text-base text-slate-500">• Petugas: {{ $schedule['petugas'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-2xl font-black text-slate-800">Jadwal Aktif Terbaru</h2>
                    <span class="text-sm font-semibold text-slate-400">Diproses</span>
                </div>

                <div class="mt-6 grid gap-4 xl:grid-cols-2">
                    @forelse ($scheduledRequests as $item)
                        <article class="rounded-3xl border border-slate-200 p-5">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-2xl font-black text-slate-800">{{ $item->pengguna?->name }}</p>
                                    <p class="mt-2 text-base text-slate-500">{{ $item->alamat }}</p>
                                    <p class="mt-2 text-base text-slate-500">Petugas: {{ $item->petugas?->name ?? '-' }}</p>
                                    <p class="mt-2 text-base text-slate-500">Jadwal: {{ optional($item->scheduled_at ? \Illuminate\Support\Carbon::parse($item->scheduled_at) : null)?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                                </div>
                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-700">{{ $item->status }}</span>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center text-slate-500 xl:col-span-2">
                            Belum ada penjadwalan aktif.
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
</body>
</html>
