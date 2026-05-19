<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Penyelesaian Tugas - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

    {{-- HEADER --}}
    <header class="bg-slate-950 text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 py-8 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">SiResik · Petugas</p>
                <h1 class="mt-2 text-4xl font-black">Bukti Penyelesaian Tugas</h1>
                <p class="mt-2 text-sm text-slate-300">Unggah foto sebagai bukti bahwa penjemputan telah diselesaikan.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('petugas.riwayat') }}" class="rounded-full border border-white/20 px-5 py-3 text-sm font-bold text-white hover:bg-white/10 transition">← Riwayat Tugas</a>
                <a href="{{ route('dashboard.petugas') }}" class="rounded-full border border-white/20 px-5 py-3 text-sm font-bold text-white hover:bg-white/10 transition">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-full border border-white/20 px-5 py-3 text-sm font-bold text-white hover:bg-white/10 transition">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-10 space-y-8">

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 flex items-start gap-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-bold text-emerald-800">Berhasil!</p>
                    <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4">
                <p class="font-bold text-rose-800">Gagal mengunggah bukti.</p>
                <ul class="mt-2 list-disc pl-5 text-sm text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[1fr,1.1fr]">

            {{-- DETAIL PERMINTAAN --}}
            <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <div class="bg-slate-900 px-7 py-6">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-300">Detail Permintaan</p>
                    <p class="mt-2 text-2xl font-black text-white">#{{ $permintaan->id }}</p>
                </div>

                <div class="px-7 py-6 space-y-5">
                    {{-- Status Badge --}}
                    @php
                        $statusClass = match($permintaan->status) {
                            'Selesai'  => 'bg-emerald-100 text-emerald-800',
                            'Diproses' => 'bg-amber-100 text-amber-800',
                            default    => 'bg-sky-100 text-sky-800',
                        };
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $permintaan->status }}</span>
                        @if($permintaan->status === 'Selesai')
                            <span class="text-xs text-slate-400">Diselesaikan {{ $permintaan->diselesaikan_at?->diffForHumans() }}</span>
                        @endif
                    </div>

                    {{-- Info Pengguna --}}
                    <div class="rounded-2xl bg-slate-50 p-4 space-y-1">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Warga</p>
                        <p class="font-bold text-slate-900">{{ $permintaan->pengguna?->name ?? '-' }}</p>
                        <p class="text-sm text-slate-500">{{ $permintaan->pengguna?->email ?? '-' }}</p>
                        <p class="text-sm text-slate-600 pt-1">📍 {{ $permintaan->alamat }}</p>
                        <p class="text-sm text-slate-600">📞 {{ $permintaan->nomor_telepon }}</p>
                    </div>

                    {{-- Jadwal --}}
                    <div class="rounded-2xl bg-amber-50 p-4 space-y-1">
                        <p class="text-xs font-black uppercase tracking-widest text-amber-600">Jadwal Penjemputan</p>
                        <p class="font-bold text-slate-900">{{ $permintaan->jadwal }}</p>
                        <p class="text-sm text-slate-600">Tanggal: {{ $permintaan->tanggal }}</p>
                        @if($permintaan->scheduled_at)
                            <p class="text-sm text-slate-600">Dijadwalkan: {{ $permintaan->scheduled_at->translatedFormat('d M Y, H:i') }}</p>
                        @endif
                    </div>

                    {{-- Item Sampah --}}
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Item Sampah</p>
                        <div class="space-y-2">
                            @forelse ($permintaan->items as $item)
                                <div class="flex justify-between items-center rounded-xl bg-slate-50 px-4 py-3">
                                    <div>
                                        <p class="text-sm font-semibold">{{ $item->kategoriSampah?->nama ?? '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ $item->berat_kg }} kg</p>
                                    </div>
                                    <span class="text-sm font-bold text-emerald-600">+{{ number_format($item->estimasi_poin) }} poin</span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-400">Tidak ada item.</p>
                            @endforelse
                        </div>
                        <div class="mt-3 flex justify-between rounded-2xl bg-emerald-900 px-4 py-3 text-white">
                            <span class="font-bold">Total Estimasi Poin</span>
                            <span class="font-black text-emerald-300">{{ number_format($permintaan->total_estimasi_poin) }} poin</span>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    @if($permintaan->catatan && $permintaan->catatan !== '-')
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Catatan Warga</p>
                            <p class="text-sm text-slate-700">{{ $permintaan->catatan }}</p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- FORM UPLOAD BUKTI --}}
            <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <div class="bg-gradient-to-br from-amber-400 to-amber-500 px-7 py-6">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-900">PBI 6</p>
                    <p class="mt-2 text-2xl font-black text-slate-900">Unggah Bukti Penyelesaian</p>
                    <p class="mt-1 text-sm text-amber-900">Upload foto bukti sampah telah dijemput & tugas selesai.</p>
                </div>

                <div class="px-7 py-6">

                    {{-- Jika sudah ada bukti sebelumnya --}}
                    @if($permintaan->bukti_penyelesaian)
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-emerald-600 mb-3">Bukti yang sudah diunggah</p>
                            <img src="{{ Storage::url($permintaan->bukti_penyelesaian) }}"
                                 alt="Bukti Penyelesaian"
                                 class="w-full rounded-xl object-cover max-h-48">
                            @if($permintaan->catatan_penyelesaian)
                                <p class="mt-3 text-sm text-slate-600"><span class="font-semibold">Catatan:</span> {{ $permintaan->catatan_penyelesaian }}</p>
                            @endif
                            @if($permintaan->diselesaikan_at)
                                <p class="mt-1 text-xs text-slate-400">Diunggah: {{ $permintaan->diselesaikan_at->translatedFormat('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    @endif

                    @if($permintaan->status !== 'Selesai')
                        {{-- FORM UPLOAD --}}
                        <form action="{{ route('petugas.bukti.upload', $permintaan) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="space-y-5"
                              id="formUpload">
                            @csrf

                            {{-- Foto Bukti --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Foto Bukti Penyelesaian <span class="text-rose-500">*</span>
                                </label>
                                <div id="dropzone"
                                     class="relative rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-50 transition"
                                     onclick="document.getElementById('bukti_foto').click()">
                                    <div id="dropzone-placeholder">
                                        <div class="text-4xl mb-2">📷</div>
                                        <p class="text-sm font-semibold text-slate-600">Klik atau seret foto ke sini</p>
                                        <p class="text-xs text-slate-400 mt-1">JPEG, PNG, WEBP · Maks 5MB</p>
                                    </div>
                                    <img id="preview-img" src="" alt="Preview" class="hidden w-full rounded-xl object-cover max-h-48 mx-auto">
                                </div>
                                <input type="file"
                                       id="bukti_foto"
                                       name="bukti_foto"
                                       accept="image/jpeg,image/jpg,image/png,image/webp"
                                       class="hidden"
                                       required>
                                @error('bukti_foto')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Catatan Penyelesaian --}}
                            <div>
                                <label for="catatan_penyelesaian" class="block text-sm font-bold text-slate-700 mb-2">
                                    Catatan Penyelesaian <span class="text-slate-400 font-normal">(opsional)</span>
                                </label>
                                <textarea id="catatan_penyelesaian"
                                          name="catatan_penyelesaian"
                                          rows="3"
                                          maxlength="500"
                                          placeholder="Contoh: Sampah berhasil dijemput, warga kooperatif..."
                                          class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-200 transition resize-none">{{ old('catatan_penyelesaian') }}</textarea>
                                @error('catatan_penyelesaian')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Konfirmasi --}}
                            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 flex gap-3">
                                <input type="checkbox" id="konfirmasi" class="mt-0.5 accent-amber-500" required>
                                <label for="konfirmasi" class="text-sm text-amber-900">
                                    Saya konfirmasi bahwa penjemputan sampah telah <strong>benar-benar dilaksanakan</strong> dan foto yang diunggah adalah bukti nyata.
                                </label>
                            </div>

                            {{-- Tombol Submit --}}
                            <button type="submit"
                                    id="btnSubmit"
                                    class="w-full rounded-2xl bg-amber-400 px-6 py-4 text-base font-black text-slate-900 hover:bg-amber-500 transition shadow-lg shadow-amber-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                ✅ Tandai Selesai & Kirim Bukti
                            </button>
                        </form>
                    @else
                        {{-- Tugas sudah selesai --}}
                        <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-6 text-center">
                            <div class="text-5xl mb-3">🎉</div>
                            <p class="font-black text-emerald-800 text-lg">Tugas Telah Selesai!</p>
                            <p class="text-sm text-emerald-600 mt-1">Bukti penyelesaian sudah tercatat dalam sistem.</p>
                            @if($permintaan->diselesaikan_at)
                                <p class="text-xs text-slate-400 mt-3">{{ $permintaan->diselesaikan_at->translatedFormat('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </main>

    <script>
        // Preview gambar sebelum upload
        const inputFoto = document.getElementById('bukti_foto');
        const previewImg = document.getElementById('preview-img');
        const placeholder = document.getElementById('dropzone-placeholder');

        inputFoto?.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });

        // Drag & Drop
        const dropzone = document.getElementById('dropzone');
        dropzone?.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.classList.add('border-amber-400', 'bg-amber-50');
        });
        dropzone?.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-amber-400', 'bg-amber-50');
        });
        dropzone?.addEventListener('drop', e => {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                inputFoto.files = dt.files;
                inputFoto.dispatchEvent(new Event('change'));
            }
        });
    </script>

</body>
</html>
