<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Layanan - SiResik</title>
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
                    ['label' => 'Dashboard',      'href' => route('dashboard.masyarakat'), 'active' => false],
                    ['label' => 'Penjemputan',    'href' => route('permintaan-penjemputan.index'), 'active' => false],
                    ['label' => 'Status Layanan', 'href' => route('dashboard.masyarakat'), 'active' => false],
                    ['label' => 'Riwayat Layanan','href' => route('riwayat-layanan.index'), 'active' => true],
                    ['label' => 'Poin & Reward',  'href' => route('poin.index'), 'active' => false],
                    ['label' => 'Peta & Lokasi',  'href' => route('peta.lokasi'), 'active' => false],
                    ['label' => 'Usulkan Titik',  'href' => route('peta.usulan-titik'), 'active' => false],
                ];
            @endphp

            <nav class="mt-14 space-y-2">
                @foreach ($menuItems as $item)
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition
                              {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg' : 'text-emerald-50 hover:bg-white/5' }}">
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

            {{-- Back + Header --}}
            <div class="mb-6">
                <a href="{{ route('riwayat-layanan.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition">
                    ← Kembali ke Riwayat
                </a>
            </div>

            @php
                $statusMeta = match ($permintaan->status) {
                    'Selesai'  => ['color' => 'bg-emerald-100 text-emerald-800', 'border' => 'border-emerald-200', 'icon' => '✅', 'bar' => 'bg-emerald-500 w-full'],
                    'Diproses' => ['color' => 'bg-amber-100 text-amber-800',     'border' => 'border-amber-200',   'icon' => '🔄', 'bar' => 'bg-amber-400 w-2/3'],
                    default    => ['color' => 'bg-sky-100 text-sky-800',          'border' => 'border-sky-200',     'icon' => '⏳', 'bar' => 'bg-sky-400 w-1/3'],
                };
            @endphp

            {{-- Status Banner --}}
            <div class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden mb-8">
                <div class="px-8 py-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.25em] text-slate-400">Detail Layanan #{{ $permintaan->id }}</p>
                        <h1 class="mt-2 text-4xl font-black tracking-tight">Riwayat Penjemputan</h1>
                        <p class="mt-1 text-slate-500">Diajukan {{ $permintaan->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="self-start rounded-full px-5 py-2 text-sm font-black {{ $statusMeta['color'] }}">
                        {{ $statusMeta['icon'] }} {{ $permintaan->status }}
                    </span>
                </div>

                {{-- Progress --}}
                <div class="px-8 pb-6">
                    <div class="flex justify-between text-xs font-bold text-slate-400 mb-2">
                        <span class="{{ $permintaan->status !== '' ? 'text-slate-700' : '' }}">Menunggu</span>
                        <span class="{{ in_array($permintaan->status, ['Diproses','Selesai']) ? 'text-slate-700' : '' }}">Diproses</span>
                        <span class="{{ $permintaan->status === 'Selesai' ? 'text-emerald-600' : '' }}">Selesai</span>
                    </div>
                    <div class="h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full transition-all {{ $statusMeta['bar'] }}"></div>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">

                {{-- INFO PENJEMPUTAN --}}
                <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="bg-emerald-900 px-7 py-5">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-300">Info Penjemputan</p>
                    </div>
                    <div class="px-7 py-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Tanggal</p>
                                <p class="mt-1 font-semibold">{{ $permintaan->tanggal }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Jadwal</p>
                                <p class="mt-1 font-semibold">{{ $permintaan->jadwal }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Alamat</p>
                                <p class="mt-1 font-semibold">{{ $permintaan->alamat }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">No. Telepon</p>
                                <p class="mt-1 font-semibold">{{ $permintaan->nomor_telepon }}</p>
                            </div>
                            @if($permintaan->scheduled_at)
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Dijadwalkan</p>
                                <p class="mt-1 font-semibold">{{ $permintaan->scheduled_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                            @endif
                        </div>
                        @if($permintaan->catatan && $permintaan->catatan !== '-')
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Catatan</p>
                                <p class="mt-1 text-sm">{{ $permintaan->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- PETUGAS & BUKTI --}}
                <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                    <div class="bg-slate-800 px-7 py-5">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-300">Petugas & Bukti</p>
                    </div>
                    <div class="px-7 py-6 space-y-5">
                        {{-- Petugas --}}
                        @if($permintaan->petugas)
                            <div class="flex items-center gap-4 rounded-2xl bg-slate-50 p-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-700 text-xl font-black text-white">
                                    {{ strtoupper(substr($permintaan->petugas->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black uppercase tracking-widest text-slate-400">Petugas</p>
                                    <p class="font-bold">{{ $permintaan->petugas->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $permintaan->petugas->email }}</p>
                                </div>
                            </div>
                        @else
                            <div class="rounded-2xl bg-slate-50 p-4 text-center text-slate-400">
                                <p class="text-sm">Belum ada petugas yang ditugaskan.</p>
                            </div>
                        @endif

                        {{-- Bukti Penyelesaian --}}
                        @if($permintaan->bukti_penyelesaian)
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Bukti Penyelesaian</p>
                                <img src="{{ Storage::url($permintaan->bukti_penyelesaian) }}"
                                     alt="Bukti Penyelesaian Penjemputan"
                                     class="w-full rounded-2xl object-cover max-h-52 cursor-pointer hover:opacity-90 transition"
                                     onclick="document.getElementById('modal-foto').classList.remove('hidden')">

                                @if($permintaan->catatan_penyelesaian)
                                    <div class="mt-3 rounded-2xl bg-emerald-50 p-4">
                                        <p class="text-xs font-black uppercase tracking-widest text-emerald-600">Catatan Petugas</p>
                                        <p class="mt-1 text-sm text-slate-700">{{ $permintaan->catatan_penyelesaian }}</p>
                                    </div>
                                @endif

                                @if($permintaan->diselesaikan_at)
                                    <p class="mt-2 text-xs text-slate-400 text-right">
                                        Diselesaikan: {{ $permintaan->diselesaikan_at->translatedFormat('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-200 p-6 text-center text-slate-400">
                                <div class="text-3xl mb-2">📷</div>
                                <p class="text-sm">Bukti belum diunggah petugas.</p>
                            </div>
                        @endif
                    </div>
                </section>

            </div>

            {{-- ITEM SAMPAH --}}
            <section class="mt-8 rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-400 px-7 py-5">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-900">Rincian Sampah</p>
                    <p class="mt-1 text-xl font-black text-white">Item yang Dijemput</p>
                </div>
                <div class="px-7 py-6">
                    <div class="space-y-3">
                        @forelse ($permintaan->items as $item)
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-xl">🗑</div>
                                    <div>
                                        <p class="font-bold">{{ $item->kategoriSampah?->nama ?? '-' }}</p>
                                        <p class="text-sm text-slate-500">Berat: {{ $item->berat_kg }} kg</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400">Poin • Tagihan</p>
                                    <p class="text-sm font-semibold">
                                        <span class="text-emerald-600">{{ number_format($item->estimasi_poin) }} poin</span>
                                        <span class="text-slate-400">•</span>
                                        <span class="text-rose-600">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-400 py-4">Tidak ada item.</p>
                        @endforelse
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="flex justify-between items-center rounded-2xl bg-emerald-900 px-6 py-4 text-white">
                            <span class="font-bold">Total Poin</span>
                            <span class="text-xl font-black text-emerald-300">{{ number_format($permintaan->total_estimasi_poin) }}</span>
                        </div>
                        <div class="flex justify-between items-center rounded-2xl bg-rose-900 px-6 py-4 text-white">
                            <span class="font-bold">Total Tagihan</span>
                            <span class="text-xl font-black text-rose-300">Rp {{ number_format($permintaan->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    {{-- MODAL FOTO BUKTI --}}
    @if($permintaan->bukti_penyelesaian)
    <div id="modal-foto" class="hidden fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
         onclick="this.classList.add('hidden')">
        <div class="max-w-2xl w-full" onclick="event.stopPropagation()">
            <img src="{{ Storage::url($permintaan->bukti_penyelesaian) }}"
                 alt="Bukti Penyelesaian"
                 class="w-full rounded-3xl object-contain max-h-[80vh]">
            <button onclick="document.getElementById('modal-foto').classList.add('hidden')"
                    class="mt-4 w-full rounded-2xl bg-white/10 py-3 text-sm font-bold text-white hover:bg-white/20 transition">
                Tutup
            </button>
        </div>
    </div>
    @endif

</body>
</html>
