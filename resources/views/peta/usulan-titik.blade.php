<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usul Lokasi Baru - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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
                    ['label' => 'Riwayat Layanan', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Poin & Reward', 'active' => false, 'href' => route('poin.index')],
                    ['label' => 'Sampah Liar', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Peta & Lokasi', 'active' => false, 'href' => route('peta.lokasi')],
                    ['label' => 'Usulkan Titik', 'active' => true, 'href' => route('peta.usulan-titik')],
                    ['label' => 'Edukasi Lingkungan', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Kegiatan Lingkungan', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Notifikasi', 'active' => false, 'href' => route('dashboard.masyarakat')],
                ];
            @endphp

            <nav class="mt-14 space-y-2">
                @foreach ($menuItems as $item)
                    <a href="{{ $item['href'] }}"
                        class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition {{ $item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5' }}">
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
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">R</div>
                    <div>
                        <p class="text-xl font-bold">{{ $user->name }}</p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Usul Lokasi Baru</h1>
                    <p class="mt-2 text-lg text-slate-500">Dashboard Masyarakat</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('peta.lokasi') }}" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700">Kembali ke Peta</a>
                </div>
            </header>

            <section class="mt-10 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm shadow-slate-200/50">
                <div class="mx-auto max-w-3xl">
                    <div class="text-center">
                        <h2 class="text-4xl font-black tracking-tight text-slate-800">Usulkan Titik Layanan Baru</h2>
                        <p class="mt-2 text-slate-500">Bantu kami meningkatkan layanan dengan lokasi titik penjemputan strategis.</p>
                    </div>

                    @if (session('success'))
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold">Usulan lokasi tidak disimpan.</p>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="mt-8 space-y-6" action="{{ route('peta.usulan-titik.store') }}" method="POST">
                        @csrf

                        <div>
                            <label class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-400">Pilih Lokasi di Peta</label>
                            <div id="usulan-map" class="h-[260px] overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"></div>
                            <p id="koordinat-terpilih" class="mt-2 text-sm text-slate-500">Ketuk map untuk menentukan koordinat.</p>
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                        </div>

                        <div>
                            <label for="alamat_detail" class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-400">Detail Alamat / Landmark</label>
                            <textarea id="alamat_detail" name="alamat_detail" rows="3" required
                                placeholder="Contoh: Depan Pos Kamling RT 04, sebelah Warung Ija"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">{{ old('alamat_detail') }}</textarea>
                        </div>

                        <div>
                            <label for="jenis_layanan" class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-400">Jenis Layanan</label>
                            <select id="jenis_layanan" name="jenis_layanan" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option value="">Pilih jenis layanan</option>
                                <option value="tps" @selected(old('jenis_layanan') === 'tps')>Titik Sampah</option>
                                <option value="bank_sampah" @selected(old('jenis_layanan') === 'bank_sampah')>Bank Sampah</option>
                            </select>
                        </div>

                        <div>
                            <label for="deskripsi_alasan" class="mb-2 block text-xs font-black uppercase tracking-[0.18em] text-slate-400">Deskripsi / Alasan Usulan</label>
                            <textarea id="deskripsi_alasan" name="deskripsi_alasan" rows="4" required
                                placeholder="Jelaskan alasan lokasi ini dibutuhkan..."
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">{{ old('deskripsi_alasan') }}</textarea>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-[#0c5b49] px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-800">
                            Kirim Usulan
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
    (function () {
        const bandung = [-6.9175, 107.6191];
        const map = L.map('usulan-map', { scrollWheelZoom: true }).setView(bandung, 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const selectedText = document.getElementById('koordinat-terpilih');
        let marker = null;

        function setPoint(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
            selectedText.textContent = 'Koordinat terpilih: ' + lat.toFixed(7) + ', ' + lng.toFixed(7);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }
        }

        map.on('click', function (event) {
            setPoint(event.latlng.lat, event.latlng.lng);
        });

        if (latInput.value && lngInput.value) {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                setPoint(lat, lng);
                map.setView([lat, lng], 15);
            }
        }
    })();
    </script>
</body>
</html>
