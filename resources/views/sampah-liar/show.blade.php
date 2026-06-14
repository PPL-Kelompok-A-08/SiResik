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
    {{-- Sidebar Konsisten --}}
    <x-sidebar />

        <main class="px-6 py-8 lg:px-10">
            @if (session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-6 py-4 text-emerald-800">
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between mb-8">
                <a href="{{ auth()->user()->role === 'admin' ? route('dashboard.admin') : route('sampah-liar.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold">
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
