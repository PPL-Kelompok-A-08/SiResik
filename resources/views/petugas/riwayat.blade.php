<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Tugas Petugas - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

    {{-- HEADER --}}
    <header class="bg-slate-950 text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 py-8 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">SiResik · Petugas</p>
                <h1 class="mt-2 text-4xl font-black">Riwayat Tugas</h1>
                <p class="mt-2 text-sm text-slate-300">Semua permintaan penjemputan yang ditugaskan kepada Anda.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard.petugas') }}" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-bold text-slate-950">← Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/20 px-5 py-3 text-sm font-bold text-white">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10 space-y-8">

        {{-- STATISTIK --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-slate-900 p-5 text-white">
                <p class="text-sm text-slate-400">Total Tugas</p>
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
        </section>

        {{-- FILTER --}}
        <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 px-6 py-5">
            <form method="GET" action="{{ route('petugas.riwayat') }}" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Status</label>
                    <select name="status" class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                        <option value="">Semua Status</option>
                        @foreach(['Menunggu','Diproses','Selesai'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}"
                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
                <button type="submit" class="rounded-xl bg-amber-400 px-5 py-2 text-sm font-bold text-slate-900 hover:bg-amber-500 transition">Cari</button>
                @if(request()->anyFilled(['status','dari','sampai']))
                    <a href="{{ route('petugas.riwayat') }}" class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Reset</a>
                @endif
            </form>
        </section>

        {{-- DAFTAR TUGAS --}}
        <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="px-7 py-6 border-b border-slate-100">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-600">Daftar Tugas Penjemputan</p>
                <h2 class="mt-1 text-2xl font-bold">Semua Permintaan</h2>
            </div>

            @forelse ($permintaan as $item)
                @php
                    $statusClass = match($item->status) {
                        'Selesai'  => 'bg-emerald-100 text-emerald-800',
                        'Diproses' => 'bg-amber-100 text-amber-800',
                        default    => 'bg-sky-100 text-sky-800',
                    };
                    $canUpload = $item->status === 'Diproses' && $item->petugas_id === auth()->id();
                @endphp
                <article class="border-b border-slate-100 last:border-0 px-7 py-5 hover:bg-slate-50 transition">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-xs font-black text-slate-400">#{{ $item->id }}</span>
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $item->status }}</span>
                                @if($item->bukti_penyelesaian)
                                    <span class="rounded-full px-3 py-1 text-xs font-bold bg-violet-100 text-violet-700">📷 Ada Bukti</span>
                                @endif
                            </div>
                            <p class="mt-2 font-bold text-lg">{{ $item->pengguna?->name ?? '-' }}</p>
                            <p class="text-sm text-slate-500">{{ $item->pengguna?->email ?? '-' }}</p>
                            <div class="mt-2 flex flex-wrap gap-4 text-sm text-slate-600">
                                <span>📍 {{ $item->alamat }}</span>
                                <span>🗓 {{ $item->jadwal }}</span>
                                <span>📅 {{ $item->tanggal }}</span>
                            </div>
                            @if($item->catatan && $item->catatan !== '-')
                                <p class="mt-1 text-xs text-slate-400">Catatan: {{ $item->catatan }}</p>
                            @endif
                        </div>

                        <div class="flex flex-col gap-2 min-w-[160px]">
                            @if($canUpload)
                                <a href="{{ route('petugas.bukti.show', $item) }}"
                                   class="rounded-xl bg-amber-400 px-4 py-2 text-sm font-bold text-slate-900 text-center hover:bg-amber-500 transition">
                                    📷 Upload Bukti
                                </a>
                            @elseif($item->petugas_id === auth()->id() || auth()->user()->role === 'admin')
                                <a href="{{ route('petugas.bukti.show', $item) }}"
                                   class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 text-center hover:bg-slate-100 transition">
                                    Lihat Detail
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="px-7 py-16 text-center text-slate-400">
                    <div class="text-5xl mb-4">📋</div>
                    <p class="font-semibold">Belum ada tugas yang ditemukan.</p>
                    @if(request()->anyFilled(['status','dari','sampai']))
                        <p class="text-sm mt-1">Coba ubah filter pencarian.</p>
                    @endif
                </div>
            @endforelse
        </section>

        {{-- PAGINATION --}}
        @if($permintaan->hasPages())
            <div class="flex justify-center">
                {{ $permintaan->links() }}
            </div>
        @endif

    </main>
</body>
</html>
