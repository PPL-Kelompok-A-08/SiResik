<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unggah Bukti Penanganan - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="max-w-3xl mx-auto p-6">
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-emerald-800">{{ session('success') }}</div>
        @endif

        <a href="{{ route('dashboard.petugas') }}" class="inline-block mb-4 text-emerald-600">← Kembali</a>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-bold mb-2">Unggah Bukti Penanganan</h1>
            <p class="text-sm text-slate-600 mb-4">Laporan: <strong>{{ $laporan->lokasi }}</strong> — Dilaporkan oleh {{ $laporan->pengguna->name ?? '—' }}</p>

            <div class="mb-4">
                <p class="text-sm font-semibold">Status saat ini: <span class="font-bold">{{ ucfirst($laporan->status) }}</span></p>
                @if($laporan->petugas)
                    <p class="text-sm">Ditugaskan ke: {{ $laporan->petugas->name }}</p>
                @endif
            </div>

            <form action="{{ route('petugas.bukti.sampah_liar.upload', $laporan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold mb-1">Foto Bukti Penanganan (jpeg/png/webp, max 5MB)</label>
                    <input type="file" name="bukti_foto" required accept="image/*">
                    @error('bukti_foto') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Catatan (opsional)</label>
                    <textarea name="catatan_petugas" rows="4" class="w-full rounded-lg border px-3 py-2">{{ old('catatan_petugas') }}</textarea>
                    @error('catatan_petugas') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded-2xl bg-emerald-500 px-4 py-2 text-white font-bold">Unggah & Tandai Selesai</button>
                    <a href="{{ route('dashboard.petugas') }}" class="rounded-2xl border px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
