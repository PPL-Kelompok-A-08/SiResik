<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Layanan – SiResik</title>
    <meta name="description" content="Daftar lengkap riwayat penjemputan sampah Anda di SiResik.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- Sidebar Konsisten --}}
    <x-sidebar />

    {{-- ═══════════════════ MAIN CONTENT ═══════════════════ --}}
    <main class="flex flex-col gap-6 px-6 py-6 lg:px-8">

        {{-- Page Header --}}
        <header>
            <h1 class="text-2xl font-black tracking-tight text-slate-800">Riwayat Layanan</h1>
            <p class="mt-1 text-sm text-slate-500">Daftar lengkap transaksi penjemputan sampah Anda.</p>
        </header>

        {{-- Statistik Singkat --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total</p>
                <p class="mt-1 text-3xl font-black text-slate-800">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-amber-500">Menunggu</p>
                <p class="mt-1 text-3xl font-black text-amber-600">{{ $stats['menunggu'] }}</p>
            </div>
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-teal-500">Diproses</p>
                <p class="mt-1 text-3xl font-black text-teal-600">{{ $stats['diproses'] }}</p>
            </div>
            <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-500">Selesai</p>
                <p class="mt-1 text-3xl font-black text-emerald-600">{{ $stats['selesai'] }}</p>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <section class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">

            {{-- Header Tabel --}}
            <div class="flex flex-col gap-3 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100">
                <div>
                    <h2 class="text-base font-black text-slate-800">Semua Transaksi</h2>
                </div>
                {{-- Filter Status --}}
                <form method="GET" action="{{ route('riwayat-layanan.index') }}" class="flex items-center gap-2 flex-wrap">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="Menunggu"  {{ request('status') === 'Menunggu'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses"  {{ request('status') === 'Diproses'  ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai"   {{ request('status') === 'Selesai'   ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @if(request('status'))
                        <a href="{{ route('riwayat-layanan.index') }}"
                           class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-500 hover:bg-slate-50">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50/60">
                        <tr>
                            <th class="px-5 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">ID</th>
                            <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Tanggal</th>
                            <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Alamat</th>
                            <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Item Sampah</th>
                            <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-wider text-slate-400">Poin</th>
                            <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-wider text-slate-400">Tagihan</th>
                            <th class="px-4 py-3 text-center text-[11px] font-black uppercase tracking-wider text-slate-400">Status</th>
                            <th class="px-5 py-3 text-center text-[11px] font-black uppercase tracking-wider text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($riwayat as $item)
                            @php
                                $trkId = 'TRK-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);

                                $detailItems = $item->items->map(function ($i) {
                                    $berat = number_format($i->berat_kg, 1);
                                    return ($i->kategoriSampah?->nama ?? '-') . ' (' . $berat . ' kg)';
                                })->take(2)->implode(', ');

                                if ($item->items->count() > 2) {
                                    $detailItems .= ' +' . ($item->items->count() - 2) . ' lainnya';
                                }

                                $statusConfig = match ($item->status) {
                                    'Selesai'  => ['label' => 'Selesai',      'class' => 'bg-emerald-100 text-emerald-700'],
                                    'Diproses' => ['label' => 'Diproses',     'class' => 'bg-teal-100 text-teal-700'],
                                    default    => ['label' => 'Menunggu',     'class' => 'bg-amber-100 text-amber-700'],
                                };

                                $progressWidth = match ($item->status) {
                                    'Selesai'  => 'w-full bg-emerald-500',
                                    'Diproses' => 'w-2/3 bg-amber-400',
                                    default    => 'w-1/3 bg-sky-400',
                                };
                            @endphp
                            <tr class="group transition-colors hover:bg-emerald-50/30">
                                <td class="px-5 py-4">
                                    <span class="font-mono font-bold text-slate-600 text-xs">{{ $trkId }}</span>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    @if($item->tanggal)
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-600 max-w-[160px]">
                                    <span class="block truncate" title="{{ $item->alamat }}">
                                        {{ $item->alamat ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 max-w-[200px]">
                                    <span class="block truncate text-xs" title="{{ $item->items->map(fn($i) => ($i->kategoriSampah?->nama ?? '-'))->implode(', ') }}">
                                        {{ $detailItems ?: '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-bold text-emerald-600">
                                        +{{ number_format($item->total_estimasi_poin ?? 0) }}
                                    </span>
                                    <span class="text-xs text-slate-400"> Pts</span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-semibold text-rose-600">
                                        Rp {{ number_format($item->total_tagihan ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                    {{-- Progress bar --}}
                                    <div class="mt-1.5 h-1 w-full rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full rounded-full transition-all {{ $progressWidth }}"></div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('riwayat-layanan.show', $item) }}"
                                       class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-emerald-300 hover:text-emerald-700 transition">
                                        Detail →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-20 text-center">
                                    <div class="text-5xl mb-4">📭</div>
                                    <p class="text-base font-bold text-slate-600">Belum ada riwayat layanan.</p>
                                    <p class="mt-1 text-sm text-slate-400">Riwayat penjemputan Anda akan muncul di sini.</p>
                                    <a href="{{ route('permintaan-penjemputan.index') }}"
                                       class="mt-6 inline-block rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white hover:bg-emerald-600 transition">
                                        + Ajukan Penjemputan Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($riwayat->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </section>

        {{-- Banner Statistik Kontribusi --}}
        <section class="relative overflow-hidden rounded-2xl bg-[#0d2b22] px-8 py-6 text-white shadow-xl">
            <div class="pointer-events-none absolute -right-10 -top-10 h-52 w-52 rounded-full bg-emerald-500/20 blur-3xl"></div>

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.25em] text-emerald-400">Total Kontribusi Anda</p>
                    <p class="mt-2 text-2xl font-black">
                        <span class="text-white">{{ number_format($stats['total_berat'], 1) }} kg</span>
                        <span class="text-emerald-400"> Sampah Terolah</span>
                    </p>
                </div>
                <div class="flex items-center gap-8">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Selesai</p>
                        <p class="mt-0.5 text-2xl font-black">
                            {{ number_format($stats['selesai']) }}
                            <span class="text-sm font-medium text-slate-400">Kali</span>
                        </p>
                    </div>
                    <div class="h-8 w-px bg-white/10"></div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Poin Terkumpul</p>
                        <p class="mt-0.5 text-2xl font-black text-emerald-400">
                            {{ number_format($stats['total_poin']) }}
                            <span class="text-sm font-medium text-slate-400">Pts</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>
