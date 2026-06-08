<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="flex min-h-screen">
        <aside class="hidden w-80 flex-col bg-slate-950 text-white lg:flex">
            <div class="flex items-center gap-4 border-b border-slate-800 px-7 py-8">
                <div class="h-12 w-12 rounded-[1.5rem] bg-emerald-500 flex items-center justify-center text-xl font-black text-white">S</div>
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Sistem Informasi Resik</p>
                    <p class="mt-2 text-lg font-black">SiResik</p>
                </div>
            </div>

            <nav class="flex flex-col gap-3 px-7 py-8">
                <a href="{{ route('dashboard.petugas') }}" class="flex items-center justify-between rounded-[1.75rem] bg-emerald-600 px-5 py-4 text-sm font-bold text-white shadow-lg shadow-emerald-900/20">
                    <span>Tugas Saya</span>
                    <span class="text-xl">▣</span>
                </a>
                <a href="{{ route('petugas.riwayat') }}" class="flex items-center rounded-[1.75rem] border border-slate-800 bg-slate-900 px-5 py-4 text-sm font-semibold text-slate-200 hover:bg-slate-800 transition">
                    <span>Riwayat Tugas</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center rounded-[1.75rem] border border-slate-800 bg-slate-900 px-5 py-4 text-sm font-semibold text-slate-200 hover:bg-slate-800 transition">Keluar (Log Out)</button>
                </form>
            </nav>

            <div class="mt-auto border-t border-slate-800 px-7 py-8">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Petugas Resik</p>
                <p class="mt-2 text-lg font-black">{{ $user->name }}</p>
                <p class="text-sm text-slate-400">Field Operator</p>
            </div>
        </aside>

        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 py-8 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-400">Pusat Tugas Petugas</p>
                        <h1 class="mt-3 text-4xl font-black text-slate-950">Halo, Petugas SiResik</h1>
                        <p class="mt-2 text-sm text-slate-600">Pantau antrean tugas penjemputan tanpa mengubah tampilan pengguna lain.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('permintaan-penjemputan.index') }}" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Unduh Laporan</a>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-6 py-8">
                @if(session('success'))
                    <div class="mb-6 rounded-[1.75rem] bg-emerald-50 border border-emerald-200 p-4 text-emerald-700 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <p class="text-sm text-slate-500">Jadwal hari ini</p>
                        <p class="mt-5 text-4xl font-black text-slate-950">{{ $stats['jadwal_hari_ini'] }}</p>
                    </div>
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <p class="text-sm text-slate-500">Pending</p>
                        <p class="mt-5 text-4xl font-black text-sky-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <p class="text-sm text-slate-500">Ongoing</p>
                        <p class="mt-5 text-4xl font-black text-amber-600">{{ $stats['ongoing'] }}</p>
                    </div>
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <p class="text-sm text-slate-500">Completed</p>
                        <p class="mt-5 text-4xl font-black text-emerald-600">{{ $stats['completed'] }}</p>
                    </div>
                </section>

                <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-600">Pusat Tugas Petugas</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950">Permintaan terbaru</h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" data-filter="all" onclick="filterTasks('all')" class="filter-button rounded-full border border-slate-200 bg-slate-950 px-5 py-2 text-sm font-bold text-white">All</button>
                            <button type="button" data-filter="pending" onclick="filterTasks('pending')" class="filter-button rounded-full border border-slate-200 bg-slate-100 px-5 py-2 text-sm font-bold text-slate-700">Pending</button>
                            <button type="button" data-filter="ongoing" onclick="filterTasks('ongoing')" class="filter-button rounded-full border border-slate-200 bg-slate-100 px-5 py-2 text-sm font-bold text-slate-700">Ongoing</button>
                            <button type="button" data-filter="completed" onclick="filterTasks('completed')" class="filter-button rounded-full border border-slate-200 bg-slate-100 px-5 py-2 text-sm font-bold text-slate-700">Completed</button>
                        </div>
                    </div>

                    <div class="mt-8 grid gap-4" id="task-list">
                        @forelse ($permintaan as $item)
                            @php
                                $statusKey = $item->status === 'Menunggu' ? 'pending' : ($item->status === 'Diproses' ? 'ongoing' : 'completed');
                                $category = $item->items->pluck('kategoriSampah.nama')->filter()->values();
                            @endphp

                            <article class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-sm" data-status="{{ $statusKey }}">
                                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="min-w-0 space-y-4">
                                        <div class="flex flex-wrap gap-3 text-sm font-bold uppercase tracking-[0.2em] text-slate-500">
                                            <span class="rounded-full bg-sky-100 px-3 py-1 text-sky-700">PICKUP</span>
                                            <span class="text-slate-400">ID: JOB-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-black text-slate-950">{{ $item->pengguna?->name ?? 'Masyarakat Demo' }}</h3>
                                            <p class="mt-1 text-sm text-slate-500">{{ $item->pengguna?->email ?? 'masyarakat@siresik.local' }}</p>
                                        </div>
                                        <div class="rounded-[1.5rem] bg-white p-4 text-sm text-slate-600 shadow-sm">
                                            <p>{{ $item->alamat ?: 'Alamat tidak tersedia' }}</p>
                                            <p class="mt-2">{{ $item->jadwal ?: '08.00-10.00' }} | {{ $item->tanggal }}</p>
                                        </div>
                                    </div>

                                    <div class="flex min-w-[180px] flex-col gap-3 items-start lg:items-end">
                                        <span class="inline-flex rounded-full bg-slate-100 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-700">{{ $item->status }}</span>

                                        @if($item->status === 'Menunggu' && is_null($item->petugas_id))
                                            <form action="{{ route('petugas.terima', $item) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="w-full rounded-full bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition">Terima Tugas</button>
                                            </form>
                                        @elseif($item->status === 'Diproses' && $item->petugas_id === auth()->id())
                                            <a href="{{ route('petugas.bukti.show', $item) }}" class="w-full rounded-full bg-amber-500 px-5 py-3 text-sm font-bold text-slate-950 hover:bg-amber-600 transition text-center">Bukti Foto</a>
                                        @elseif($item->status === 'Selesai')
                                            <span class="inline-flex rounded-full bg-emerald-100 px-5 py-3 text-sm font-bold text-emerald-700">Selesai</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-5 py-3 text-sm font-bold text-slate-700">Menunggu penugasan</span>
                                        @endif

                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Petugas: {{ $item->petugas?->name ?? 'Belum ditugaskan' }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-sm text-slate-500">Jenis sampah: {{ $category->join(', ') ?: 'Tidak tersedia' }}</p>
                                    <p class="text-sm text-slate-500">{{ $item->diselesaikan_at ? 'Selesai ' . \Illuminate\Support\Carbon::parse($item->diselesaikan_at)->format('H:i WIB') : '' }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-slate-500">
                                Belum ada permintaan yang tersedia.
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>
        </div>
    </div>

    <script>
        function filterTasks(status) {
            document.querySelectorAll('[data-status]').forEach(card => {
                card.style.display = status === 'all' || card.dataset.status === status ? '' : 'none';
            });
            document.querySelectorAll('[data-filter]').forEach(button => {
                const active = button.dataset.filter === status;
                button.classList.toggle('bg-slate-950', active);
                button.classList.toggle('text-white', active);
                button.classList.toggle('bg-slate-100', !active);
                button.classList.toggle('text-slate-700', !active);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            filterTasks('all');
        });
    </script>
</body>
</html>
