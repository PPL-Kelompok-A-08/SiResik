<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <header class="bg-slate-950 text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 py-8 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">Dashboard Petugas</p>
                <h1 class="mt-2 text-4xl font-black">Halo, {{ $user->name }}</h1>
                <p class="mt-2 text-sm text-slate-300">Pantau antrean permintaan penjemputan dan prioritas operasional lapangan.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('permintaan-penjemputan.index') }}" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-bold text-slate-950">Lihat antrean</a>
                <a href="{{ route('petugas.riwayat') }}" class="rounded-full border border-amber-300 px-5 py-3 text-sm font-bold text-amber-300 hover:bg-amber-300 hover:text-slate-950 transition">📋 Riwayat Tugas</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/20 px-5 py-3 text-sm font-bold text-white">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Jadwal hari ini</p><p class="mt-3 text-4xl font-black">{{ $stats['jadwal_hari_ini'] }}</p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Menunggu</p><p class="mt-3 text-4xl font-black text-sky-600">{{ $stats['menunggu'] }}</p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Diproses</p><p class="mt-3 text-4xl font-black text-amber-600">{{ $stats['diproses'] }}</p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Selesai</p><p class="mt-3 text-4xl font-black text-emerald-600">{{ $stats['selesai'] }}</p></div>
        </section>

        <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-600">Antrean Operasional</p>
            <h2 class="mt-2 text-2xl font-bold">Permintaan terbaru</h2>
            <div class="mt-6 grid gap-4">
                @forelse ($permintaan as $item)
                    <article class="rounded-3xl border border-slate-200 p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-lg font-bold">{{ $item->pengguna?->name ?? '-' }}</p>
                                <p class="text-sm text-slate-500">{{ $item->pengguna?->email ?? '-' }}</p>
                                <p class="mt-3 text-sm text-slate-600">{{ $item->alamat }}</p>
                                <p class="text-sm text-slate-600">{{ $item->jadwal }} | {{ $item->tanggal }}</p>
                            </div>
                            <div class="flex flex-col gap-2 items-end">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">{{ $item->status }}</span>
                                @if($item->status === 'Diproses' && $item->petugas_id === auth()->id())
                                    <a href="{{ route('petugas.bukti.show', $item) }}"
                                       class="rounded-xl bg-amber-400 px-3 py-1.5 text-xs font-bold text-slate-900 hover:bg-amber-500 transition whitespace-nowrap">
                                        📷 Upload Bukti
                                    </a>
                                @elseif($item->status === 'Selesai')
                                    <a href="{{ route('petugas.bukti.show', $item) }}"
                                       class="rounded-xl bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-200 transition whitespace-nowrap">
                                        ✅ Lihat Bukti
                                    </a>
                                @endif
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">Catatan: {{ $item->catatan }}</p>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center text-slate-500">Belum ada permintaan yang masuk.</div>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
