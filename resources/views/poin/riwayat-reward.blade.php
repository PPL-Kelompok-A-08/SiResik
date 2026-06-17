<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penukaran Reward - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .wave-bottom { position: absolute; bottom: 0; left: 0; right: 0; pointer-events: none; line-height: 0; overflow: hidden; }
        .tbl-row { transition: background 0.12s; }
        .tbl-row:hover { background-color: #f0fdf4; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">

<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- Sidebar Konsisten --}}
    <x-sidebar />

    {{-- ============================================================
         MAIN CONTENT
    ============================================================ --}}
    <main class="relative px-6 py-8 lg:px-10 overflow-hidden flex flex-col justify-between min-h-screen">

        <div>
            {{-- ── Header ────────────────────────────────────────────── --}}
            <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <a href="{{ route('poin.index') }}" class="text-[#0c5b49] hover:underline font-bold text-sm inline-flex items-center gap-1.5 mb-3">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Poin & Reward
                    </a>
                    <h1 class="text-4xl font-black tracking-tight text-slate-900">Riwayat Penukaran Reward</h1>
                    <p class="mt-2 text-base text-slate-500">Daftar reward yang telah Anda klaim dengan poin SiResik</p>
                </div>
            </header>

            {{-- ── Riwayat Table/List ──────────────────────────────── --}}
            <section class="mt-10 bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 ring-1 ring-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/75">
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-wider text-slate-400">No</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-wider text-slate-400">Nama Reward</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-wider text-slate-400">Poin Ditukar</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-wider text-slate-400">Tanggal Penukaran</th>
                                <th class="py-4 px-6 text-xs font-black uppercase tracking-wider text-slate-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($riwayat as $index => $item)
                                <tr class="tbl-row">
                                    <td class="py-4 px-6 text-sm font-bold text-slate-800">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6 text-sm font-semibold text-slate-900">
                                        <div class="flex items-center gap-3">
                                            @if($item->reward?->gambar)
                                                <img src="{{ asset('storage/' . $item->reward->gambar) }}" alt="{{ $item->reward->nama }}" class="h-10 w-10 shrink-0 rounded-xl object-cover ring-1 ring-slate-200">
                                            @else
                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 text-lg">
                                                    🎁
                                                </div>
                                            @endif
                                            <span>{{ $item->reward?->nama ?? 'Reward Terhapus' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-sm font-black text-rose-600">
                                        -{{ number_format($item->reward?->poin_diperlukan ?? 0, 0, ',', '.') }} PTS
                                    </td>
                                    <td class="py-4 px-6 text-sm text-slate-500">
                                        {{ $item->tanggal_penukaran ? $item->tanggal_penukaran->translatedFormat('d M Y, H:i') : $item->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="py-4 px-6 text-sm">
                                        @if($item->status_penukaran === 'ditolak' || $item->status_penukaran === 'dibatalkan')
                                            <span class="inline-flex rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 ring-1 ring-rose-600/20">
                                                Dibatalkan / Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-600/20">
                                                Berhasil
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-500 bg-white">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="text-5xl mb-3">🎁</span>
                                            <p class="font-bold text-slate-800 text-lg">Belum Ada Penukaran Reward</p>
                                            <p class="text-sm text-slate-400 mt-1 max-w-sm">Tukarkan poin SiResik Anda dengan reward menarik pada halaman sebelumnya.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- ── Aksen Gelombang (bottom) ────────────────────────────── --}}
        <div class="wave-bottom relative mt-16">
            <svg viewBox="0 0 1440 90" fill="none" xmlns="http://www.w3.org/2000/svg"
                 style="width:100%; height:90px; opacity:0.08;">
                <path d="M0 45C240 80 480 10 720 45C960 80 1200 10 1440 45L1440 90H0Z" fill="#0c5b49"/>
                <path d="M0 60C360 25 720 85 1080 55C1260 40 1380 70 1440 60L1440 90H0Z"
                      fill="#16a34a" opacity="0.6"/>
            </svg>
            {{-- Logo SiResik sudut kanan bawah --}}
            <div class="absolute bottom-4 right-6 flex items-center gap-1.5 opacity-30 select-none">
                <span class="text-base">♻</span>
                <span class="text-sm font-black tracking-tight text-[#0c5b49]">SiResik</span>
            </div>
        </div>

    </main>
</div>

</body>
</html>
