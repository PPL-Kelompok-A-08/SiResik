<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Berhasil - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">SiResik</p>
                <h1 class="mt-2 text-3xl font-black">Ajukan Penjemputan</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700">Logout</button>
            </form>
        </div>
    </header>

    <main class="mx-auto flex max-w-7xl justify-center px-6 py-14">
        <section class="w-full max-w-xl rounded-[2rem] bg-white p-10 text-center shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-5xl text-emerald-700">✓</div>
            <h2 class="mt-8 text-5xl font-black tracking-tight text-slate-900">Pengajuan Berhasil!</h2>
            <p class="mt-4 text-xl leading-9 text-slate-500">
                Permintaan penjemputan Anda telah tersimpan dengan status
                <span class="font-black text-emerald-500">"{{ $permintaan->status }}"</span>.
            </p>

            <div class="mt-8 rounded-3xl bg-slate-50 p-6 text-left ring-1 ring-slate-200">
                <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400">Detail Penjemputan</p>
                <div class="mt-4 space-y-3 text-sm text-slate-700">
                    <p>{{ $permintaan->alamat }}</p>
                    <p>{{ $permintaan->nomor_telepon }}</p>
                    <p>{{ $permintaan->items->count() }} Kategori Sampah</p>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ($permintaan->items as $item)
                        <div class="rounded-2xl bg-white px-4 py-3 ring-1 ring-slate-200">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $item->kategoriSampah?->nama }}</p>
                                    <p class="text-sm text-slate-500">{{ rtrim(rtrim(number_format($item->berat_kg, 2, '.', ''), '0'), '.') }} kg</p>
                                </div>
                                <p class="font-bold text-emerald-600">{{ number_format($item->estimasi_poin) }} poin</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 border-t border-slate-200 pt-5 text-right">
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400">Total Estimasi Poin</p>
                    <p class="mt-2 text-4xl font-black text-emerald-500">{{ number_format($permintaan->total_estimasi_poin) }}</p>
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <a href="{{ route('permintaan-penjemputan.index') }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-6 py-4 text-xl font-bold text-white">Buat Pengajuan Baru</a>
                <a href="{{ route('dashboard') }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 text-lg font-semibold text-slate-700">Kembali ke Dashboard</a>
            </div>
        </section>
    </main>
</body>
</html>
