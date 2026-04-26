<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal Penjemputan - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .stat-card { border-radius: 16px; padding: 20px 24px; }
        .section-card { background: white; border-radius: 24px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; }
        .badge { border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 700; }
        .page { display: none; }
        .page.active { display: block; }
        .tab-btn { padding: 8px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; background: transparent; color: #64748b; transition: all 0.15s; }
        .tab-btn.active { background: #10b981; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; border-bottom: 1px solid #f1f5f9; }
        td { padding: 14px 16px; font-size: 14px; color: #334155; border-bottom: 1px solid #f8fafc; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }
        .status-badge { border-radius: 20px; padding: 4px 10px; font-size: 11px; font-weight: 700; display: inline-block; }
        .status-menunggu { background: #fef3c7; color: #d97706; }
        .status-dijadwalkan { background: #dbeafe; color: #2563eb; }
        .status-selesai { background: #d1fae5; color: #059669; }
        .status-dibatalkan { background: #fee2e2; color: #dc2626; }
        input, select, textarea { outline: none; transition: border-color 0.15s; }
        input:focus, select:focus, textarea:focus { border-color: #10b981 !important; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 50; display: none; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: white; border-radius: 24px; padding: 32px; width: 480px; max-width: 90vw; max-height: 85vh; overflow-y: auto; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
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

            <!-- Nav -->
            <nav class="mt-10 space-y-2">
                <a onclick="showPage('dashboard')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Admin Dashboard</span>
                </a>
                <a onclick="showPage('jadwal')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition bg-emerald-600 text-white shadow-lg shadow-emerald-900/20 cursor-pointer">
                    <span class="text-xl">▣</span>
                    <span>Kelola Jadwal</span>
                </a>
                <a onclick="showPage('verifikasi')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Verifikasi Laporan</span>
                </a>
                <a onclick="showPage('kategori')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Kategori & Reward</span>
                </a>
                <a onclick="showPage('reward')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Kelola Reward</span>
                </a>
                <a onclick="showPage('area')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Area Layanan</span>
                </a>
                <a onclick="showPage('petugas')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Pantau Petugas</span>
                </a>
                <a onclick="showPage('riwayat')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Riwayat Layanan</span>
                </a>
                <a onclick="showPage('laporan')"
                    class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition text-emerald-50 hover:bg-white/5 cursor-pointer">
                    <span class="text-xl">◦</span>
                    <span>Laporan Periodik</span>
                </a>
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

            <!-- ======================== PAGE: DASHBOARD ======================== -->
            <div id="page-dashboard" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Admin Statistics Dashboard</h1>
                        <p class="mt-1 text-slate-500">Monitor waste management performance and user participation.</p>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-lg font-black text-slate-800 mb-1">Key Metrics</h2>
                    <p class="text-sm text-slate-400 mb-5">Overview of waste reports and user participation.</p>
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="stat-card" style="background:#0c5b49;">
                            <div class="text-white">
                                <p class="text-sm opacity-80">Total Reports</p>
                                <p class="text-2xl font-black">1,234</p>
                            </div>
                        </div>
                        <div class="stat-card" style="background:#1e3a5f;">
                            <div class="text-white">
                                <p class="text-sm opacity-80">Active Users</p>
                                <p class="text-2xl font-black">567</p>
                            </div>
                        </div>
                        <div class="stat-card" style="background:#7b6f00;">
                            <div class="text-white">
                                <p class="text-sm opacity-80">Waste Collected (kg)</p>
                                <p class="text-2xl font-black">8,901</p>
                            </div>
                        </div>
                        <div class="stat-card" style="background:#fee2e2; border:1px solid #fecaca;">
                            <div class="text-slate-700">
                                <p class="text-sm opacity-80">Pending Requests</p>
                                <p class="text-2xl font-black">{{ $stats['menunggu'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="stat-card" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                            <div class="text-slate-700">
                                <p class="text-sm opacity-80">Completed Pickups</p>
                                <p class="text-2xl font-black">345</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-lg font-black text-slate-800 mb-5">Reports Over Time</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <canvas id="lineChart" height="200"></canvas>
                        </div>
                        <div>
                            <canvas id="barChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-lg font-black text-slate-800 mb-4">Quick Filters</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Date Range</label>
                            <input type="date" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Category</label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option>All Categories</option>
                                <option>Plastic</option>
                                <option>Organic</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Service Reports -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-lg font-black text-slate-800 mb-1">Service Reports (Periodic Reports)</h2>
                    <p class="text-sm text-slate-400 mb-5">Detailed service reports over selected periods.</p>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <canvas id="pieChart" height="200"></canvas>
                        </div>
                        <div>
                            <canvas id="userBarChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- User Contribution Analytics -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-lg font-black text-slate-800 mb-1">User Contribution Analytics</h2>
                    <p class="text-sm text-slate-400 mb-5">Track user participation in waste management.</p>

                    <h3 class="font-black text-slate-700 mb-4">Top Contributors</h3>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">User A</p>
                            <p class="text-sm text-slate-500">500 kg</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">User B</p>
                            <p class="text-sm text-slate-500">450 kg</p>
                        </div>
                    </div>

                    <h3 class="font-black text-slate-700 mb-4">User Activity Visualization</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <canvas id="activityChart" height="200"></canvas>
                        </div>
                        <div class="flex items-center justify-center">
                            <p class="text-slate-500">Activity Map Placeholder</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4">
                        <div>
                            <p class="font-semibold text-slate-700">Total Points Earned</p>
                            <p class="text-2xl font-black text-emerald-500">12,345</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700">Active Users This Month</p>
                            <p class="text-2xl font-black text-emerald-500">89</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================== PAGE: JADWAL ======================== -->
            <div id="page-jadwal" class="page active">
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
                    <button type="button" onclick="openModal('modal-tambah-jadwal-area')" class="rounded-2xl bg-emerald-500 px-5 py-3 text-base font-bold text-white shadow-lg shadow-emerald-500/20">+ Tambah Jadwal Area</button>
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
            </div>

            <!-- ======================== PAGE: VERIFIKASI ======================== -->
            <div id="page-verifikasi" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Verifikasi Laporan</h1>
                        <p class="mt-1 text-slate-500">Review and approve waste reports from users.</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="tab-btn active" onclick="filterVerifikasi(this, 'semua')">Semua</button>
                        <button class="tab-btn" onclick="filterVerifikasi(this, 'menunggu')">Menunggu</button>
                        <button class="tab-btn" onclick="filterVerifikasi(this, 'disetujui')">Disetujui</button>
                    </div>
                </div>

                <div class="section-card">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-black text-slate-800">Daftar Laporan</h2>
                        <span class="text-sm text-slate-500">Total: {{ $permintaan->count() }} laporan</span>
                    </div>
                    <div class="grid gap-4 p-6 md:grid-cols-2">
                        @forelse($permintaan as $laporan)
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <p class="text-lg font-black text-slate-800">Laporan #{{ $laporan->id }}</p>
                                    <p class="text-sm text-slate-500">Oleh: {{ $laporan->pengguna?->name ?? 'Pengguna Tidak Diketahui' }}</p>
                                </div>
                                <span class="status-badge {{ $laporan->status === 'Menunggu' ? 'status-menunggu' : ($laporan->status === 'Selesai' ? 'status-selesai' : 'status-dibatalkan') }}">{{ $laporan->status }}</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-4">{{ $laporan->alamat ?? 'Tidak ada deskripsi' }}</p>
                            <div class="flex gap-2">
                                @if($laporan->status === 'Menunggu')
                                <form method="POST" action="{{ route('admin.verifikasi-laporan', $laporan) }}" style="display: inline;" onsubmit="showVerifikasiSuccess('disetujui'); return true;">
                                    @csrf
                                    <input type="hidden" name="status" value="disetujui">
                                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.verifikasi-laporan', $laporan) }}" style="display: inline;" onsubmit="showVerifikasiSuccess('ditolak'); return true;">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Tolak</button>
                                </form>
                                @else
                                <div class="rounded-xl bg-slate-100 px-4 py-2 text-sm text-slate-600">Status: {{ $laporan->status }}</div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-8 text-slate-500">
                            Tidak ada laporan untuk diverifikasi.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ======================== PAGE: KATEGORI ======================== -->
            <div id="page-kategori" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Kategori & Reward</h1>
                        <p class="mt-1 text-slate-500">Manage waste categories and point systems.</p>
                    </div>
                    <button onclick="openModal('modal-tambah-kategori')" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">+ Tambah Kategori</button>
                </div>

                <div class="section-card mb-6">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-800">Daftar Kategori Sampah</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Poin/kg</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\KategoriSampah::all() as $kategori)
                            <tr>
                                <td>{{ $kategori->nama }}</td>
                                <td>{{ $kategori->poin_per_kg }}</td>
                                <td>{{ $kategori->deskripsi }}</td>
                                <td>
                                    <button onclick="editKategori({{ $kategori->id }}, '{{ $kategori->nama }}', '{{ $kategori->deskripsi }}', {{ $kategori->poin_per_kg }})" class="text-emerald-500 hover:text-emerald-700">Edit</button>
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" style="display: inline;" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Konfigurasi Poin -->
                <div class="section-card p-6">
                    <h2 class="text-xl font-black text-slate-800 mb-4">Konfigurasi Sistem Poin</h2>
                    <form method="POST" action="{{ route('admin.konfigurasi-poin.update') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2">Poin Minimal Tukar Reward</label>
                                <input type="number" name="poin_minimal_tukar" value="{{ session('poin_minimal_tukar', 100) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">Bonus Poin Bulanan</label>
                                <input type="number" name="bonus_poin_bulanan" value="{{ session('bonus_poin_bulanan', 50) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">Maksimal Poin Harian</label>
                                <input type="number" name="maksimal_poin_harian" value="{{ session('maksimal_poin_harian', 200) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">Simpan Konfigurasi</button>
                    </form>
                </div>
            </div>

            <!-- ======================== PAGE: REWARD ======================== -->
            <div id="page-reward" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Kelola Reward</h1>
                        <p class="mt-1 text-slate-500">Manage reward items for point redemption.</p>
                    </div>
                    <button onclick="openModal('modal-tambah-reward')" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">+ Tambah Reward</button>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="section-card p-5">
                        <p class="text-sm text-slate-500">Total Reward</p>
                        <p class="text-2xl font-black text-slate-800">{{ $rewards->count() }}</p>
                    </div>
                    <div class="section-card p-5">
                        <p class="text-sm text-slate-500">Penukaran Bulan Ini</p>
                        <p class="text-2xl font-black text-slate-800">23</p>
                    </div>
                    <div class="section-card p-5">
                        <p class="text-sm text-slate-500">Poin Dikeluarkan</p>
                        <p class="text-2xl font-black text-slate-800">1,150</p>
                    </div>
                </div>

                <!-- Reward List -->
                <div class="section-card mb-6">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-800">Daftar Reward</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                        @forelse($rewards as $reward)
                        <div class="rounded-2xl border border-slate-200 p-4 relative">
                            <div class="flex items-start justify-between mb-2">
                                <p class="text-lg font-black text-slate-800">{{ $reward->nama }}</p>
                                <div class="flex gap-1">
                                    <button onclick="editReward({{ $reward->id }}, '{{ $reward->nama }}', '{{ $reward->deskripsi }}', {{ $reward->poin_diperlukan }}, {{ $reward->stok }}, {{ $reward->aktif ? 'true' : 'false' }})" class="text-slate-400 hover:text-slate-600 text-sm">✎</button>
                                    <form method="POST" action="{{ route('admin.reward.destroy', $reward) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus reward ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 text-sm">✕</button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-sm text-slate-500 mb-2">{{ $reward->poin_diperlukan }} poin</p>
                            <p class="text-xs text-slate-400 mb-2">Stok: {{ $reward->stok }}</p>
                            @if($reward->deskripsi)
                            <p class="text-xs text-slate-500">{{ Str::limit($reward->deskripsi, 50) }}</p>
                            @endif
                            <div class="mt-2">
                                <span class="text-xs px-2 py-1 rounded-full {{ $reward->aktif ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $reward->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-8 text-slate-500">
                            Belum ada reward yang ditambahkan.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Riwayat Penukaran -->
                <div class="section-card">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-800">Riwayat Penukaran</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Reward</th>
                                <th>Poin</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>User A</td>
                                <td>Voucher Belanja</td>
                                <td>100</td>
                                <td>2024-04-20</td>
                            </tr>
                            <!-- More rows... -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ======================== PAGE: AREA ======================== -->
            <div id="page-area" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Area Layanan</h1>
                        <p class="mt-1 text-slate-500">Manage service areas and zones.</p>
                    </div>
                    <button class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">+ Tambah Area</button>
                </div>

                <!-- Map Placeholder -->
                <div class="section-card mb-6 p-6">
                    <h2 class="text-xl font-black text-slate-800 mb-4">Peta Area Layanan</h2>
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between mb-6">
                        <div>
                            <h3 class="text-4xl font-black tracking-tight text-slate-800">Pemetaan komprehensif</h3>
                            <p class="mt-2 text-xl text-slate-500">Koordinat dari basis data; pembaruan dilakukan lewat pengelolaan data titik layanan.</p>
                        </div>
                        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
                            <label class="relative flex-1 min-w-[220px]">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">⌕</span>
                                <input type="search" id="cari-lokasi" autocomplete="off" placeholder="Cari nama atau alamat..."
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-lg text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            </label>
                            <button type="button" id="peta-lokasi-saya"
                                class="whitespace-nowrap rounded-2xl border border-emerald-600 bg-emerald-50 px-5 py-3 text-lg font-semibold text-emerald-900 transition hover:bg-emerald-100">
                                Lokasi saya
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-6 text-sm font-semibold text-slate-600 mb-6">
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-green-600"></span> TPS</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-blue-600"></span> Bank Sampah</span>
                        <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-amber-600"></span> Usulan baru</span>
                    </div>

                    @php
                        $titikLayanan = \App\Models\TitikLayanan::orderBy('nama')->get();
                    @endphp
                    @include('peta._leaflet-map', ['titikLayanan' => $titikLayanan])
                </div>

                <!-- Zone List -->
                <div class="section-card">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-black text-slate-800">Daftar Titik Layanan</h2>
                        <button id="btn-open-titik-layanan-modal" onclick="openModal('modal-tambah-titik-layanan')" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">+ Tambah Titik Layanan</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Alamat</th>
                                <th>Jam Operasional</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($titikLayanan as $titik)
                            <tr>
                                <td>{{ $titik->nama }}</td>
                                <td>{{ $titik->jenis }}</td>
                                <td>{{ $titik->alamat }}</td>
                                <td>{{ $titik->jam_operasional }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <button onclick="editTitikLayanan({{ $titik->id }}, '{{ $titik->nama }}', '{{ $titik->jenis }}', '{{ $titik->alamat }}', '{{ $titik->jam_operasional }}', {{ $titik->latitude }}, {{ $titik->longitude }}, '{{ $titik->jenis_sampah_diterima }}')" class="text-slate-400 hover:text-slate-600 text-sm">✎</button>
                                        <form method="POST" action="{{ route('admin.titik-layanan.destroy', $titik) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus titik layanan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-600 text-sm">✕</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-500">Belum ada titik layanan yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ======================== PAGE: PETUGAS ======================== -->
            <div id="page-petugas" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Pantau Petugas</h1>
                        <p class="mt-1 text-slate-500">Monitor officer performance and assignments.</p>
                    </div>
                    <button onclick="openModal('modal-tambah-petugas')" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">+ Tambah Petugas</button>
                </div>

                <!-- Stats Petugas -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="section-card p-5">
                        <p class="text-sm text-slate-500">Total Petugas</p>
                        <p class="text-2xl font-black text-slate-800">{{ $petugas->count() }}</p>
                    </div>
                    <div class="section-card p-5">
                        <p class="text-sm text-slate-500">Aktif Hari Ini</p>
                        <p class="text-2xl font-black text-slate-800">{{ $petugas->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                    </div>
                    <div class="section-card p-5">
                        <p class="text-sm text-slate-500">Penjemputan Selesai</p>
                        <p class="text-2xl font-black text-slate-800">{{ $permintaan->where('status', 'Selesai')->count() }}</p>
                    </div>
                </div>

                <!-- Petugas Cards -->
                <div class="section-card mb-6">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-800">Daftar Petugas</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                        @forelse($petugas as $petugasItem)
                        <div class="rounded-2xl border border-slate-200 p-5">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <span class="text-emerald-600 font-bold">{{ substr($petugasItem->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-lg font-black text-slate-800">{{ $petugasItem->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $petugasItem->email }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    <button onclick="editPetugas({{ $petugasItem->id }}, '{{ $petugasItem->name }}', '{{ $petugasItem->email }}')" class="text-slate-400 hover:text-slate-600 text-sm">✎</button>
                                    <form method="POST" action="{{ route('admin.petugas.destroy', $petugasItem) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 text-sm">✕</button>
                                    </form>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm text-slate-600">Status: <span class="text-emerald-600 font-semibold">Aktif</span></p>
                                <p class="text-sm text-slate-600">Penjemputan hari ini: {{ $permintaan->where('petugas_id', $petugasItem->id)->where('status', 'Selesai')->filter(function($item) { return $item->updated_at && $item->updated_at->isToday(); })->count() }}</p>
                                <p class="text-sm text-slate-600">Total penjemputan: {{ $permintaan->where('petugas_id', $petugasItem->id)->where('status', 'Selesai')->count() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-8 text-slate-500">
                            Belum ada petugas yang terdaftar.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ======================== PAGE: RIWAYAT ======================== -->
            <div id="page-riwayat" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Riwayat Layanan</h1>
                        <p class="mt-1 text-slate-500">View historical service records.</p>
                    </div>
                    <div class="flex gap-3">
                        <button class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold">Export</button>
                        <button class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Filter</button>
                    </div>
                </div>

                <!-- Filter -->
                <div class="section-card mb-6 p-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Tanggal Mulai</label>
                            <input type="date" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Tanggal Akhir</label>
                            <input type="date" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Status</label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option>Semua</option>
                                <option>Selesai</option>
                                <option>Dalam Proses</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Petugas</label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option>Semua</option>
                                <option>Ahmad</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-black text-slate-800">Riwayat Penjemputan</h2>
                        <span class="text-sm text-slate-500">Total: 156 records</span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Petugas</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>REQ-101</td>
                                <td>User A</td>
                                <td>Ahmad</td>
                                <td>2024-04-20</td>
                                <td><span class="status-badge status-selesai">Selesai</span></td>
                            </tr>
                            <!-- More rows... -->
                        </tbody>
                    </table>
                    <div class="px-6 py-4 flex items-center justify-between border-t border-slate-100">
                        <p class="text-sm text-slate-500">Menampilkan 1-10 dari 156</p>
                        <div class="flex gap-2">
                            <button class="px-3 py-1 border border-slate-300 rounded text-sm">Previous</button>
                            <button class="px-3 py-1 bg-emerald-500 text-white rounded text-sm">1</button>
                            <button class="px-3 py-1 border border-slate-300 rounded text-sm">Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================== PAGE: LAPORAN ======================== -->
            <div id="page-laporan" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Laporan Periodik</h1>
                        <p class="mt-1 text-slate-500">Generate and view periodic reports.</p>
                    </div>
                    <div class="flex gap-3">
                        <button class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold">Export PDF</button>
                        <button class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Generate Report</button>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="section-card mb-6 p-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Periode Mulai</label>
                            <input type="date" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Periode Akhir</label>
                            <input type="date" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Tipe Laporan</label>
                            <select class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option>Bulanan</option>
                                <option>Mingguan</option>
                                <option>Harian</option>
                            </select>
                        </div>
                        <button class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Terapkan Filter</button>
                    </div>
                </div>

                <!-- Summary -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-xl font-black text-slate-800 mb-4">Ringkasan Periode</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">1,234</p>
                            <p class="text-sm text-slate-500">Total Laporan</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">567</p>
                            <p class="text-sm text-slate-500">Laporan Selesai</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">8,901 kg</p>
                            <p class="text-sm text-slate-500">Sampah Dikumpulkan</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">89</p>
                            <p class="text-sm text-slate-500">User Aktif</p>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-xl font-black text-slate-800 mb-4">Tren Laporan Bulanan</h2>
                    <canvas id="lapChart" height="120"></canvas>
                </div>

                <!-- Table -->
                <div class="section-card">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-xl font-black text-slate-800">Detail Laporan</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah Laporan</th>
                                <th>Sampah (kg)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-04-01</td>
                                <td>45</td>
                                <td>1,200</td>
                                <td><span class="status-badge status-selesai">Selesai</span></td>
                            </tr>
                            <!-- More rows... -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Modal: Tambah/Edit Reward -->
    <div id="modal-tambah-reward" class="modal-overlay" onclick="closeModalOutside(event,'modal-tambah-reward')">
        <div class="modal">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800" id="modal-reward-title">Tambah Reward</h3>
                <button onclick="closeModal('modal-tambah-reward')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            <form id="reward-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Nama Reward</label>
                        <input type="text" name="nama" id="reward-nama" placeholder="Contoh: Voucher Belanja" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Poin Diperlukan</label>
                        <input type="number" name="poin_diperlukan" id="reward-poin" placeholder="Contoh: 100" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Stok</label>
                        <input type="number" name="stok" id="reward-stok" placeholder="Contoh: 50" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="reward-deskripsi" rows="3" placeholder="Deskripsi reward..." class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm resize-none"></textarea>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="aktif" id="reward-aktif" value="1" class="mr-2">
                        <label for="reward-aktif" class="text-sm text-slate-600">Aktifkan reward ini</label>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-reward')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Tambah/Edit Petugas -->
    <div id="modal-tambah-petugas" class="modal-overlay" onclick="closeModalOutside(event,'modal-tambah-petugas')">
        <div class="modal">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800" id="modal-petugas-title">Tambah Petugas</h3>
                <button onclick="closeModal('modal-tambah-petugas')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            <form id="petugas-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="petugas-nama" placeholder="Contoh: Ahmad Surya" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Email</label>
                        <input type="email" name="email" id="petugas-email" placeholder="Contoh: ahmad@siresik.com" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Password</label>
                        <input type="password" name="password" id="petugas-password" placeholder="Minimal 8 karakter" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="petugas-password-confirm" placeholder="Ulangi password" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-petugas')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Tambah/Edit Titik Layanan -->
    <div id="modal-tambah-titik-layanan" class="modal-overlay" onclick="closeModalOutside(event,'modal-tambah-titik-layanan')">
        <div class="modal">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800" id="modal-titik-layanan-title">Tambah Titik Layanan</h3>
                <button onclick="closeModal('modal-tambah-titik-layanan')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            <form id="titik-layanan-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Nama Titik Layanan</label>
                        <input type="text" name="nama" id="titik-layanan-nama" placeholder="Contoh: TPS Margonda" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Jenis</label>
                        <select name="jenis" id="titik-layanan-jenis" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                            <option value="TPS">TPS (Tempat Pembuangan Sementara)</option>
                            <option value="Bank Sampah">Bank Sampah</option>
                            <option value="Drop Point">Drop Point</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Alamat</label>
                        <textarea name="alamat" id="titik-layanan-alamat" rows="2" placeholder="Alamat lengkap..." class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Pilih Lokasi di Peta</label>
                        <div id="titik-layanan-map" class="h-[220px] overflow-hidden rounded-xl border border-slate-200 bg-slate-50"></div>
                        <p id="titik-layanan-koordinat" class="mt-2 text-xs text-slate-500">Klik peta untuk menentukan titik layanan.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Latitude</label>
                            <input type="text" id="titik-layanan-latitude-display" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50" readonly>
                            <input type="hidden" name="latitude" id="titik-layanan-latitude" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Longitude</label>
                            <input type="text" id="titik-layanan-longitude-display" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm bg-slate-50" readonly>
                            <input type="hidden" name="longitude" id="titik-layanan-longitude" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Jam Operasional</label>
                        <input type="text" name="jam_operasional" id="titik-layanan-jam" placeholder="Contoh: 08:00 - 17:00" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Jenis Sampah Diterima</label>
                        <input type="text" name="jenis_sampah_diterima" id="titik-layanan-sampah" placeholder="Contoh: Plastik, Organik, Kertas" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-titik-layanan')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Tambah/Edit Kategori -->
    <div id="modal-tambah-kategori" class="modal-overlay" onclick="closeModalOutside(event,'modal-tambah-kategori')">
        <div class="modal">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800" id="modal-title">Tambah Kategori Sampah</h3>
                <button onclick="closeModal('modal-tambah-kategori')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            <form id="kategori-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Nama Kategori</label>
                        <input type="text" name="nama" id="kategori-nama" placeholder="Contoh: Plastik" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Poin per kg</label>
                        <input type="number" name="poin_per_kg" id="kategori-poin" placeholder="Contoh: 50" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="kategori-deskripsi" rows="3" placeholder="Deskripsi singkat kategori..." class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm resize-none"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-kategori')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Tambah Jadwal Area -->
    <div id="modal-tambah-jadwal-area" class="modal-overlay" onclick="closeModalOutside(event,'modal-tambah-jadwal-area')">
        <div class="modal">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800">Tambah Jadwal Area</h3>
                <button onclick="closeModal('modal-tambah-jadwal-area')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            <form id="jadwal-area-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Hari</label>
                        <select name="hari" id="jadwal-hari" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Zona/Area</label>
                        <input type="text" name="zona" id="jadwal-zona" placeholder="Contoh: Zona A" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Jam Penjemputan</label>
                        <input type="time" name="jam" id="jadwal-jam" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Pilih Petugas</label>
                        <select name="petugas_id" id="jadwal-petugas" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                            <option value="">Pilih Petugas</option>
                            @foreach($petugas as $petugasItem)
                            <option value="{{ $petugasItem->id }}">{{ $petugasItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-jadwal-area')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Verifikasi Laporan Success/Fail -->
    <div id="modal-verifikasi-result" class="modal-overlay" onclick="closeModalOutside(event,'modal-verifikasi-result')">
        <div class="modal" style="max-width: 400px;">
            <div class="text-center">
                <div id="verifikasi-icon" class="mx-auto mb-4 text-6xl">✓</div>
                <h3 class="text-2xl font-black text-slate-800 mb-2" id="verifikasi-title">Laporan Disetujui</h3>
                <p class="text-slate-500 mb-6" id="verifikasi-message">Laporan telah berhasil disetujui dan diterima oleh sistem.</p>
                <button onclick="closeModal('modal-verifikasi-result'); location.reload();" class="w-full rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Selesai</button>
            </div>
        </div>
    </div>

    <script>
        function showVerifikasiSuccess(status) {
            const icon = document.getElementById('verifikasi-icon');
            const title = document.getElementById('verifikasi-title');
            const message = document.getElementById('verifikasi-message');
            
            if (status === 'disetujui') {
                icon.textContent = '✓';
                icon.className = 'mx-auto mb-4 text-6xl text-emerald-500';
                title.textContent = 'Laporan Disetujui';
                title.className = 'text-2xl font-black text-emerald-700 mb-2';
                message.textContent = 'Laporan telah berhasil disetujui dan poin diberikan kepada user.';
            } else {
                icon.textContent = '✕';
                icon.className = 'mx-auto mb-4 text-6xl text-red-500';
                title.textContent = 'Laporan Ditolak';
                title.className = 'text-2xl font-black text-red-700 mb-2';
                message.textContent = 'Laporan telah ditolak dan dikembalikan untuk perbaikan.';
            }
            
            openModal('modal-verifikasi-result');
            return true;
        }

        // Navigation
        function showPage(name) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.getElementById('page-' + name).classList.add('active');
            document.querySelectorAll('nav a').forEach(n => {
                n.classList.remove('bg-emerald-600', 'text-white', 'shadow-lg', 'shadow-emerald-900/20');
                n.classList.add('text-emerald-50', 'hover:bg-white/5');
                n.querySelector('span:first-child').textContent = '◦';
            });
            
            // Find the clicked element
            const clickedElement = event.currentTarget;
            clickedElement.classList.remove('text-emerald-50', 'hover:bg-white/5');
            clickedElement.classList.add('bg-emerald-600', 'text-white', 'shadow-lg', 'shadow-emerald-900/20');
            clickedElement.querySelector('span:first-child').textContent = '▣';

            if (name === 'dashboard') {
                setTimeout(initCharts, 100);
            }
            if (name === 'laporan') {
                setTimeout(initLapChart, 100);
            }
        }

        // Modal
        function openModal(id) {
            document.getElementById(id).classList.add('open');
            if (id === 'modal-tambah-titik-layanan') {
                setTimeout(function () {
                    setupTitikLayananMap();
                }, 100);
            }
        }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }
        function closeModalOutside(e, id) { if (e.target.id === id) closeModal(id); }

        let titikLayananMap = null;
        let titikLayananMarker = null;

        function setTitikLayananPoint(lat, lng) {
            const latValue = Number(lat).toFixed(7);
            const lngValue = Number(lng).toFixed(7);
            document.getElementById('titik-layanan-latitude').value = latValue;
            document.getElementById('titik-layanan-longitude').value = lngValue;
            document.getElementById('titik-layanan-latitude-display').value = latValue;
            document.getElementById('titik-layanan-longitude-display').value = lngValue;
            document.getElementById('titik-layanan-koordinat').textContent = `Koordinat terpilih: ${latValue}, ${lngValue}`;

            if (titikLayananMarker) {
                titikLayananMarker.setLatLng([lat, lng]);
            } else {
                titikLayananMarker = L.marker([lat, lng]).addTo(titikLayananMap);
            }
        }

        function setupTitikLayananMap(initialLat = null, initialLng = null) {
            const fallbackLat = -6.9175;
            const fallbackLng = 107.6191;
            const hasInitial = initialLat !== null && initialLng !== null;
            const startLat = hasInitial ? initialLat : fallbackLat;
            const startLng = hasInitial ? initialLng : fallbackLng;

            if (!titikLayananMap) {
                titikLayananMap = L.map('titik-layanan-map', { scrollWheelZoom: true }).setView([startLat, startLng], hasInitial ? 15 : 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(titikLayananMap);

                titikLayananMap.on('click', function (event) {
                    setTitikLayananPoint(event.latlng.lat, event.latlng.lng);
                });
            } else {
                titikLayananMap.setView([startLat, startLng], hasInitial ? 15 : 13);
                titikLayananMap.invalidateSize();
            }

            if (hasInitial) {
                setTitikLayananPoint(initialLat, initialLng);
            } else {
                if (titikLayananMarker) {
                    titikLayananMap.removeLayer(titikLayananMarker);
                    titikLayananMarker = null;
                }
                document.getElementById('titik-layanan-latitude').value = '';
                document.getElementById('titik-layanan-longitude').value = '';
                document.getElementById('titik-layanan-latitude-display').value = '';
                document.getElementById('titik-layanan-longitude-display').value = '';
                document.getElementById('titik-layanan-koordinat').textContent = 'Klik peta untuk menentukan titik layanan.';
            }
        }

        // Kategori
        function editKategori(id, nama, deskripsi, poin) {
            document.getElementById('modal-title').textContent = 'Edit Kategori Sampah';
            document.getElementById('kategori-form').action = `/admin/kategori/${id}`;
            document.getElementById('kategori-form').insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PUT">');
            document.getElementById('kategori-nama').value = nama;
            document.getElementById('kategori-deskripsi').value = deskripsi;
            document.getElementById('kategori-poin').value = poin;
            openModal('modal-tambah-kategori');
        }

        // Reset form when opening modal for new kategori
        document.querySelector('[onclick*="modal-tambah-kategori"]').addEventListener('click', function() {
            document.getElementById('modal-title').textContent = 'Tambah Kategori Sampah';
            document.getElementById('kategori-form').action = '{{ route("admin.kategori.store") }}';
            const methodInput = document.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            document.getElementById('kategori-form').reset();
        });

        // Reward
        function editReward(id, nama, deskripsi, poin, stok, aktif) {
            document.getElementById('modal-reward-title').textContent = 'Edit Reward';
            document.getElementById('reward-form').action = `/admin/reward/${id}`;
            document.getElementById('reward-form').insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PUT">');
            document.getElementById('reward-nama').value = nama;
            document.getElementById('reward-deskripsi').value = deskripsi;
            document.getElementById('reward-poin').value = poin;
            document.getElementById('reward-stok').value = stok;
            document.getElementById('reward-aktif').checked = aktif;
            openModal('modal-tambah-reward');
        }

        // Reset form when opening modal for new reward
        document.querySelector('[onclick*="modal-tambah-reward"]').addEventListener('click', function() {
            document.getElementById('modal-reward-title').textContent = 'Tambah Reward';
            document.getElementById('reward-form').action = '{{ route("admin.reward.store") }}';
            const methodInput = document.querySelector('#reward-form input[name="_method"]');
            if (methodInput) methodInput.remove();
            document.getElementById('reward-form').reset();
            document.getElementById('reward-aktif').checked = true; // Default aktif
        });

        // Petugas
        function editPetugas(id, nama, email) {
            document.getElementById('modal-petugas-title').textContent = 'Edit Petugas';
            document.getElementById('petugas-form').action = `/admin/petugas/${id}`;
            document.getElementById('petugas-form').insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PUT">');
            document.getElementById('petugas-nama').value = nama;
            document.getElementById('petugas-email').value = email;
            document.getElementById('petugas-password').required = false;
            document.getElementById('petugas-password-confirm').required = false;
            document.getElementById('petugas-password').placeholder = 'Kosongkan jika tidak ingin mengubah';
            document.getElementById('petugas-password-confirm').placeholder = 'Kosongkan jika tidak ingin mengubah';
            openModal('modal-tambah-petugas');
        }

        // Reset form when opening modal for new petugas
        document.querySelector('[onclick*="modal-tambah-petugas"]').addEventListener('click', function() {
            document.getElementById('modal-petugas-title').textContent = 'Tambah Petugas';
            document.getElementById('petugas-form').action = '{{ route("admin.petugas.store") }}';
            const methodInput = document.querySelector('#petugas-form input[name="_method"]');
            if (methodInput) methodInput.remove();
            document.getElementById('petugas-form').reset();
            document.getElementById('petugas-password').required = true;
            document.getElementById('petugas-password-confirm').required = true;
            document.getElementById('petugas-password').placeholder = 'Minimal 8 karakter';
            document.getElementById('petugas-password-confirm').placeholder = 'Ulangi password';
        });

        // Titik Layanan
        function editTitikLayanan(id, nama, jenis, alamat, jam, latitude, longitude, sampah) {
            document.getElementById('modal-titik-layanan-title').textContent = 'Edit Titik Layanan';
            document.getElementById('titik-layanan-form').action = `/admin/titik-layanan/${id}`;
            document.getElementById('titik-layanan-form').insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PUT">');
            document.getElementById('titik-layanan-nama').value = nama;
            document.getElementById('titik-layanan-jenis').value = jenis;
            document.getElementById('titik-layanan-alamat').value = alamat;
            document.getElementById('titik-layanan-jam').value = jam;
            document.getElementById('titik-layanan-sampah').value = sampah;
            openModal('modal-tambah-titik-layanan');
            setTimeout(function () {
                setupTitikLayananMap(Number(latitude), Number(longitude));
            }, 120);
        }

        // Reset form when opening modal for new titik layanan
        document.getElementById('btn-open-titik-layanan-modal').addEventListener('click', function() {
            document.getElementById('modal-titik-layanan-title').textContent = 'Tambah Titik Layanan';
            document.getElementById('titik-layanan-form').action = '{{ route("admin.titik-layanan.store") }}';
            const methodInput = document.querySelector('#titik-layanan-form input[name="_method"]');
            if (methodInput) methodInput.remove();
            document.getElementById('titik-layanan-form').reset();
            setupTitikLayananMap();
        });

        function filterVerifikasi(btn, filter) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        // Charts (Dashboard)
        let chartsInit = false;
        function initCharts() {
            if (chartsInit) return;
            chartsInit = true;

            const lineCtx = document.getElementById('lineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
                    datasets: [{
                        label: 'Reports',
                        data: [120, 150, 180, 200, 250, 300, 350, 400, 450, 500, 550, 600],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: { plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { color: '#f1f5f9' } } } }
            });

            const barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: ['Plastik','Organik','Elektronik','Kertas','Logam'],
                    datasets: [{
                        data: [400, 300, 200, 150, 100],
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                    }]
                },
                options: { plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { color: '#f1f5f9' } } } }
            });

            const userBarCtx = document.getElementById('userBarChart').getContext('2d');
            new Chart(userBarCtx, {
                type: 'bar',
                data: {
                    labels: ['U1','U2','U3','U4','U5','U6','U7','U8'],
                    datasets: [{
                        data: [50, 45, 40, 35, 30, 25, 20, 15],
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    }]
                },
                options: { plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { color: '#f1f5f9' } } } }
            });

            const pieCtx = document.getElementById('pieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Plastik','Organik','Elektronik','Lainnya'],
                    datasets: [{
                        data: [40, 30, 20, 10],
                        backgroundColor: ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0'],
                        borderWidth: 0,
                    }]
                },
                options: { plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } } }, cutout: '65%' }
            });
        }

        let lapChartInit = false;
        function initLapChart() {
            if (lapChartInit) return;
            lapChartInit = true;
            const ctx = document.getElementById('lapChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan','Feb','Mar','Apr'],
                    datasets: [
                        {
                            label: 'Reports',
                            data: [120, 150, 180, 200],
                            backgroundColor: '#10b981',
                        }
                    ]
                },
                options: { plugins: { legend: { position: 'bottom' } }, scales: { x: { grid: { display: false } }, y: { grid: { color: '#f1f5f9' } } } }
            });
        }
    </script>
</body>
</html>
