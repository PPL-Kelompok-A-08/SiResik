<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Sampah Liar - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen xl:grid xl:grid-cols-[300px,1fr]">
    {{-- Sidebar Konsisten --}}
    <x-sidebar />

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-800">Lapor Sampah Liar</h1>
                    <p class="mt-2 text-lg text-slate-500">Bantu kami menjaga kebersihan lingkungan dengan melaporkan titik pumpukan sampah liar di sekitar Anda.</p>
                </div>
                <a href="{{ route('sampah-liar.index') }}" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700 hover:bg-slate-50">← Kembali</a>
            </header>

            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                <!-- Form Laporan -->
                <div>
                    <div class="rounded-[2rem] bg-white p-8 shadow-sm">
                        <form action="{{ route('sampah-liar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <!-- Foto Bukti -->
                            <div>
                                <label class="block text-lg font-bold text-slate-700 mb-4">Foto Bukti</label>
                                <div id="dropZone" class="border-2 border-dashed border-emerald-300 rounded-2xl p-8 text-center cursor-pointer hover:bg-emerald-50 transition">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-emerald-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-emerald-600 font-semibold">Ambil atau Unggah Foto</p>
                                        <p class="text-slate-500 text-sm mt-1">Ukuran maks: 5MB, format: JPEG/PNG</p>
                                    </div>
                                    <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" required>
                                </div>
                                <div id="previewContainer" class="mt-4 hidden">
                                    <img id="fotoPreview" src="" alt="Preview" class="w-full rounded-2xl max-h-64 object-cover">
                                    <button type="button" onclick="removeFoto()" class="mt-3 w-full rounded-lg bg-red-100 text-red-700 py-2 font-semibold hover:bg-red-200">Hapus Foto</button>
                                </div>
                                @error('foto')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lokasi & Deskripsi -->
                            <div>
                                <label class="block text-lg font-bold text-slate-700 mb-2">Lokasi & Deskripsi</label>
                                <p class="text-slate-500 text-sm mb-3">Tentukan lokasi di peta di samping atau masukkan koordinat manual</p>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-600 mb-1">Nama Lokasi</label>
                                        <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Jalan Merdeka, Blok B" 
                                            class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none" required>
                                        @error('lokasi')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-600 mb-1">Latitude</label>
                                            <input type="number" id="latitude" name="latitude" step="0.000001" value="{{ old('latitude', '-6.2088') }}" 
                                                class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none" required>
                                            @error('latitude')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-600 mb-1">Longitude</label>
                                            <input type="number" id="longitude" name="longitude" step="0.000001" value="{{ old('longitude', '106.8456') }}" 
                                                class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none" required>
                                            @error('longitude')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-600 mb-1">Deskripsi Sampah Liar</label>
                                        <textarea name="deskripsi" rows="4" placeholder="Deskripsikan kondisi sampah liar, jenis sampah yang terlihat, dampak lingkungan, dll..." 
                                            class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none" required>{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-600 mb-1">Estimasi Jumlah Sampah (dalam satuan ember/karung)</label>
                                        <input type="number" name="jumlah_estimasi" min="1" value="{{ old('jumlah_estimasi', 1) }}" 
                                            class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none" required>
                                        @error('jumlah_estimasi')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-6 py-4 text-xl font-bold text-white hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                                Kirim Laporan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Peta -->
                <div>
                    <label class="block text-lg font-bold text-slate-700 mb-4">Lokasi di Peta</label>
                    <div id="map" class="rounded-[2rem] overflow-hidden shadow-sm" style="height: 600px;"></div>
                    <p class="text-slate-500 text-sm mt-3">Klik pada peta untuk menentukan lokasi atau drag marker untuk menggeser posisi</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Initialize map
        const map = L.map('map').setView([-6.2088, 106.8456], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        let marker;

        function updateMarker(lat, lng) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng], { draggable: true })
                .addTo(map)
                .on('dragend', function() {
                    const pos = this.getLatLng();
                    document.getElementById('latitude').value = pos.lat.toFixed(6);
                    document.getElementById('longitude').value = pos.lng.toFixed(6);
                });
            map.setView([lat, lng], 15);
        }

        // Initialize marker
        const initialLat = parseFloat(document.getElementById('latitude').value);
        const initialLng = parseFloat(document.getElementById('longitude').value);
        updateMarker(initialLat, initialLng);

        // Click on map to set location
        map.on('click', function(e) {
            document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
            updateMarker(e.latlng.lat, e.latlng.lng);
        });

        // Update marker when input changes
        document.getElementById('latitude').addEventListener('change', function() {
            const lat = parseFloat(this.value);
            const lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                updateMarker(lat, lng);
            }
        });

        document.getElementById('longitude').addEventListener('change', function() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(this.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                updateMarker(lat, lng);
            }
        });

        // File upload
        const dropZone = document.getElementById('dropZone');
        const fotoInput = document.getElementById('fotoInput');
        const previewContainer = document.getElementById('previewContainer');
        const fotoPreview = document.getElementById('fotoPreview');

        dropZone.addEventListener('click', () => fotoInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('bg-emerald-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('bg-emerald-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('bg-emerald-50');
            if (e.dataTransfer.files.length) {
                fotoInput.files = e.dataTransfer.files;
                handleFileSelect();
            }
        });

        fotoInput.addEventListener('change', handleFileSelect);

        function handleFileSelect() {
            const file = fotoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    fotoPreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    dropZone.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeFoto() {
            fotoInput.value = '';
            previewContainer.classList.add('hidden');
            dropZone.classList.remove('hidden');
        }

        // Check if there's an old file input (after form validation fails)
        if (fotoInput.files.length > 0) {
            handleFileSelect();
        }
    </script>
</body>
</html>
