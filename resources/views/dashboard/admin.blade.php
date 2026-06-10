<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal Penjemputan - SiResik</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .stat-card { border-radius: 16px; padding: 16px 18px; min-height: 80px; display: flex; align-items: center; justify-content: space-between; }
        .section-card { background: white; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid #f1f5f9; }
        .chart-panel { position: relative; height: 200px; min-height: 0; }
        .chart-panel-sm { height: 170px; }
        .chart-panel-md { height: 220px; }
        .chart-panel canvas { display: block; width: 100% !important; height: 100% !important; }
        .stat-gradient { background: linear-gradient(135deg, #064e3b 0%, #059669 55%, #10b981 100%); color: #fff; }
        .stat-card-plain { background: #fff; border: 1px solid #e2e8f0; color: #064e3b; }
        .filter-chip { border-radius: 999px; border: 1px solid #cbd5e1; padding: 9px 14px; font-size: 13px; font-weight: 700; color: #475569; background: #fff; }
        .filter-chip.active { border-color: #10b981; background: #ecfdf5; color: #047857; }
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
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/20">
                    <i class="fas fa-recycle text-xl text-emerald-200"></i>
                </div>
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
                $adminMenuItems = [
                    ['label' => 'Admin Dashboard', 'page' => 'dashboard', 'icon' => 'fa-chart-pie'],
                    ['label' => 'Kelola Jadwal', 'page' => 'jadwal', 'icon' => 'fa-calendar-check'],
                    ['label' => 'Verifikasi Laporan', 'page' => 'verifikasi', 'icon' => 'fa-clipboard-check'],
                    ['label' => 'Kategori & Reward', 'page' => 'kategori', 'icon' => 'fa-tags'],
                    ['label' => 'Kelola Reward', 'page' => 'reward', 'icon' => 'fa-gift'],
                    ['label' => 'Area Layanan', 'page' => 'area', 'icon' => 'fa-map-location-dot'],
                    ['label' => 'Pantau Petugas', 'page' => 'petugas', 'icon' => 'fa-user-shield'],
                    ['label' => 'Riwayat Layanan', 'page' => 'riwayat', 'icon' => 'fa-clock-rotate-left'],
                    ['label' => 'Laporan Periodik', 'page' => 'laporan', 'icon' => 'fa-file-lines'],
                ];
            @endphp

            <nav class="mt-14 space-y-2">
                @foreach ($adminMenuItems as $item)
                    <a onclick="showPage('{{ $item['page'] }}')"
                        data-page="{{ $item['page'] }}"
                        class="nav-item flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition cursor-pointer {{ $item['page'] === 'dashboard' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
                        <span class="flex w-6 shrink-0 items-center justify-center">
                            <i class="fas {{ $item['icon'] }} text-base"></i>
                        </span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="mt-8">
                @csrf
                <button type="submit" class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-50 transition hover:bg-white/5">
                    <span class="flex w-6 shrink-0 items-center justify-center">
                        <i class="fas fa-right-from-bracket text-base"></i>
                    </span>
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
            <div id="page-dashboard" class="page active">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Admin Statistics Dashboard</h1>
                        <p class="mt-1 text-slate-500">Monitor waste management performance and user participation.</p>
                    </div>
                    <a onclick="showPage('laporan')" class="cursor-pointer rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-700 hover:bg-emerald-100">Laporan Periodik</a>
                </div>

                <!-- Key Metrics -->
                <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
                    <div class="stat-card stat-gradient">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide opacity-80">Total Reports</p>
                            <p class="mt-1 text-2xl font-black">{{ $stats['total_permintaan'] }}</p>
                        </div>
                    </div>
                    <div class="stat-card stat-gradient">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide opacity-80">Active Users</p>
                            <p class="mt-1 text-2xl font-black">{{ $stats['masyarakat'] }}</p>
                        </div>
                    </div>
                    <div class="stat-card stat-gradient">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide opacity-80">Total Officers</p>
                            <p class="mt-1 text-2xl font-black">{{ $stats['petugas'] }}</p>
                        </div>
                    </div>
                    <div class="stat-card stat-gradient">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide opacity-80">Pending</p>
                            <p class="mt-1 text-2xl font-black">{{ $stats['menunggu'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-2 gap-3 lg:grid-cols-3">
                    <div class="stat-card stat-card-plain">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Completed</p>
                            <p class="mt-1 text-2xl font-black text-emerald-700">{{ $stats['selesai'] }}</p>
                        </div>
                    </div>
                    <div class="stat-card stat-card-plain">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">In Progress</p>
                            <p class="mt-1 text-2xl font-black text-emerald-700">{{ $stats['diproses'] }}</p>
                        </div>
                    </div>
                    <div class="stat-card stat-card-plain col-span-2 lg:col-span-1">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Registered Users</p>
                            <p class="mt-1 text-2xl font-black text-emerald-700">{{ $stats['total_user'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="section-card p-5 mb-5">
                    <div class="mb-4 flex items-end justify-between gap-4">
                        <div>
                            <h2 class="text-base font-black text-slate-800">Reports Over Time</h2>
                            <p class="text-xs text-slate-400">Filtered periodic report data</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.4fr,0.6fr]">
                        <div class="chart-panel chart-panel-md">
                            <canvas id="lineChart"></canvas>
                        </div>
                        <div class="chart-panel chart-panel-sm">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="section-card p-5 mb-5">
                    <div class="mb-4">
                        <h2 class="text-base font-black text-slate-800">Waste & User Analytics</h2>
                        <p class="text-xs text-slate-400">Category volume and top contributors</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="chart-panel chart-panel-sm">
                            <canvas id="barChart"></canvas>
                        </div>
                        <div class="chart-panel chart-panel-sm">
                            <canvas id="userBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="section-card p-6 mb-6">
                    <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                        <div>
                            <h2 class="text-lg font-black text-slate-800">Quick Filter Reports</h2>
                            <p class="text-sm text-slate-400">Filter Laporan Periodik by status and time range.</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-500">{{ $periodicSummary['total'] }} reports matched</span>
                    </div>
                    <form method="GET" action="{{ route('dashboard.admin') }}" class="grid grid-cols-1 gap-3 lg:grid-cols-[1fr,1fr,1fr,auto] lg:items-end">
                        <input type="hidden" name="section" value="laporan">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Periode Mulai</label>
                            <input type="date" name="report_start" value="{{ $reportFilters['start'] }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Periode Akhir</label>
                            <input type="date" name="report_end" value="{{ $reportFilters['end'] }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Status</label>
                            <select name="report_status" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option value="all" @selected($reportFilters['status'] === 'all')>Semua Status</option>
                                <option value="Menunggu" @selected($reportFilters['status'] === 'Menunggu')>Menunggu</option>
                                <option value="Diproses" @selected($reportFilters['status'] === 'Diproses')>Diproses</option>
                                <option value="Selesai" @selected($reportFilters['status'] === 'Selesai')>Selesai</option>
                                <option value="Dibatalkan" @selected($reportFilters['status'] === 'Dibatalkan')>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Apply</button>
                            <a href="{{ route('dashboard.admin', ['section' => 'laporan']) }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- System Overview -->
                <div class="section-card p-5 mb-6">
                    <h2 class="text-base font-black text-slate-800 mb-4">System Overview</h2>
                    <div class="grid grid-cols-3 gap-3 rounded-2xl border border-emerald-100 bg-emerald-50/50 p-4">
                        <div class="text-center">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Masyarakat</p>
                            <p class="mt-1 text-xl font-black text-emerald-700">{{ $stats['masyarakat'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Petugas</p>
                            <p class="mt-1 text-xl font-black text-emerald-700">{{ $stats['petugas'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rewards</p>
                            <p class="mt-1 text-xl font-black text-emerald-700">{{ $rewards->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================== PAGE: JADWAL ======================== -->
            <div id="page-jadwal" class="page">
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

            <!-- Manajemen Status Permintaan -->
            <section class="mt-8 overflow-hidden rounded-[2rem] bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                    <h2 class="text-2xl font-black text-slate-800">Manajemen Status Permintaan</h2>
                </div>

                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pengguna</th>
                                <th>Alamat</th>
                                <th>Status Saat Ini</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permintaanForStatus as $item)
                                <tr>
                                    <td>REQ-{{ 101 + $loop->index }}</td>
                                    <td>{{ $item->pengguna?->name }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>
                                        <span class="status-badge {{ 
                                            $item->status === 'Menunggu' ? 'status-menunggu' : 
                                            ($item->status === 'Diproses' ? 'status-dijadwalkan' : 'status-selesai')
                                        }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at?->format('d M Y') }}</td>
                                    <td>
                                        <button onclick="openUpdateStatusModal({{ $item->id }}, '{{ $item->status }}', '{{ $item->pengguna?->name }}', '{{ $item->alamat }}')" class="text-blue-500 hover:text-blue-700 text-sm font-semibold px-3 py-1 rounded border border-blue-200 hover:bg-blue-50 transition">
                                            Update Status
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-slate-500">Tidak ada permintaan penjemputan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                            ['hari' => 'Senin', 'zona' => 'Bojongsoang, Desa Buah Batu', 'jam' => '08:00 WIB', 'petugas' => 'Ahmad'],
                            ['hari' => 'Selasa', 'zona' => 'Bojongsoang, Desa Bojongsoang', 'jam' => '08:00 WIB', 'petugas' => 'Bambang'],
                            ['hari' => 'Rabu', 'zona' => 'Baleendah, Kelurahan Jelekong', 'jam' => '09:00 WIB', 'petugas' => 'Cecep'],
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
                        <span class="text-sm text-slate-500">Total: {{ $permintaan->count() + $laporanSampahLiar->count() }} laporan</span>
                    </div>
                    <div class="grid gap-4 p-6 md:grid-cols-2">
                        @if($permintaan->isEmpty() && $laporanSampahLiar->isEmpty())
                        <div class="col-span-full text-center py-8 text-slate-500">
                            Tidak ada laporan untuk diverifikasi.
                        </div>
                        @else
                        @foreach($permintaan as $laporan)
                        @php
                            $statusFilter = $laporan->status === 'Selesai' ? 'disetujui' : 'menunggu';
                        @endphp
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm verifikasi-card" data-status="{{ $statusFilter }}" data-type="permintaan">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">No. {{ $loop->iteration }}</span>
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">Permintaan</span>
                                        <span>ID: JOB-{{ str_pad($laporan->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $laporan->pengguna?->name ?? 'Pengguna Tidak Diketahui' }}</p>
                                    <p class="text-sm text-slate-500">{{ $laporan->pengguna?->email ?? 'Email tidak tersedia' }}</p>
                                </div>
                                <span class="status-badge {{ $laporan->status === 'Menunggu' ? 'status-menunggu' : ($laporan->status === 'Selesai' ? 'status-selesai' : 'status-dibatalkan') }}">{{ $laporan->status }}</span>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <p class="font-semibold text-slate-800">Alamat / Lokasi</p>
                                    <p class="mt-2 text-sm text-slate-600">{{ $laporan->alamat ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <p class="font-semibold text-slate-800">Jadwal</p>
                                    <p class="mt-2 text-sm text-slate-600">{{ $laporan->tanggal ?? 'Belum tersedia' }}</p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-3 items-center">
                                <a href="{{ route('admin.permintaan.show', $laporan) }}" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Detail</a>
                                @if($laporan->status === 'Menunggu')
                                <form method="POST" action="{{ route('admin.verifikasi-laporan', $laporan) }}" style="display: inline;" onsubmit="showVerifikasiSuccess('disetujui'); return true;">
                                    @csrf
                                    <input type="hidden" name="status" value="disetujui">
                                    <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-600 transition">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.verifikasi-laporan', $laporan) }}" style="display: inline;" onsubmit="showVerifikasiSuccess('ditolak'); return true;">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Tolak</button>
                                </form>
                                @else
                                <div class="rounded-2xl bg-slate-100 px-4 py-2 text-sm text-slate-600">Status: {{ $laporan->status }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @foreach($laporanSampahLiar as $laporan)
                        @php $statusFilter = $laporan->status === 'diverifikasi' ? 'disetujui' : 'menunggu'; @endphp
                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm verifikasi-card" data-status="{{ $statusFilter }}" data-type="sampah_liar">
                            @if ($laporan->foto)
                                <div class="mb-6 overflow-hidden rounded-3xl border border-slate-200 bg-slate-100">
                                    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto laporan {{ $laporan->lokasi ?? 'sampah liar' }}" class="h-56 w-full object-cover">
                                </div>
                            @endif
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">No. {{ $loop->iteration }}</span>
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">Sampah Liar</span>
                                        <span>ID: SL-{{ str_pad($laporan->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $laporan->pengguna?->name ?? 'Pengguna Tidak Diketahui' }}</p>
                                    <p class="text-sm text-slate-500">{{ $laporan->pengguna?->email ?? 'Email tidak tersedia' }}</p>
                                </div>
                                <span class="status-badge {{ $laporan->status === 'pending' ? 'status-menunggu' : ($laporan->status === 'diverifikasi' ? 'status-selesai' : 'status-dibatalkan') }}">
                                    {{ $laporan->status === 'pending' ? 'Menunggu' : ($laporan->status === 'diverifikasi' ? 'Disetujui' : 'Ditolak') }}
                                </span>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <p class="font-semibold text-slate-800">Lokasi</p>
                                    <p class="mt-2 text-sm text-slate-600">{{ $laporan->lokasi ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                                <div class="rounded-3xl bg-slate-50 p-4 text-sm text-slate-600">
                                    <p class="font-semibold text-slate-800">Tanggal Laporan</p>
                                    <p class="mt-2 text-sm text-slate-600">{{ $laporan->created_at?->format('d M Y') ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-3 items-center">
                                <a href="{{ route('admin.sampah-liar.show', $laporan) }}" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Detail</a>
                                @if($laporan->status === 'pending')
                                <form method="POST" action="{{ route('admin.verifikasi-laporan-sampah-liar', $laporan) }}" style="display: inline;" onsubmit="showVerifikasiSuccess('disetujui'); return true;">
                                    @csrf
                                    <input type="hidden" name="status" value="disetujui">
                                    <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-600 transition">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.verifikasi-laporan-sampah-liar', $laporan) }}" style="display: inline;" onsubmit="showVerifikasiSuccess('ditolak'); return true;">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Tolak</button>
                                </form>
                                @else
                                <div class="rounded-2xl bg-slate-100 px-4 py-2 text-sm text-slate-600">Status: {{ $laporan->status === 'diverifikasi' ? 'Disetujui' : 'Ditolak' }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        @endif
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
                                <th>Harga/kg</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\KategoriSampah::all() as $kategori)
                            <tr class="cursor-pointer hover:bg-slate-50" onclick="toggleKategoriRow({{ $kategori->id }})">
                                <td class="py-4 px-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <span>{{ $kategori->nama }}</span>
                                        <span id="kategori-arrow-{{ $kategori->id }}" class="text-slate-400">▸</span>
                                    </div>
                                </td>
                                <td>{{ $kategori->poin_per_kg }}</td>
                                <td>Rp {{ number_format($kategori->harga_per_kg, 0, ',', '.') }}</td>
                                <td>{{ Str::limit($kategori->deskripsi, 35) }}</td>
                                <td>
                                    <button onclick="event.stopPropagation(); editKategori({{ $kategori->id }}, @json($kategori->nama), @json($kategori->deskripsi), {{ $kategori->poin_per_kg }}, {{ $kategori->harga_per_kg }})" class="text-emerald-500 hover:text-emerald-700">Edit</button>
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" style="display: inline;" onsubmit="event.stopPropagation(); return confirm('Yakin hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <tr id="kategori-{{ $kategori->id }}" class="hidden bg-slate-50">
                                <td colspan="5" class="px-4 py-4">
                                    <div class="grid gap-4 md:grid-cols-[2fr,1fr] items-center">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">Deskripsi</p>
                                            <p class="mt-2 text-sm text-slate-600">{{ $kategori->deskripsi }}</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2 justify-end">
                                            <button onclick="event.stopPropagation(); editKategori({{ $kategori->id }}, @json($kategori->nama), @json($kategori->deskripsi), {{ $kategori->poin_per_kg }}, {{ $kategori->harga_per_kg }})" class="rounded-xl border border-emerald-500 px-4 py-2 text-sm font-semibold text-emerald-600 hover:bg-emerald-50">Edit</button>
                                            <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" class="inline" onsubmit="event.stopPropagation(); return confirm('Yakin hapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-red-500 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
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
                        <p class="text-sm text-slate-500">Penghargaan Aktif</p>
                        <p class="text-2xl font-black text-slate-800">{{ $rewards->where('aktif', true)->count() }}</p>
                    </div>
                    <div class="section-card p-5">
                        <p class="text-sm text-slate-500">Total Stok</p>
                        <p class="text-2xl font-black text-slate-800">{{ $rewards->sum('stok') }}</p>
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
                                <td colspan="4" class="text-center py-6 text-slate-500">Belum ada data penukaran reward.</td>
                            </tr>
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
                    <button type="button"
                        onclick="var m=document.getElementById('modalTambahArea'); if(m){m.classList.add('open');} setTimeout(function(){ if(window.__initZonaRadiusMap) window.__initZonaRadiusMap(); },120);"
                        class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">
                        + Tambah Area
                    </button>
                </div>

                @include('peta._leaflet-area-admin-map', ['titikLayanan' => $titikLayanan, 'zonaLayanan' => $zonaLayanan, 'usulanMenunggu' => $usulanMenunggu])

                <div class="section-card mt-6">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-slate-800">Usulan Titik Layanan Menunggu Verifikasi</h2>
                            <p class="mt-1 text-sm text-slate-500">Usulan dari masyarakat akan tampil sebagai marker oranye di peta.</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-500">{{ $usulanMenunggu->count() }} usulan menunggu</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse ($usulanMenunggu as $usulan)
                            <article class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-5">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-xl bg-amber-100 px-3 py-1 text-xs font-black uppercase tracking-[0.12em] text-amber-700">Diajukan</span>
                                            <span class="rounded-xl bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                                {{ $usulan->jenis_layanan === 'bank_sampah' ? 'Bank Sampah' : 'Titik Sampah' }}
                                            </span>
                                        </div>

                                        <p class="mt-3 text-lg font-bold text-slate-800">{{ $usulan->alamat_detail }}</p>
                                        <p class="mt-1 text-sm text-slate-600">Koordinat: {{ number_format($usulan->latitude, 6) }}, {{ number_format($usulan->longitude, 6) }}</p>
                                        <p class="mt-2 text-sm text-slate-600">Alasan: {{ $usulan->deskripsi_alasan }}</p>
                                        <p class="mt-2 text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">
                                            Pengusul: {{ $usulan->pengusul?->name ?? 'Pengguna' }} • {{ optional($usulan->created_at)->translatedFormat('d M Y H:i') }}
                                        </p>
                                    </div>

                                    <div class="flex w-full flex-col gap-2 sm:w-auto">
                                        <form method="POST" action="{{ route('dashboard.admin.usulan.approve', $usulan) }}">
                                            @csrf
                                            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-emerald-700">
                                                Setujui & Jadikan Titik Layanan
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('dashboard.admin.usulan.reject', $usulan) }}" class="space-y-2">
                                            @csrf
                                            <input type="text" name="catatan_verifikasi" placeholder="Alasan penolakan (opsional)"
                                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100">
                                            <button type="submit" class="w-full rounded-xl border border-red-300 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-100">
                                                Tolak Usulan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-10 text-center text-sm text-slate-500">
                                Tidak ada usulan yang menunggu verifikasi saat ini.
                            </div>
                        @endforelse
                    </div>
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
                                        <a href="{{ route('admin.jadwal.index', $titik->id) }}" class="text-emerald-500 hover:text-emerald-700 text-sm font-semibold ml-2">🕒 Jadwal</a>
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
                        <button type="button" onclick="exportServiceHistoryCsv()" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold">Export CSV</button>
                        <button type="button" onclick="filterServiceHistory()" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Filter</button>
                    </div>
                </div>

                <!-- Filter -->
                <div class="section-card mb-6 p-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Tanggal Mulai</label>
                            <input type="date" id="history-start" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Tanggal Akhir</label>
                            <input type="date" id="history-end" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Status</label>
                            <select id="history-status" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option value="all">Semua</option>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Petugas</label>
                            <select id="history-petugas" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option value="all">Semua</option>
                                @foreach($petugas as $petugasItem)
                                <option value="{{ $petugasItem->id }}">{{ $petugasItem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-black text-slate-800">Riwayat Penjemputan</h2>
                        <span class="text-sm text-slate-500">Total: {{ $permintaan->count() }} records</span>
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
                            @forelse($permintaan as $history)
                            <tr class="history-row" data-date="{{ $history->created_at->format('Y-m-d') }}" data-status="{{ $history->status }}" data-petugas="{{ $history->petugas_id ?? 'none' }}">
                                <td>REQ-{{ str_pad($history->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $history->pengguna?->name ?? 'Pengguna Tidak Diketahui' }}</td>
                                <td>{{ $history->petugas?->name ?? '-' }}</td>
                                <td>{{ $history->created_at->format('Y-m-d') }}</td>
                                <td><span class="status-badge {{ $history->status === 'Menunggu' ? 'status-menunggu' : ($history->status === 'Selesai' ? 'status-selesai' : ($history->status === 'Diproses' ? 'status-dijadwalkan' : 'status-dibatalkan')) }}">{{ $history->status }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-slate-500">Belum ada riwayat layanan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-slate-100">
                        <p id="history-count" class="text-sm text-slate-500">Menampilkan {{ $permintaan->count() }} dari {{ $permintaan->count() }} records</p>
                    </div>
                </div>
            </div>

            <!-- ======================== PAGE: LAPORAN ======================== -->
            <div id="page-laporan" class="page">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-4xl font-black text-slate-900">Laporan Periodik</h1>
                        <p class="mt-1 text-slate-500">Generate and view periodic reports from pickup request data.</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="exportPeriodicReportCsv()" class="rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold">Export CSV</button>
                        <button type="submit" form="periodic-report-filter" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Generate Report</button>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="section-card mb-6 p-5">
                    <form id="periodic-report-filter" method="GET" action="{{ route('dashboard.admin') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                        <input type="hidden" name="section" value="laporan">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Periode Mulai</label>
                            <input type="date" name="report_start" value="{{ $reportFilters['start'] }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Periode Akhir</label>
                            <input type="date" name="report_end" value="{{ $reportFilters['end'] }}" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Tipe Laporan</label>
                            <select name="report_type" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option value="monthly" @selected($reportFilters['type'] === 'monthly')>Bulanan</option>
                                <option value="weekly" @selected($reportFilters['type'] === 'weekly')>Mingguan</option>
                                <option value="daily" @selected($reportFilters['type'] === 'daily')>Harian</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Status</label>
                            <select name="report_status" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                                <option value="all" @selected($reportFilters['status'] === 'all')>Semua Status</option>
                                <option value="Menunggu" @selected($reportFilters['status'] === 'Menunggu')>Menunggu</option>
                                <option value="Diproses" @selected($reportFilters['status'] === 'Diproses')>Diproses</option>
                                <option value="Selesai" @selected($reportFilters['status'] === 'Selesai')>Selesai</option>
                                <option value="Dibatalkan" @selected($reportFilters['status'] === 'Dibatalkan')>Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-bold text-white">Terapkan Filter</button>
                    </form>
                </div>

                <!-- Summary -->
                <div class="section-card p-6 mb-6">
                    <h2 class="text-xl font-black text-slate-800 mb-4">Ringkasan Periode</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">{{ $periodicSummary['total'] }}</p>
                            <p class="text-sm text-slate-500">Total Laporan</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">{{ $periodicSummary['selesai'] }}</p>
                            <p class="text-sm text-slate-500">Laporan Selesai</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">{{ $periodicSummary['menunggu'] }}</p>
                            <p class="text-sm text-slate-500">Menunggu Diproses</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">{{ $periodicSummary['diproses'] }}</p>
                            <p class="text-sm text-slate-500">Diproses</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">{{ number_format($periodicSummary['total_berat'], 2) }}</p>
                            <p class="text-sm text-slate-500">Total Sampah (kg)</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black text-emerald-500">{{ $periodicSummary['pengguna_unik'] }}</p>
                            <p class="text-sm text-slate-500">Pengguna Aktif</p>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="section-card p-5 mb-6">
                    <h2 class="text-base font-black text-slate-800 mb-4">Tren Laporan Bulanan</h2>
                    <div class="chart-panel chart-panel-md">
                        <canvas id="lapChart"></canvas>
                    </div>
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
                            @forelse($periodicReports as $report)
                            <tr>
                                <td>
                                    <p class="font-semibold">{{ $report->created_at->format('Y-m-d') }}</p>
                                    <p class="text-xs text-slate-400">Jadwal: {{ $report->tanggal }}</p>
                                </td>
                                <td>
                                    <p class="font-semibold">Laporan #{{ $report->id }}</p>
                                    <p class="text-xs text-slate-400">{{ $report->pengguna?->name ?? 'Pengguna Tidak Diketahui' }}</p>
                                </td>
                                <td>{{ number_format($report->items->sum('berat_kg'), 2) }}</td>
                                <td><span class="status-badge {{ $report->status === 'Menunggu' ? 'status-menunggu' : ($report->status === 'Selesai' ? 'status-selesai' : ($report->status === 'Diproses' ? 'status-dijadwalkan' : 'status-dibatalkan')) }}">{{ $report->status }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-slate-500">Tidak ada laporan pada periode ini.</td>
                            </tr>
                            @endforelse
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
                            <option value="tps">TPS (Tempat Pembuangan Sementara)</option>
                            <option value="bank_sampah">Bank Sampah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Alamat</label>
                        <textarea name="alamat" id="titik-layanan-alamat" rows="2" placeholder="Alamat lengkap (opsional)..." class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm resize-none"></textarea>
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
                        <input type="text" name="jam_operasional" id="titik-layanan-jam" placeholder="Contoh: 08:00 - 17:00 (opsional)" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Jenis Sampah Diterima</label>
                        <input type="text" name="jenis_sampah_diterima" id="titik-layanan-sampah" placeholder="Contoh: Plastik, Organik, Kertas (opsional)" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('modal-tambah-titik-layanan')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Tambah Area Layanan (Zona) -->
    <div id="modalTambahArea" class="modal-overlay" onclick="closeModalOutside(event,'modalTambahArea')">
        <div class="modal" style="width: 920px; max-width: 92vw;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800">Tambah Area Layanan</h3>
                <button type="button" onclick="closeModal('modalTambahArea')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            <form id="formTambahArea" method="POST" action="{{ route('admin.zona-layanan.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-[360px,1fr]">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Nama Zona</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Zona A - Gedung Kuliah"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Warna Zona</label>
                            <div class="flex items-center gap-3 border border-slate-200 rounded-xl px-3 py-2">
                                <input type="color" id="zona-warna" name="warna" value="{{ old('warna', '#16a34a') }}" class="h-10 w-14 rounded-lg border border-slate-200 bg-white p-1">
                                <input type="text" id="zona-warna-text" value="{{ old('warna', '#16a34a') }}" readonly class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm bg-slate-50 font-semibold text-slate-700">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Cakupan Wilayah (Radius)</label>
                            <input type="number" id="zona-radius-km" inputmode="decimal" step="0.1" min="0.1" placeholder="Radius (km). Contoh: 1.0"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm">
                            <div class="mt-3 flex flex-wrap gap-2">
                                <button type="button" class="zona-radius-preset px-3 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-700" data-km="0.25">0.25 km</button>
                                <button type="button" class="zona-radius-preset px-3 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-700" data-km="0.5">0.5 km</button>
                                <button type="button" class="zona-radius-preset px-3 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-700" data-km="1">1 km</button>
                                <button type="button" class="zona-radius-preset px-3 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-700" data-km="2">2 km</button>
                                <button type="button" class="zona-radius-preset px-3 py-2 border border-slate-200 rounded-lg text-xs font-bold text-slate-700" data-km="5">5 km</button>
                            </div>
                            <p class="mt-3 text-xs text-slate-500">Klik peta untuk menentukan titik pusat. Area akan terbentuk berdasarkan radius.</p>
                        </div>

                        <input type="hidden" name="geojson" id="zona-geojson" value="{{ old('geojson') }}">
                        <input type="hidden" id="zona-center-lat" value="">
                        <input type="hidden" id="zona-center-lng" value="">

                        <div class="mt-6 flex gap-3">
                            <button type="button" onclick="closeModal('modalTambahArea')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                            <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white">Simpan Area</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Pilih Lokasi di Peta</label>
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                            <div id="zona-form-map" style="height: 420px; width: 100%;"></div>
                        </div>
                    </div>
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
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Poin per kg</label>
                            <input type="number" name="poin_per_kg" id="kategori-poin" placeholder="Contoh: 50" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Harga per kg (Rp)</label>
                            <input type="number" name="harga_per_kg" id="kategori-harga" placeholder="Contoh: 5000" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                        </div>
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
        // Initialize event listeners when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Kategori button listeners
            const kategoriAddBtn = document.querySelector('button[onclick*="modal-tambah-kategori"]');
            if (kategoriAddBtn) {
                kategoriAddBtn.addEventListener('click', function() {
                    document.getElementById('modal-title').textContent = 'Tambah Kategori Sampah';
                    document.getElementById('kategori-form').action = '{{ route("admin.kategori.store") }}';
                    const methodInput = document.querySelector('#kategori-form input[name="_method"]');
                    if (methodInput) methodInput.remove();
                    document.getElementById('kategori-form').reset();
                });
            }

            // Reward button listeners
            const rewardAddBtn = document.querySelector('button[onclick*="modal-tambah-reward"]');
            if (rewardAddBtn) {
                rewardAddBtn.addEventListener('click', function() {
                    document.getElementById('modal-reward-title').textContent = 'Tambah Reward';
                    document.getElementById('reward-form').action = '{{ route("admin.reward.store") }}';
                    const methodInput = document.querySelector('#reward-form input[name="_method"]');
                    if (methodInput) methodInput.remove();
                    document.getElementById('reward-form').reset();
                    document.getElementById('reward-aktif').checked = true;
                });
            }

            // Petugas button listeners
            const petugasAddBtn = document.querySelector('button[onclick*="modal-tambah-petugas"]');
            if (petugasAddBtn) {
                petugasAddBtn.addEventListener('click', function() {
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
            }

            // Titik Layanan button listeners
            const titikAddBtn = document.querySelector('button[onclick*="modal-tambah-titik-layanan"]');
            if (titikAddBtn) {
                titikAddBtn.addEventListener('click', function() {
                    document.getElementById('modal-titik-layanan-title').textContent = 'Tambah Titik Layanan';
                    document.getElementById('titik-layanan-form').action = '{{ route("admin.titik-layanan.store") }}';
                    const methodInput = document.querySelector('#titik-layanan-form input[name="_method"]');
                    if (methodInput) methodInput.remove();
                    document.getElementById('titik-layanan-form').reset();
                });
            }

            const activeSection = new URLSearchParams(window.location.search).get('section');
            if (activeSection) {
                showPage(activeSection);
            } else {
                setTimeout(initCharts, 100);
            }
        });

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

        function filterVerifikasi(btn, filter) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.verifikasi-card').forEach(card => {
                const status = card.dataset.status;
                if (filter === 'semua' || status === filter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        // Navigation
        function showPage(name) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            const targetPage = document.getElementById('page-' + name);
            if (!targetPage) return;
            targetPage.classList.add('active');
            document.querySelectorAll('nav .nav-item').forEach(n => {
                n.classList.remove('bg-emerald-600', 'text-white', 'shadow-lg', 'shadow-emerald-900/20');
                n.classList.add('text-emerald-50', 'hover:bg-white/5');
            });
            const activeNav = document.querySelector(`nav .nav-item[data-page="${name}"]`);
            if (activeNav) {
                activeNav.classList.remove('text-emerald-50', 'hover:bg-white/5');
                activeNav.classList.add('bg-emerald-600', 'text-white', 'shadow-lg', 'shadow-emerald-900/20');
            }

            if (name === 'dashboard') {
                setTimeout(initCharts, 100);
            }
            if (name === 'laporan') {
                setTimeout(initLapChart, 100);
            }
        }

        window.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const page = params.get('page');
            if (page) {
                showPage(page);
            }
        });

        // Modal
        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.add('open');
            if (id === 'modal-tambah-titik-layanan') {
                setTimeout(function () {
                    setupTitikLayananMap();
                }, 100);
            }
            if (id === 'modalTambahArea') {
                setTimeout(function () {
                    if (window.__initZonaRadiusMap) window.__initZonaRadiusMap();
                }, 120);
            }
        }
        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('open');
        }
        function closeModalOutside(e, id) { if (e.target.id === id) closeModal(id); }

        function openModalTambahArea() {
            openModal('modalTambahArea');
        }

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
        function editKategori(id, nama, deskripsi, poin, harga) {
            document.getElementById('modal-title').textContent = 'Edit Kategori Sampah';
            document.getElementById('kategori-form').action = `/admin/kategori/${id}`;
            document.getElementById('kategori-form').insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PUT">');
            document.getElementById('kategori-nama').value = nama;
            document.getElementById('kategori-deskripsi').value = deskripsi;
            document.getElementById('kategori-poin').value = poin;
            document.getElementById('kategori-harga').value = harga;
            openModal('modal-tambah-kategori');
        }

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

        function toggleKategoriRow(id) {
            const detailsRow = document.getElementById(`kategori-${id}`);
            const arrow = document.getElementById(`kategori-arrow-${id}`);
            if (!detailsRow) return;
            const expanded = !detailsRow.classList.toggle('hidden');
            if (arrow) arrow.textContent = expanded ? '▾' : '▸';
        }

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

            document.querySelectorAll('.verifikasi-card').forEach(card => {
                const status = card.dataset.status;
                if (filter === 'semua' || status === filter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        const periodicChartData = @json($chartData);
        const periodicExportRows = @json($periodicExportRows);

        const chartTheme = {
            gradient: ['#064e3b', '#047857', '#059669', '#10b981', '#34d399', '#6ee7b7'],
            line: '#059669',
            lineSoft: 'rgba(5, 150, 105, 0.15)',
            lineAlt: '#10b981',
            lineAltSoft: 'rgba(16, 185, 129, 0.12)',
            grid: '#ecfdf5',
            tick: '#94a3b8',
        };

        function greenBarColors(count) {
            return Array.from({ length: count }, (_, i) => {
                const t = count <= 1 ? 0.5 : i / (count - 1);
                const r = Math.round(6 + t * (16 - 6));
                const g = Math.round(78 + t * (185 - 78));
                const b = Math.round(59 + t * (129 - 59));
                return `rgb(${r}, ${g}, ${b})`;
            });
        }

        function makeLineFill(ctx) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, 'rgba(5, 150, 105, 0.28)');
            gradient.addColorStop(1, 'rgba(5, 150, 105, 0.02)');
            return gradient;
        }

        const baseChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { font: { size: 11 }, color: '#64748b', boxWidth: 12, padding: 14 },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, color: chartTheme.tick, maxRotation: 0 },
                },
                y: {
                    grid: { color: chartTheme.grid },
                    ticks: { font: { size: 10 }, color: chartTheme.tick },
                    beginAtZero: true,
                },
            },
        };

        function emptyChartMessage(ctx, message) {
            const chart = ctx.canvas;
            const wrapper = chart.parentElement;
            if (!wrapper || wrapper.querySelector('.empty-chart-message')) return;
            const empty = document.createElement('div');
            empty.className = 'empty-chart-message flex h-full min-h-[160px] items-center justify-center rounded-xl border border-dashed border-emerald-200 text-sm font-semibold text-slate-400';
            empty.textContent = message;
            chart.style.display = 'none';
            wrapper.appendChild(empty);
        }

        function hasChartData(data) {
            return Array.isArray(data) && data.some((value) => Number(value) > 0);
        }

        // Charts (Dashboard)
        let chartsInit = false;
        function initCharts() {
            if (chartsInit) return;
            chartsInit = true;

            const lineCtx = document.getElementById('lineChart')?.getContext('2d');
            if (lineCtx) {
                if (!hasChartData(periodicChartData.trend.reports)) {
                    emptyChartMessage(lineCtx, 'No report trend data for the selected period.');
                } else {
                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: periodicChartData.trend.labels,
                        datasets: [{
                            label: 'Reports',
                            data: periodicChartData.trend.reports,
                            borderColor: chartTheme.line,
                            backgroundColor: makeLineFill(lineCtx),
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: chartTheme.line,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1,
                        }]
                    },
                    options: {
                        ...baseChartOptions,
                        plugins: { legend: { display: false } },
                    }
                });
                }
            }

            const barCtx = document.getElementById('barChart')?.getContext('2d');
            if (barCtx) {
                if (!hasChartData(periodicChartData.category.data)) {
                    emptyChartMessage(barCtx, 'No waste category data for the selected period.');
                } else {
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: periodicChartData.category.labels,
                        datasets: [{
                            label: 'Total kg',
                            data: periodicChartData.category.data,
                            backgroundColor: greenBarColors(periodicChartData.category.data.length),
                            borderRadius: 6,
                            maxBarThickness: 36,
                        }]
                    },
                    options: {
                        ...baseChartOptions,
                        plugins: { legend: { display: false } },
                    }
                });
                }
            }

            const userBarCtx = document.getElementById('userBarChart')?.getContext('2d');
            if (userBarCtx) {
                if (!hasChartData(periodicChartData.users.data)) {
                    emptyChartMessage(userBarCtx, 'No user contribution data for the selected period.');
                } else {
                new Chart(userBarCtx, {
                    type: 'bar',
                    data: {
                        labels: periodicChartData.users.labels,
                        datasets: [{
                            label: 'Reports',
                            data: periodicChartData.users.data,
                            backgroundColor: greenBarColors(periodicChartData.users.data.length),
                            borderRadius: 6,
                            maxBarThickness: 36,
                        }]
                    },
                    options: {
                        ...baseChartOptions,
                        plugins: { legend: { display: false } },
                    }
                });
                }
            }

            const pieCtx = document.getElementById('pieChart')?.getContext('2d');
            if (pieCtx) {
                if (!hasChartData(periodicChartData.status.data)) {
                    emptyChartMessage(pieCtx, 'No status data for the selected period.');
                } else {
                new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: periodicChartData.status.labels,
                        datasets: [{
                            data: periodicChartData.status.data,
                            backgroundColor: chartTheme.gradient.slice(0, periodicChartData.status.data.length),
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 10 }, color: '#64748b', padding: 10, boxWidth: 10 },
                            },
                        },
                    }
                });
                }
            }
        }

        let lapChartInit = false;
        function initLapChart() {
            if (lapChartInit) return;
            lapChartInit = true;
            const ctx = document.getElementById('lapChart')?.getContext('2d');
            if (ctx) {
                if (!hasChartData(periodicChartData.trend.reports) && !hasChartData(periodicChartData.trend.weight)) {
                    emptyChartMessage(ctx, 'No periodic report data for the selected filter.');
                } else {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: periodicChartData.trend.labels,
                        datasets: [
                            {
                                label: 'Reports',
                                data: periodicChartData.trend.reports,
                                borderColor: chartTheme.line,
                                backgroundColor: chartTheme.lineSoft,
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointRadius: 3,
                                yAxisID: 'y',
                            },
                            {
                                label: 'Waste kg',
                                data: periodicChartData.trend.weight,
                                borderColor: chartTheme.lineAlt,
                                backgroundColor: chartTheme.lineAltSoft,
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointRadius: 3,
                                yAxisID: 'y1',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 11 }, color: '#64748b', boxWidth: 12, padding: 14 },
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 }, color: chartTheme.tick },
                            },
                            y: {
                                grid: { color: chartTheme.grid },
                                ticks: { font: { size: 10 }, color: chartTheme.tick },
                                beginAtZero: true,
                            },
                            y1: {
                                position: 'right',
                                grid: { drawOnChartArea: false },
                                ticks: { font: { size: 10 }, color: chartTheme.tick },
                                beginAtZero: true,
                            },
                        },
                    }
                });
                }
            }
        }

        function exportPeriodicReportCsv() {
            const header = ['Tanggal Laporan', 'Tanggal Jadwal', 'ID Laporan', 'Pengguna', 'Petugas', 'Status', 'Berat Kg', 'Poin'];
            const rows = periodicExportRows.map((row) => [
                row.tanggal_laporan,
                row.tanggal_jadwal,
                row.id_laporan,
                row.pengguna,
                row.petugas,
                row.status,
                row.berat_kg,
                row.poin,
            ]);
            const csv = [header, ...rows]
                .map((items) => items.map((item) => `"${String(item).replaceAll('"', '""')}"`).join(','))
                .join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'laporan-periodik.csv';
            link.click();
            URL.revokeObjectURL(link.href);
        }

        function filterServiceHistory() {
            const start = document.getElementById('history-start')?.value;
            const end = document.getElementById('history-end')?.value;
            const status = document.getElementById('history-status')?.value || 'all';
            const petugas = document.getElementById('history-petugas')?.value || 'all';
            let visible = 0;
            const rows = document.querySelectorAll('.history-row');

            rows.forEach((row) => {
                const rowDate = row.dataset.date;
                const matchesStart = !start || rowDate >= start;
                const matchesEnd = !end || rowDate <= end;
                const matchesStatus = status === 'all' || row.dataset.status === status;
                const matchesPetugas = petugas === 'all' || row.dataset.petugas === petugas;
                const show = matchesStart && matchesEnd && matchesStatus && matchesPetugas;
                row.classList.toggle('hidden', !show);
                if (show) visible += 1;
            });

            const count = document.getElementById('history-count');
            if (count) count.textContent = `Menampilkan ${visible} dari ${rows.length} records`;
        }

        function exportServiceHistoryCsv() {
            const rows = Array.from(document.querySelectorAll('.history-row:not(.hidden)')).map((row) => {
                const cells = Array.from(row.querySelectorAll('td')).map((cell) => cell.textContent.trim().replace(/\s+/g, ' '));
                return cells;
            });
            const csv = [['ID', 'User', 'Petugas', 'Tanggal', 'Status'], ...rows]
                .map((items) => items.map((item) => `"${String(item).replaceAll('"', '""')}"`).join(','))
                .join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'riwayat-layanan.csv';
            link.click();
            URL.revokeObjectURL(link.href);
        }
    </script>

    <!-- Modal: Update Status Permintaan -->
    <div id="modal-update-status" class="modal-overlay" onclick="closeModalOutside(event,'modal-update-status')">
        <div class="modal" style="max-width: 500px;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800">Update Status Permintaan</h3>
                <button onclick="closeModal('modal-update-status')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            
            <div class="bg-slate-50 rounded-lg p-4 mb-6 border border-slate-200">
                <p class="text-sm text-slate-500">Pengguna</p>
                <p class="font-semibold text-slate-800" id="status-modal-user">-</p>
                <p class="text-sm text-slate-500 mt-2">Alamat</p>
                <p class="text-slate-700" id="status-modal-alamat">-</p>
                <p class="text-sm text-slate-500 mt-2">Status Saat Ini</p>
                <span id="status-modal-current" class="status-badge status-menunggu">-</span>
            </div>

            <form id="update-status-form" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Status Baru</label>
                        <select name="status" id="status-select" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm" required>
                            <option value="">Pilih Status</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('modal-update-status')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-blue-500 py-3 text-sm font-bold text-white hover:bg-blue-600 transition">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openUpdateStatusModal(permintaanId, currentStatus, userName, alamat) {
            document.getElementById('status-modal-user').textContent = userName;
            document.getElementById('status-modal-alamat').textContent = alamat;
            document.getElementById('status-modal-current').textContent = currentStatus;
            document.getElementById('status-modal-current').className = 'status-badge ' + (
                currentStatus === 'Menunggu' ? 'status-menunggu' : 
                (currentStatus === 'Diproses' ? 'status-dijadwalkan' : 'status-selesai')
            );
            
            const form = document.getElementById('update-status-form');
            form.action = `/permintaan-penjemputan/${permintaanId}/status`;
            
            openModal('modal-update-status');
        }

        // Handle update status form submission
        document.getElementById('update-status-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const url = this.action;
            const statusValue = document.getElementById('status-select').value;
            
            fetch(url, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: statusValue
                })
            })
            .then(response => response.json())
            .then(data => {
                closeModal('modal-update-status');
                showSuccessMessage(data.message || 'Status berhasil diperbarui');
                setTimeout(() => location.reload(), 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat update status');
            });
        });

        function showSuccessMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => alertDiv.remove(), 3000);
        }
    </script>
</body>
</html>
