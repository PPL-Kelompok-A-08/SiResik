<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Sampah Liar - SiResik</title>
    <meta name="description" content="Laporkan titik pumpukan sampah liar di sekitar Anda melalui SiResik.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        #dropZone.drag-over { background: #f0fdf4; border-color: #10b981; }
    </style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">

    {{-- ═══════════════════════ SIDEBAR ═══════════════════════ --}}
    {{-- Sidebar Konsisten --}}
    <x-sidebar />

    {{-- ═══════════════════════ MAIN ═══════════════════════ --}}
    <main class="flex flex-col gap-5 px-7 py-6">

        {{-- Top Bar --}}
        <header class="flex items-center justify-between">
            <h1 class="text-xl font-black tracking-tight text-slate-800">Lapor Sampah Liar</h1>
            <button type="button"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Unduh Laporan
            </button>
        </header>

        {{-- Flash Success --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- ═══ 2-column layout ═══ --}}
        <div class="grid gap-5 lg:grid-cols-[1fr,320px]">

            {{-- ── Form Card ── --}}
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-7">
                <h2 class="text-lg font-black text-slate-800">Lapor Sampah Liar</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Bantu kami menjaga kebersihan lingkungan dengan melaporkan titik pumpukan sampah liar di sekitar Anda.
                </p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <p class="font-bold">Laporan belum bisa dikirim:</p>
                        <ul class="mt-1 list-disc pl-5 space-y-0.5">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sampah-liar.store') }}" method="POST"
                      enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf

                    {{-- Foto Bukti --}}
                    <div>
                        <p class="mb-2 text-sm font-bold text-slate-700">Foto Bukti</p>
                        <div id="dropZone"
                             class="relative flex min-h-[200px] cursor-pointer flex-col items-center justify-center gap-3
                                    rounded-2xl border-2 border-dashed border-emerald-300 bg-slate-50 text-center transition hover:bg-emerald-50">
                            <div id="dropContent">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full border-2 border-emerald-300 text-2xl text-emerald-500 mx-auto">📷</div>
                                <p class="mt-2 text-sm font-semibold text-emerald-600">Ambil atau Unggah Foto</p>
                                <p class="text-xs text-slate-400">Ukuran maks 5MB, format JPG/PNG</p>
                            </div>
                            <div id="previewContainer" class="hidden w-full px-4 pb-4">
                                <img id="fotoPreview" src="" alt="Preview"
                                     class="mx-auto max-h-48 rounded-xl object-cover w-full">
                                <button type="button" id="removePhoto"
                                        class="mt-3 w-full rounded-xl bg-rose-100 py-2 text-sm font-bold text-rose-700 hover:bg-rose-200">
                                    Hapus Foto
                                </button>
                            </div>
                            <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" required>
                        </div>
                        @error('foto')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Divider --}}
                    <div class="relative">
                        <div class="absolute inset-y-1/2 inset-x-0 border-t border-slate-100"></div>
                        <span class="relative bg-white px-3 text-xs font-bold uppercase tracking-widest text-slate-400">
                            Lokasi &amp; Deskripsi
                        </span>
                    </div>

                    {{-- Lokasi --}}
                    <label class="block">
                        <span class="sr-only">Lokasi</span>
                        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 focus-within:border-emerald-400 focus-within:bg-white transition">
                            <span class="text-slate-400">📍</span>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                                   placeholder="Gunakan lokasi saat ini atau masukkan alamat..."
                                   class="flex-1 bg-transparent text-sm outline-none placeholder:text-slate-400" required>
                        </div>
                        @error('lokasi')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </label>

                    {{-- Koordinat (hidden, tersembunyi tapi tetap tersedia) --}}
                    <div class="grid grid-cols-2 gap-3">
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-400">Latitude</span>
                            <input type="number" id="latitude" name="latitude" step="0.000001"
                                   value="{{ old('latitude', '-6.9175') }}"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-emerald-400 focus:bg-white transition" required>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-400">Longitude</span>
                            <input type="number" id="longitude" name="longitude" step="0.000001"
                                   value="{{ old('longitude', '107.6191') }}"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-emerald-400 focus:bg-white transition" required>
                        </label>
                    </div>

                    {{-- Deskripsi --}}
                    <label class="block">
                        <textarea name="deskripsi" rows="4"
                                  placeholder="Ceritakan kondisi tumpukan sampah tersebut..."
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-emerald-400 focus:bg-white transition placeholder:text-slate-400" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </label>

                    {{-- Estimasi --}}
                    <label class="block">
                        <span class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-400">Estimasi Jumlah (ember/karung)</span>
                        <input type="number" name="jumlah_estimasi" min="1"
                               value="{{ old('jumlah_estimasi', 1) }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-emerald-400 focus:bg-white transition" required>
                        @error('jumlah_estimasi')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </label>

                    {{-- Submit --}}
                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 py-3.5 text-sm font-black text-white shadow-md shadow-emerald-500/30 transition hover:bg-emerald-600">
                        Kirim Laporan
                    </button>
                </form>
            </div>

            {{-- ── Panel Kanan: Laporan di Sekitar Anda ── --}}
            <aside class="flex flex-col gap-4">
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 p-5">
                    <h3 class="text-sm font-black text-slate-800">Laporan di Sekitar Anda</h3>

                    <div class="mt-4 space-y-3">
                        @forelse ($nearbyReports as $report)
                            <a href="{{ route('sampah-liar.show', $report) }}"
                               class="group block overflow-hidden rounded-xl border border-slate-200 transition hover:shadow-md">
                                {{-- Thumbnail --}}
                                <div class="relative aspect-video overflow-hidden bg-slate-200">
                                    @if ($report->foto)
                                        <img src="{{ asset('storage/' . $report->foto) }}"
                                             alt="{{ $report->lokasi }}"
                                             class="h-full w-full object-cover transition group-hover:scale-105">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center">
                                            <span class="text-4xl">📷</span>
                                        </div>
                                    @endif
                                    {{-- Status badge --}}
                                    @php
                                        $badgeColor = match($report->status) {
                                            'pending'      => 'bg-amber-500',
                                            'diverifikasi' => 'bg-blue-500',
                                            'selesai'      => 'bg-emerald-500',
                                            default        => 'bg-slate-500',
                                        };
                                        $badgeLabel = match($report->status) {
                                            'pending'      => 'PERLU VERIFIKASI',
                                            'diverifikasi' => 'DIVERIFIKASI',
                                            'selesai'      => 'SELESAI',
                                            default        => strtoupper($report->status),
                                        };
                                    @endphp
                                    <span class="absolute left-2 top-2 rounded-full {{ $badgeColor }} px-2 py-0.5 text-[9px] font-black text-white">
                                        {{ $badgeLabel }}
                                    </span>
                                </div>

                                {{-- Info --}}
                                <div class="px-3 py-2.5">
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-emerald-700 transition line-clamp-1">
                                        {{ $report->lokasi }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-500 line-clamp-2">
                                        {{ $report->deskripsi }}
                                    </p>
                                    <p class="mt-1.5 text-[10px] text-slate-400">
                                        ⏱ {{ $report->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center py-10 text-center text-slate-400">
                                <span class="text-3xl mb-2">🌍</span>
                                <p class="text-xs font-semibold">Belum ada laporan dari warga lain</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Link ke semua laporan saya --}}
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <p class="mb-2 text-[11px] font-black uppercase tracking-widest text-slate-400">Laporan Saya</p>
                        @forelse ($myReports as $report)
                            <a href="{{ route('sampah-liar.show', $report) }}"
                               class="flex items-center justify-between rounded-xl px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                <span class="line-clamp-1 flex-1">{{ $report->lokasi }}</span>
                                <span class="ml-2 shrink-0 rounded-full px-2 py-0.5 text-[9px] font-black
                                    {{ $report->status === 'pending' ? 'bg-amber-100 text-amber-700'
                                        : ($report->status === 'diverifikasi' ? 'bg-blue-100 text-blue-700'
                                        : 'bg-emerald-100 text-emerald-700') }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">Belum ada laporan</p>
                        @endforelse
                    </div>
                </div>
            </aside>

        </div>
    </main>
</div>

{{-- ═══ Scripts ═══ --}}
<script>
    const dropZone        = document.getElementById('dropZone');
    const dropContent     = document.getElementById('dropContent');
    const fotoInput       = document.getElementById('fotoInput');
    const previewContainer= document.getElementById('previewContainer');
    const fotoPreview     = document.getElementById('fotoPreview');
    const removePhoto     = document.getElementById('removePhoto');

    dropZone.addEventListener('click', (e) => {
        if (e.target === removePhoto || removePhoto.contains(e.target)) return;
        fotoInput.click();
    });

    ['dragover', 'dragenter'].forEach(ev =>
        dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('drag-over'); })
    );
    ['dragleave', 'drop'].forEach(ev =>
        dropZone.addEventListener(ev, e => { dropZone.classList.remove('drag-over'); })
    );
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        if (e.dataTransfer.files.length) {
            fotoInput.files = e.dataTransfer.files;
            showPreview();
        }
    });

    fotoInput.addEventListener('change', showPreview);

    function showPreview() {
        const file = fotoInput.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            fotoPreview.src = e.target.result;
            dropContent.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    removePhoto.addEventListener('click', (e) => {
        e.stopPropagation();
        fotoInput.value = '';
        previewContainer.classList.add('hidden');
        dropContent.classList.remove('hidden');
    });
</script>
</body>
</html>
