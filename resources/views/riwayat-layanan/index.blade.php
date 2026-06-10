<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penjemputan - SiResik</title>
    <meta name="description" content="Daftar lengkap riwayat transaksi dan partisipasi kebersihan Anda di SiResik.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    <aside class="bg-[#0c5b49] flex flex-col px-5 py-7 text-white">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-2">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20 text-xl">♻</div>
            <div>
                <p class="text-3xl font-black tracking-tight leading-none">SiResik</p>
                <p class="mt-0.5 text-[10px] uppercase tracking-[0.2em] text-emerald-200">Sistem Informasi Resik</p>
            </div>
        </div>

        {{-- Nav Menu --}}
        @php
            $nav = [
                ['label' => 'Dashboard',           'icon' => '⊞', 'href' => route('dashboard.masyarakat'),           'active' => false],
                ['label' => 'Penjemputan',          'icon' => '⊕', 'href' => route('permintaan-penjemputan.index'),   'active' => false],
                ['label' => 'Status Layanan',       'icon' => '◎', 'href' => route('status-layanan.index'),           'active' => false],
                ['label' => 'Riwayat Layanan',      'icon' => '◉', 'href' => route('riwayat-layanan.index'),          'active' => true],
                ['label' => 'Poin & Reward',        'icon' => '◈', 'href' => route('poin.index'),                     'active' => false],
                ['label' => 'Sampah Liar',          'icon' => '⊗', 'href' => route('dashboard.masyarakat'),           'active' => false],
                ['label' => 'Peta & Lokasi',        'icon' => '⊙', 'href' => route('peta.lokasi'),                    'active' => false],
                ['label' => 'Usulkan Titik',        'icon' => '⊕', 'href' => route('peta.usulan-titik'),              'active' => false],
                ['label' => 'Edukasi Lingkungan',   'icon' => '◧', 'href' => route('dashboard.masyarakat'),           'active' => false],
                ['label' => 'Kegiatan Lingkungan',  'icon' => '◨', 'href' => route('dashboard.masyarakat'),           'active' => false],
                ['label' => 'Notifikasi',           'icon' => '◇', 'href' => route('notifications.index'),            'active' => false],
            ];
        @endphp

        <nav class="mt-10 flex-1 space-y-0.5">
            @foreach ($nav as $item)
                <a href="{{ $item['href'] }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium transition-all
                          {{ $item['active']
                              ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/30'
                              : 'text-emerald-50/80 hover:bg-white/8 hover:text-white' }}">
                    <span class="w-5 text-center text-base opacity-80">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-[15px] font-medium text-emerald-50/80 transition hover:bg-white/8 hover:text-white">
                <span class="w-5 text-center">↪</span>
                <span>Keluar (Log Out)</span>
            </button>
        </form>

        {{-- User Card --}}
        <div class="mt-5 rounded-2xl bg-white/8 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-base font-black">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-[15px] font-bold">{{ $user->name }}</p>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-200">Warga Terverifikasi</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════ MAIN ═══════════════════════ --}}
    <main class="flex flex-col gap-6 px-8 py-6 lg:px-10">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between">
            <h1 class="text-2xl font-black tracking-tight text-slate-800">Riwayat Penjemputan</h1>
            <button type="button"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Unduh Laporan
            </button>
        </header>

        {{-- Card Utama --}}
        <section class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

            {{-- Card Header --}}
            <div class="flex flex-col gap-4 px-6 pt-5 pb-0 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-800">Riwayat Layanan</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Daftar lengkap transaksi dan partisipasi kebersihan Anda.</p>
                </div>

                {{-- Filter Tabs --}}
                <div class="flex shrink-0 items-center gap-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 text-xs font-bold">
                    <button id="tab-semua"
                            onclick="setTab('semua')"
                            class="tab-btn px-4 py-2 uppercase tracking-wider transition">
                        Semua
                    </button>
                    <button id="tab-penjemputan"
                            onclick="setTab('penjemputan')"
                            class="tab-btn border-l border-slate-200 px-4 py-2 uppercase tracking-wider transition">
                        Penjemputan
                    </button>
                    <button id="tab-pembersihan"
                            onclick="setTab('pembersihan')"
                            class="tab-btn border-l border-slate-200 px-4 py-2 uppercase tracking-wider transition">
                        Pembersihan
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="mt-4 overflow-x-auto px-1">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-5 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">ID Transaksi</th>
                            <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Jenis</th>
                            <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Tanggal</th>
                            <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Lokasi</th>
                            <th class="px-4 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Detail Item</th>
                            <th class="px-4 py-3 text-right text-[11px] font-black uppercase tracking-wider text-slate-400">Poin</th>
                            <th class="px-5 py-3 text-left text-[11px] font-black uppercase tracking-wider text-slate-400">Status</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-slate-50">
                        @forelse ($riwayat as $item)
                            @php
                                // Semua permintaan dari sistem ini adalah tipe Penjemputan
                                $jenis     = 'penjemputan';
                                $jenisIcon = '🚛';
                                $jenisLabel = 'Pickup';

                                $trkId  = 'TRK-' . str_pad($item->id, 3, '0', STR_PAD_LEFT);

                                $detailItems = $item->items->map(function($i) {
                                    $berat = rtrim(rtrim(number_format($i->berat_kg, 1, '.', ''), '0'), '.');
                                    return ($i->kategoriSampah?->nama ?? '?') . ' (' . $berat . 'kg)';
                                })->take(2)->implode(', ');

                                $statusMeta = match ($item->status) {
                                    'Selesai'  => ['label' => 'SELESAI',     'class' => 'bg-emerald-100 text-emerald-700'],
                                    'Diproses' => ['label' => 'TERVERIFIKASI','class' => 'bg-teal-100 text-teal-700'],
                                    default    => ['label' => 'MENUNGGU',    'class' => 'bg-amber-100 text-amber-700'],
                                };

                                $poinFormatted = $item->total_estimasi_poin >= 1000
                                    ? '+' . number_format($item->total_estimasi_poin / 1000, 1) . 'k Pts'
                                    : '+' . number_format($item->total_estimasi_poin) . ' Pts';
                            @endphp
                            <tr class="table-row group transition hover:bg-slate-50/60" data-jenis="{{ $jenis }}">
                                <td class="px-5 py-4">
                                    <span class="font-bold text-slate-700">{{ $trkId }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-1.5 text-slate-600">
                                        <span class="text-base">{{ $jenisIcon }}</span>
                                        <span class="font-medium">{{ $jenisLabel }}</span>
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

            {{-- Pagination --}}
            @if($riwayat->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $riwayat->links() }}
                </div>
            @else
                <div class="pb-2"></div>
            @endif
        </section>

        {{-- ═══════ Banner Statistik Kontribusi ═══════ --}}
        @php
            $totalBeratFmt = number_format($stats['total_berat'], 0, '.', '.');
            $poinFmt       = $stats['total_poin'] >= 1000
                ? number_format($stats['total_poin'] / 1000, 0) . 'k'
                : number_format($stats['total_poin']);
        @endphp
        <section class="relative overflow-hidden rounded-2xl bg-[#0d1f1a] px-8 py-6 text-white shadow-xl">
            {{-- decorative blurred circle --}}
            <div class="pointer-events-none absolute -right-10 -top-10 h-52 w-52 rounded-full bg-emerald-600/20 blur-3xl"></div>
            <div class="pointer-events-none absolute right-40 bottom-0 h-32 w-32 rounded-full bg-teal-500/10 blur-2xl"></div>

            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.25em] text-emerald-400">Statistik Kontribusi</p>
                    <p class="mt-2 text-3xl font-black leading-tight">
                        Total <span class="text-white">{{ $totalBeratFmt }} kg</span>
                        <span class="text-emerald-400">Sampah Terolah</span>
                    </p>
                </div>

                <div class="flex items-center gap-10 lg:gap-14">
                    <div class="relative">
                        {{-- decorative large number watermark --}}
                        <p class="pointer-events-none absolute -top-4 -right-4 text-7xl font-black text-white/5 select-none">{{ $stats['selesai'] }}</p>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Penjemputan</p>
                        <p class="mt-1 text-3xl font-black">
                            {{ number_format($stats['selesai']) }}
                            <span class="text-base font-semibold text-slate-400">Kali</span>
                        </p>
                    </div>

                    <div class="h-10 w-px bg-white/10"></div>

                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Poin Terkumpul</p>
                        <p class="mt-1 text-3xl font-black text-emerald-400">
                            {{ $poinFmt }}
                            <span class="text-base font-semibold text-slate-400">Pts</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

{{-- ══════════════ Script Tab Filter ══════════════ --}}
<script>
    const TAB_ACTIVE   = 'bg-[#0c5b49] text-white';
    const TAB_INACTIVE = 'text-slate-500 hover:text-slate-800 bg-slate-50';

    function setTab(tab) {
        // Update tombol
        ['semua','penjemputan','pembersihan'].forEach(t => {
            const btn = document.getElementById('tab-' + t);
            btn.className = btn.className
                .replace(TAB_ACTIVE, '')
                .replace(TAB_INACTIVE, '')
                .trim();
            btn.className += ' ' + (t === tab ? TAB_ACTIVE : TAB_INACTIVE);
        });

        // Filter baris tabel
        const rows     = document.querySelectorAll('tr.table-row');
        const noResult = document.getElementById('no-filter-result');
        let visible    = 0;

        rows.forEach(row => {
            const jenis = row.dataset.jenis;
            const show  = tab === 'semua'
                || (tab === 'penjemputan'  && jenis === 'penjemputan')
                || (tab === 'pembersihan'  && jenis === 'pembersihan');
            row.classList.toggle('hidden', !show);
            if (show) visible++;
        });

        if (noResult) noResult.classList.toggle('hidden', visible > 0 || rows.length === 0);
    }

    // Inisialisasi default
    setTab('semua');
</script>
</body>
</html>
