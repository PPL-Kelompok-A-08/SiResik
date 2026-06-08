<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

            @php
                $menuItems = [
                    ['label' => 'Dashboard', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Penjemputan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Status Layanan', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Riwayat Layanan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Poin & Reward', 'active' => false, 'href' => route('poin.index')],
                    ['label' => 'Sampah Liar', 'active' => true, 'href' => route('sampah-liar.index')],
                    ['label' => 'Peta & Lokasi', 'active' => false, 'href' => route('peta.lokasi')],
                    ['label' => 'Usulkan Titik', 'active' => false, 'href' => route('peta.usulan-titik')],
                    ['label' => 'Edukasi Lingkungan', 'active' => false, 'disabled' => true],
                    ['label' => 'Kegiatan Lingkungan', 'active' => false, 'disabled' => true],
                    ['label' => 'Notifikasi', 'active' => false, 'disabled' => true],
                ];
            @endphp

            <nav class="mt-14 space-y-2">
                @foreach ($menuItems as $item)
                    @if (!empty($item['href']))
                        <a href="{{ $item['href'] }}"
                            class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
                            <span class="text-xl">{{ $item['active'] ? '◉' : '◦' }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @else
                        <div class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-200 opacity-70 cursor-not-allowed">
                            <span class="text-xl">◦</span>
                            <span>{{ $item['label'] }}</span>
                        </div>
                    @endif
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
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>
                        <p class="text-xl font-bold">{{ auth()->user()->name }}</p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-6 py-4 text-emerald-800">
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between mb-8">
                <a href="{{ route('sampah-liar.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold">
                    ← Kembali ke Daftar Laporan
                </a>
            </header>

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Foto dan Info Utama -->
                <div class="lg:col-span-2">
                    @if ($sampahLiar->foto)
                        <div class="rounded-[2rem] overflow-hidden shadow-sm mb-6">
                            <img src="{{ asset('storage/' . $sampahLiar->foto) }}" alt="{{ $sampahLiar->lokasi }}" class="w-full h-96 object-cover">
                        </div>
                    @else
                        <div class="rounded-[2rem] overflow-hidden shadow-sm mb-6 bg-slate-200 h-96 flex items-center justify-center">
                            <span class="text-6xl">📷</span>
                        </div>
                    @endif

                    <div class="rounded-[2rem] bg-white p-8 shadow-sm">
                        <div class="mb-6">
                            <h1 class="text-4xl font-black text-slate-800">{{ $sampahLiar->lokasi }}</h1>
                            <p class="mt-3 text-lg text-slate-600">{{ $sampahLiar->deskripsi }}</p>
                        </div>

                        <div class="border-t border-slate-200 pt-6">
                            <h2 class="font-bold text-slate-800 mb-4">Informasi Laporan</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Estimasi Jumlah</p>
                                    <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $sampahLiar->jumlah_estimasi }} Ember</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Status Laporan</p>
                                    <p class="mt-1">
                                        <span class="inline-block rounded-full {{ $sampahLiar->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($sampahLiar->status === 'diverifikasi' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }} px-4 py-2 font-bold">
                                            {{ ucfirst($sampahLiar->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Koordinat</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $sampahLiar->latitude }}, {{ $sampahLiar->longitude }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Tanggal Laporan</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $sampahLiar->created_at->translatedFormat('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($sampahLiar->catatan_admin)
                            <div class="border-t border-slate-200 pt-6 mt-6">
                                <h2 class="font-bold text-slate-800 mb-3">Catatan Admin</h2>
                                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 text-blue-900">
                                    {{ $sampahLiar->catatan_admin }}
                                </div>
                            </div>
                        @endif

                        <!-- Peta -->
                        <div class="border-t border-slate-200 pt-6 mt-6">
                            <h2 class="font-bold text-slate-800 mb-4">Lokasi di Peta</h2>
                            <div id="map" class="rounded-2xl overflow-hidden" style="height: 400px;"></div>
                        </div>

                        <!-- Aksi untuk pemilik laporan -->
                        @if (auth()->id() === $sampahLiar->pengguna_id && $sampahLiar->status === 'pending')
                            <div class="border-t border-slate-200 pt-6 mt-6 flex gap-3">
                                <a href="{{ route('sampah-liar.edit', $sampahLiar) }}" class="flex-1 rounded-2xl bg-emerald-500 px-6 py-3 text-center text-lg font-bold text-white hover:bg-emerald-600 transition">
                                    Edit Laporan
                                </a>
                                <form action="{{ route('sampah-liar.destroy', $sampahLiar) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')" class="w-full rounded-2xl bg-red-500 px-6 py-3 text-lg font-bold text-white hover:bg-red-600 transition">
                                        Hapus Laporan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Pelapor -->
                <div>
                    <div class="rounded-[2rem] bg-white p-6 shadow-sm sticky top-8">
                        <h3 class="font-bold text-slate-800 mb-4">Pelapor</h3>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500 text-2xl font-black text-white">
                                {{ substr($sampahLiar->pengguna->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $sampahLiar->pengguna->name }}</p>
                                <p class="text-sm text-slate-500">{{ $sampahLiar->pengguna->email }}</p>
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-4">
                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Tanggal Laporan</p>
                            <p class="mt-2 text-slate-700">{{ $sampahLiar->created_at->translatedFormat('l, d F Y') }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $sampahLiar->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="border-t border-slate-200 pt-4 mt-4">
                            <p class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Status Verifikasi</p>
                            <div class="mt-2">
                                @if ($sampahLiar->status === 'pending')
                                    <div class="flex items-center gap-2 text-yellow-600">
                                        <span class="inline-block w-3 h-3 rounded-full bg-yellow-600 animate-pulse"></span>
                                        <span class="font-semibold">Menunggu Verifikasi</span>
                                    </div>
                                    <p class="text-sm text-slate-500 mt-2">Tim kami sedang memverifikasi laporan Anda</p>
                                @elseif ($sampahLiar->status === 'diverifikasi')
                                    <div class="flex items-center gap-2 text-blue-600">
                                        <span class="inline-block w-3 h-3 rounded-full bg-blue-600"></span>
                                        <span class="font-semibold">Terverifikasi</span>
                                    </div>
                                    <p class="text-sm text-slate-500 mt-2">Laporan telah diverifikasi dan akan ditindaklanjuti</p>
                                @else
                                    <div class="flex items-center gap-2 text-green-600">
                                        <span class="inline-block w-3 h-3 rounded-full bg-green-600"></span>
                                        <span class="font-semibold">Ditangani</span>
                                    </div>
                                    <p class="text-sm text-slate-500 mt-2">Sampah liar di lokasi ini telah dibersihkan</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize map
        const map = L.map('map').setView([{{ $sampahLiar->latitude }}, {{ $sampahLiar->longitude }}], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        L.marker([{{ $sampahLiar->latitude }}, {{ $sampahLiar->longitude }}])
            .addTo(map)
            .bindPopup('<div class="font-bold">{{ $sampahLiar->lokasi }}</div><div class="text-sm text-slate-600">{{ $sampahLiar->deskripsi }}</div>');
    </script>
</body>
</html>
