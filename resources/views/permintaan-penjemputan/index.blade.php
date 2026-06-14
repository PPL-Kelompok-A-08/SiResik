<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Penjemputan - SiResik</title>
    <meta name="description" content="Ajukan permintaan penjemputan sampah Anda melalui SiResik.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .kategori-card.selected { border-color: #10b981; background-color: #f0fdf4; }
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
            <h1 class="text-xl font-black tracking-tight text-slate-800">Ajukan Penjemputan</h1>
            <div class="flex gap-3">
                <button type="button"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Unduh Laporan
                </button>
                <a href="#form-penjemputan"
                   class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-600">
                    + Ajukan Penjemputan
                </a>
            </div>
        </header>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                <p class="font-bold">Pengajuan belum bisa diproses:</p>
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($canCreate)
        {{-- ═══ Form Card ═══ --}}
        <section id="form-penjemputan"
                 class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

            {{-- Green Header --}}
            <div class="relative overflow-hidden bg-emerald-500 px-7 py-6">
                {{-- Watermark trash icon --}}
                <div class="pointer-events-none absolute -right-4 -top-4 text-[9rem] leading-none text-white/10 select-none">🗑</div>
                <h2 class="text-2xl font-black text-white">Ajukan Penjemputan</h2>
                <p class="mt-1 text-sm text-emerald-50">Lengkapi data penjemputan agar petugas kami dapat segera meluncur.</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('permintaan-penjemputan.store') }}" method="POST"
                  class="grid gap-0 lg:grid-cols-[1fr,300px]">
                @csrf

                {{-- Left: Form Fields --}}
                <div class="divide-y divide-slate-100 px-7 py-6 space-y-6">

                    {{-- Seksi 1: Lokasi & Kontak --}}
                    <section>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-emerald-100 text-xs font-black text-emerald-700">📍</span>
                            <p class="text-[11px] font-black uppercase tracking-widest text-slate-500">1. Data Lokasi &amp; Kontak</p>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @if ($isAdmin)
                                <label class="block sm:col-span-2">
                                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Pengguna</span>
                                    <select name="pengguna_id"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                        <option value="">Pilih pengguna</option>
                                        @foreach ($users as $selectedUser)
                                            <option value="{{ $selectedUser->id }}" @selected(old('pengguna_id') == $selectedUser->id)>
                                                {{ $selectedUser->name }} - {{ $selectedUser->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                            @endif

                            <label class="block {{ $isAdmin ? '' : 'sm:col-span-2' }}">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Alamat Pick Up</span>
                                <input type="text" name="alamat" value="{{ old('alamat') }}"
                                       placeholder="Jl. Merpati No. 12, Sukamaju" maxlength="45"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white" required>
                            </label>

                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nomor Telepon Aktif</span>
                                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                                       placeholder="081234567890" maxlength="20"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white" required>
                            </label>

                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal Penjemputan</span>
                                <input type="date" name="tanggal" value="{{ old('tanggal') }}"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white" required>
                            </label>

                            <label class="block">
                                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Jadwal</span>
                                <input type="text" name="jadwal" value="{{ old('jadwal') }}"
                                       placeholder="08.00 - 10.00 WIB" maxlength="45"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white" required>
                            </label>
                        </div>
                    </section>

                    {{-- Seksi 2: Pilih Kategori --}}
                    <section class="pt-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-emerald-100 text-xs font-black text-emerald-700">☑</span>
                            <p class="text-[11px] font-black uppercase tracking-widest text-slate-500">2. Pilih Kategori Sampah</p>
                        </div>
                        <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 xl:grid-cols-4">
                            @foreach ($kategori as $item)
                                @php
                                    $oldSelected = collect(old('selected_categories', []))->contains($item->id);
                                    $oldBerat = old("berat.$item->id", 1);
                                @endphp
                                <div class="kategori-card {{ $oldSelected ? 'selected' : '' }} cursor-pointer rounded-xl border-2 border-slate-200 bg-white p-4 transition hover:border-emerald-300 hover:shadow-sm"
                                     data-card
                                     data-id="{{ $item->id }}"
                                     data-name="{{ $item->nama }}"
                                     data-poin="{{ $item->poin_per_kg }}"
                                     data-harga="{{ $item->harga_per_kg }}">
                                    <input type="checkbox" name="selected_categories[]" value="{{ $item->id }}"
                                           class="sr-only category-checkbox" @checked($oldSelected)>

                                    <div class="flex flex-col items-center gap-2 text-center">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-xl text-emerald-600">🗑</div>
                                        <p class="text-sm font-bold text-slate-800">{{ $item->nama }}</p>
                                        <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                                            {{ number_format($item->poin_per_kg) }} poin/kg
                                        </span>
                                    </div>

                                    <div class="mt-3">
                                        <label class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Berat (kg)</label>
                                        <input type="number" min="0" step="0.5"
                                               name="berat[{{ $item->id }}]"
                                               value="{{ $oldBerat }}"
                                               class="category-weight w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-center text-sm outline-none transition focus:border-emerald-400 focus:bg-white">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Seksi 3: Catatan --}}
                    <section class="pt-6">
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-500">Catatan Tambahan (opsional)</span>
                            <textarea name="catatan" rows="3" maxlength="255"
                                      placeholder="Sampah sudah dipilah per karung..."
                                      class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-emerald-400 focus:bg-white">{{ old('catatan') }}</textarea>
                        </label>
                    </section>
                </div>

                {{-- Right: Ringkasan --}}
                <aside class="border-l border-slate-100 bg-slate-50 px-5 py-6 lg:sticky lg:top-0 lg:self-start">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-sm font-black text-slate-800">Ringkasan Penjemputan</h3>
                        <span id="selected-count"
                              class="rounded-full bg-slate-200 px-2.5 py-0.5 text-[10px] font-black text-slate-600">
                            0 Item
                        </span>
                    </div>

                    <div id="summary-list" class="mt-4 space-y-2 min-h-[80px]">
                        <div id="summary-empty" class="rounded-xl border border-dashed border-slate-300 p-4 text-center text-xs text-slate-400">
                            Daftar item kosong
                        </div>
                    </div>

                    <div class="mt-5 border-t border-slate-200 pt-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Estimasi Poin</p>
                        <p id="total-points" class="mt-2 text-right text-3xl font-black text-emerald-500">0</p>
                    </div>

                    <button type="submit"
                            class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-black text-white shadow-md shadow-emerald-500/30 transition hover:bg-emerald-600">
                        <span>→</span> Ajukan Penjemputan
                    </button>

                    <p class="mt-3 text-center text-[10px] leading-5 text-slate-400">
                        Dengan klik tombol di atas, Anda menyetujui syarat dan ketentuan layanan penjemputan sampah SiResik.
                    </p>
                </aside>
            </form>
        </section>
        @else
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 font-medium">
                Akun petugas tidak dapat membuat permintaan baru.
            </div>
        @endif

    </main>
</div>

{{-- ═══ Script Interaktif ═══ --}}
<script>
    const cards       = document.querySelectorAll('[data-card]');
    const summaryList = document.getElementById('summary-list');
    const summaryEmpty= document.getElementById('summary-empty');
    const totalPoints = document.getElementById('total-points');
    const selectedCount = document.getElementById('selected-count');

    function fmt(n) { return new Intl.NumberFormat('id-ID').format(n); }

    function renderSummary() {
        let total = 0, count = 0;
        const items = [];

        cards.forEach(card => {
            const cb     = card.querySelector('.category-checkbox');
            const wi     = card.querySelector('.category-weight');
            const active = cb.checked;
            const weight = parseFloat(wi.value || 0);
            const poinPkg = parseInt(card.dataset.poin, 10);

            card.classList.toggle('selected', active);

            if (active && weight > 0) {
                const sub = Math.round(weight * poinPkg);
                total += sub; count++;
                items.push(`
                    <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                        <div>
                            <p class="text-xs font-bold text-slate-700">${card.dataset.name}</p>
                            <p class="text-[10px] text-slate-400">${weight} kg</p>
                        </div>
                        <p class="text-xs font-black text-emerald-600">+${fmt(sub)} Pts</p>
                    </div>`);
            }
        });

        summaryList.innerHTML = items.length
            ? items.join('')
            : '<div id="summary-empty" class="rounded-xl border border-dashed border-slate-300 p-4 text-center text-xs text-slate-400">Daftar item kosong</div>';

        selectedCount.textContent = `${count} Item`;
        totalPoints.textContent   = fmt(total);
    }

    cards.forEach(card => {
        const cb = card.querySelector('.category-checkbox');
        const wi = card.querySelector('.category-weight');

        card.addEventListener('click', e => {
            if (e.target === wi) return;
            cb.checked = !cb.checked;
            renderSummary();
        });
        wi.addEventListener('click', e => e.stopPropagation());
        wi.addEventListener('input', renderSummary);
        cb.addEventListener('change', renderSummary);
    });

    renderSummary();
</script>
</body>
</html>
