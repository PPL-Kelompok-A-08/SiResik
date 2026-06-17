<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Layanan - SiResik</title>
    <meta name="description" content="Pantau perkembangan penjemputan sampah Anda secara real-time di SiResik.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    {{-- Sidebar Konsisten --}}
    <x-sidebar />

    {{-- ═══════════════════════ MAIN ═══════════════════════ --}}
    <main class="flex flex-col gap-6 px-7 py-6">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-black tracking-tight text-slate-800">Status Penjemputan</h1>
            <div class="flex gap-3">
                <button type="button"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Unduh Laporan
                </button>
                <a href="{{ route('permintaan-penjemputan.index') }}"
                   class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-600">
                    + Ajukan Penjemputan
                </a>
            </div>
        </header>

        {{-- ═══ Content Area ═══ --}}
        <div class="grid gap-6 xl:grid-cols-[1fr,320px]">

            {{-- LEFT: Status + Riwayat --}}
            <div class="flex flex-col gap-6">

                {{-- Title + Jadwal Card --}}
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-3xl font-black tracking-tight text-slate-800">Status Layanan</h2>
                        <p class="mt-1 text-sm text-slate-500">Pantau perkembangan penjemputan sampah Anda secara real-time.</p>
                    </div>

                    <div class="shrink-0 rounded-2xl bg-[#0c5b49] px-5 py-4 text-white shadow-lg">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-300 text-sm">📅</span>
                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-300">Jadwal Reguler Area</p>
                        </div>
                        <p class="mt-1.5 text-base font-black">Setiap {{ collect($weeklySchedules)->pluck('hari')->unique()->implode(', ') }}</p>
                    </div>
                </div>

                {{-- Riwayat Permintaan --}}
                <div>
                    <p class="text-[11px] font-black uppercase tracking-widest text-slate-400 mb-4">Riwayat Permintaan</p>

                    <div class="flex flex-col gap-3">
                        @forelse ($trackingRequests as $index => $item)
                            @php
                                $statusMeta = match ($item->status) {
                                    'Selesai' => [
                                        'label'    => 'SELESAI',
                                        'badge'    => 'bg-emerald-100 text-emerald-700',
                                        'iconBg'   => 'bg-emerald-100',
                                        'iconText' => 'text-emerald-600',
                                        'icon'     => '✓',
                                    ],
                                    'Diproses' => [
                                        'label'    => 'DIJADWALKAN',
                                        'badge'    => 'bg-blue-100 text-blue-700',
                                        'iconBg'   => 'bg-blue-100',
                                        'iconText' => 'text-blue-500',
                                        'icon'     => '◔',
                                    ],
                                    default => [
                                        'label'    => 'MENUNGGU',
                                        'badge'    => 'bg-amber-100 text-amber-700',
                                        'iconBg'   => 'bg-amber-100',
                                        'iconText' => 'text-amber-500',
                                        'icon'     => '◷',
                                    ],
                                };
                                $pkId         = 'PK-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                                $kategoriText = $item->items->pluck('kategoriSampah.nama')->filter()->take(2)->implode(', ');
                                $totalBerat   = $item->items->sum('berat_kg');
                                $beratLabel   = rtrim(rtrim(number_format($totalBerat, 2, '.', ''), '0'), '.') ?: '0';
                            @endphp

                            <article class="flex items-center gap-4 rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                                {{-- Status Icon --}}
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $statusMeta['iconBg'] }} text-xl font-black {{ $statusMeta['iconText'] }}">
                                    {{ $statusMeta['icon'] }}
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-xs font-black text-slate-400">{{ $pkId }}</span>
                                        <span class="rounded-md px-2 py-0.5 text-[10px] font-black {{ $statusMeta['badge'] }}">
                                            {{ $statusMeta['label'] }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-base font-black text-slate-800">
                                        {{ $kategoriText ?: 'Permintaan Penjemputan' }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-400">
                                        Diajukan pada {{ optional($item->created_at)->translatedFormat('d M Y') }}
                                        &bull; Estimasi berat {{ $beratLabel }} kg
                                    </p>
                                    @if ($item->status === 'Diproses' && $item->scheduled_at)
                                        <p class="mt-0.5 text-xs font-semibold text-blue-600">
                                            Dijadwalkan {{ \Illuminate\Support\Carbon::parse($item->scheduled_at)->translatedFormat('d M Y, H:i') }} WIB
                                        </p>
                                    @endif
                                </div>

                                {{-- Detail Button --}}
                                <a href="{{ route('riwayat-layanan.index') }}"
                                   class="shrink-0 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                                    Detail
                                </a>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center text-slate-400">
                                <p class="text-3xl">📭</p>
                                <p class="mt-3 font-semibold">Belum ada permintaan penjemputan.</p>
                                <a href="{{ route('permintaan-penjemputan.index') }}"
                                   class="mt-4 inline-block rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-600 transition">
                                    + Ajukan Sekarang
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Butuh Bantuan --}}
                <div class="rounded-2xl bg-white px-6 py-5 shadow-sm ring-1 ring-slate-200">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-orange-200 bg-orange-50 text-2xl text-orange-500">!</div>
                            <div>
                                <h3 class="font-black text-slate-800">Butuh Bantuan Penjemputan?</h3>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    Jika status penjemputan Anda tidak berubah dalam 2x24 jam, silakan hubungi pusat bantuan kami melalui WhatsApp.
                                </p>
                            </div>
                        </div>
                        <button type="button" id="btn-chat-bantuan"
                                class="shrink-0 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-black text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-600">
                            Chat Bantuan
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Kalender Mingguan --}}
            <div>
                <p class="text-[11px] font-black uppercase tracking-widest text-slate-400 mb-4">Kalender Mingguan</p>

                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="divide-y divide-slate-100">
                        @foreach ($weeklySchedules as $schedule)
                            <div class="px-5 py-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-black uppercase tracking-wide text-[#0c5b49]">{{ $schedule['hari'] }}</p>
                                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[10px] font-black text-slate-500">{{ $schedule['jam'] }}</span>
                                </div>
                                <p class="mt-2 text-sm font-bold text-slate-800">{{ $schedule['kategori'] }}</p>
                                <p class="mt-0.5 text-xs italic text-slate-400">Berlaku untuk {{ $schedule['zona'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-emerald-100 bg-emerald-50 px-5 py-4">
                        <p class="text-xs leading-6 text-emerald-800">                        </p>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>
</body>
</html>
