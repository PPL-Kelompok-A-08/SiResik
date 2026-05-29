<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Penjemputan - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-6 py-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">SiResik</p>
                <h1 class="mt-2 text-4xl font-black">Ajukan Penjemputan</h1>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700">Dashboard</a>
                <a href="#form-penjemputan" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">+ Ajukan Penjemputan</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-emerald-900 p-5 text-white"><p class="text-sm text-emerald-100">Total</p><p class="mt-3 text-4xl font-black">{{ $statusCounts['total'] }}</p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Menunggu</p><p class="mt-3 text-4xl font-black text-sky-600">{{ $statusCounts['menunggu'] }}</p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Diproses</p><p class="mt-3 text-4xl font-black text-amber-600">{{ $statusCounts['diproses'] }}</p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Selesai</p><p class="mt-3 text-4xl font-black text-emerald-600">{{ $statusCounts['selesai'] }}</p></div>
        </section>

        @if ($errors->any())
            <div class="mt-8 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                <p class="font-semibold">Pengajuan belum bisa diproses.</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section id="form-penjemputan" class="mt-8 rounded-[2rem] bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
            <div class="rounded-t-[2rem] bg-gradient-to-r from-emerald-500 to-emerald-400 px-8 py-8 text-white">
                <h2 class="text-5xl font-black tracking-tight">Ajukan Penjemputan</h2>
                <p class="mt-3 max-w-2xl text-lg text-emerald-50">Lengkapi data penjemputan agar petugas kami dapat segera meluncur.</p>
            </div>

            @if ($canCreate)
                <form action="{{ route('permintaan-penjemputan.store') }}" method="POST" class="grid gap-8 px-8 py-8 lg:grid-cols-[1.2fr,0.75fr]">
                    @csrf

                    <div class="space-y-8">
                        <section>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">1. Data Lokasi & Kontak</p>
                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                @if ($isAdmin)
                                    <label class="block md:col-span-2">
                                        <span class="mb-2 block text-sm font-semibold text-slate-700">Pengguna</span>
                                        <select name="pengguna_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                            <option value="">Pilih pengguna</option>
                                            @foreach ($users as $selectedUser)
                                                <option value="{{ $selectedUser->id }}" @selected(old('pengguna_id') == $selectedUser->id)>{{ $selectedUser->name }} - {{ $selectedUser->email }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                @else
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pengguna</p>
                                        <p class="mt-2 font-semibold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                    </div>
                                @endif

                                <label class="block {{ $isAdmin ? '' : 'md:col-span-2' }}">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Alamat penjemputan</span>
                                    <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Jl. Merpati No. 12, Sukamaju" maxlength="45" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Nomor telepon aktif</span>
                                    <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="081234567890" maxlength="20" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Tanggal penjemputan</span>
                                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Jadwal</span>
                                    <input type="text" name="jadwal" value="{{ old('jadwal') }}" placeholder="08.00 - 10.00 WIB" maxlength="45" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>
                            </div>
                        </section>

                        <section>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">2. Pilih Kategori Sampah</p>
                            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($kategori as $item)
                                    @php
                                        $oldSelected = collect(old('selected_categories', []))->contains($item->id);
                                        $oldBerat = old("berat.$item->id", 1);
                                    @endphp
                                    <div class="kategori-card group block cursor-pointer rounded-3xl border border-slate-200 bg-white p-5 transition hover:border-emerald-300 hover:shadow-lg"
                                        data-card
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->nama }}"
                                        data-poin="{{ $item->poin_per_kg }}"
                                        data-harga="{{ $item->harga_per_kg }}">
                                        <input type="checkbox" name="selected_categories[]" value="{{ $item->id }}" class="sr-only category-checkbox" @checked($oldSelected)>
                                        <div class="flex h-full flex-col">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="h-14 w-14 rounded-2xl bg-slate-100 text-2xl leading-[3.5rem] text-center text-emerald-600">♻</div>
                                                <div class="flex flex-col gap-2">
                                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">{{ number_format($item->poin_per_kg) }} poin/kg</span>
                                                    <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}/kg</span>
                                                </div>
                                            </div>
                                            <h3 class="mt-5 text-lg font-bold text-slate-900">{{ $item->nama }}</h3>
                                            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $item->deskripsi }}</p>

                                            <div class="mt-5">
                                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Berat (kg)</span>
                                                <input
                                                    type="number"
                                                    min="0"
                                                    step="0.5"
                                                    name="berat[{{ $item->id }}]"
                                                    value="{{ $oldBerat }}"
                                                    class="category-weight w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">3. Catatan Tambahan</p>
                            <label class="mt-4 block">
                                <textarea name="catatan" rows="4" maxlength="45" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" placeholder="Sampah sudah dipilah per karung">{{ old('catatan') }}</textarea>
                            </label>
                        </section>
                    </div>

                    <aside class="rounded-[2rem] bg-slate-50 p-6 ring-1 ring-slate-200 lg:sticky lg:top-6 lg:self-start">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-2xl font-bold text-slate-900">Ringkasan Penjemputan</h3>
                            <span id="selected-count" class="rounded-full bg-slate-200 px-3 py-1 text-xs font-bold text-slate-600">0 Item</span>
                        </div>

                        <div id="summary-list" class="mt-6 space-y-3">
                            <div id="summary-empty" class="rounded-3xl border border-dashed border-slate-300 p-5 text-sm text-slate-500">
                                Pilih kategori sampah untuk melihat ringkasannya di sini.
                            </div>
                        </div>

                        <div class="mt-6 border-t border-slate-200 pt-6">
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400">Total Estimasi Poin</p>
                            <p id="total-points" class="mt-3 text-right text-4xl font-black text-emerald-500">0</p>
                        </div>

                        <div class="mt-4 border-t border-slate-200 pt-4">
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400">Total Tagihan</p>
                            <p id="total-tagihan" class="mt-3 text-right text-4xl font-black text-rose-600">Rp 0</p>
                        </div>

                        <button type="submit" class="mt-8 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-6 py-4 text-lg font-bold text-white shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-600">
                            Ajukan Penjemputan
                        </button>

                        <p class="mt-5 text-center text-xs leading-6 text-slate-400">Dengan klik tombol di atas, Anda menyetujui syarat dan ketentuan layanan penjemputan sampah SiResik.</p>
                    </aside>
                </form>
            @else
                <div class="px-8 py-8">
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                        Akun petugas tidak dapat membuat permintaan baru. Gunakan halaman ini untuk memantau antrean penjemputan yang sudah masuk.
                    </div>
                </div>
            @endif
        </section>

        <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Riwayat Permintaan</p>
                    <h2 class="mt-3 text-2xl font-bold">Daftar pengajuan penjemputan</h2>
                </div>
                <p class="text-sm text-slate-500">Menampilkan semua permintaan yang sudah tersimpan.</p>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead>
                        <tr class="text-slate-500">
                            <th class="pb-3 pr-4 font-semibold">Pengguna</th>
                            <th class="pb-3 pr-4 font-semibold">Detail</th>
                            <th class="pb-3 pr-4 font-semibold">Kategori</th>
                            <th class="pb-3 pr-4 font-semibold">Poin</th>
                            <th class="pb-3 pr-4 font-semibold">Tagihan</th>
                            <th class="pb-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($permintaan as $item)
                            <tr class="align-top">
                                <td class="py-4 pr-4">
                                    <p class="font-semibold text-slate-900">{{ $item->pengguna?->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->pengguna?->email ?? '-' }}</p>
                                </td>
                                <td class="py-4 pr-4">
                                    <p>{{ $item->alamat }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->nomor_telepon }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->tanggal }} | {{ $item->jadwal }}</p>
                                </td>
                                <td class="py-4 pr-4">
                                    <div class="space-y-2">
                                        @foreach ($item->items as $detail)
                                            <div class="rounded-2xl bg-slate-50 px-3 py-2">
                                                <p class="font-semibold text-slate-700">{{ $detail->kategoriSampah?->nama }}</p>
                                                <p class="text-xs text-slate-500">{{ rtrim(rtrim(number_format($detail->berat_kg, 2, '.', ''), '0'), '.') }} kg</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-4 pr-4 font-bold text-emerald-600">{{ number_format($item->total_estimasi_poin) }}</td>
                                <td class="py-4 pr-4 font-bold text-rose-600">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                                <td class="py-4">
                                    @php
                                        $statusClass = match ($item->status) {
                                            'Diproses' => 'bg-amber-100 text-amber-700',
                                            'Selesai' => 'bg-emerald-100 text-emerald-700',
                                            default => 'bg-sky-100 text-sky-700',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-500">Belum ada permintaan penjemputan yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        const cards = document.querySelectorAll('[data-card]');
        const summaryList = document.getElementById('summary-list');
        const summaryEmpty = document.getElementById('summary-empty');
        const totalPoints = document.getElementById('total-points');
        const totalTagihan = document.getElementById('total-tagihan');
        const selectedCount = document.getElementById('selected-count');

        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }

        function formatCurrency(value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(value));
        }

        function renderSummary() {
            if (!summaryList || !totalPoints || !selectedCount || !totalTagihan) {
                return;
            }

            let total = 0;
            let totalHarga = 0;
            let count = 0;
            const items = [];

            cards.forEach((card) => {
                const checkbox = card.querySelector('.category-checkbox');
                const weightInput = card.querySelector('.category-weight');
                const active = checkbox.checked;
                const weight = parseFloat(weightInput.value || 0);
                const pointsPerKg = parseInt(card.dataset.poin, 10);
                const hargaPerKg = parseFloat(card.dataset.harga || 0);

                card.classList.toggle('border-emerald-500', active);
                card.classList.toggle('bg-emerald-50', active);
                card.classList.toggle('shadow-lg', active);

                if (active && weight > 0) {
                    const subtotal = Math.round(weight * pointsPerKg);
                    const subtotalHarga = weight * hargaPerKg;
                    total += subtotal;
                    totalHarga += subtotalHarga;
                    count += 1;
                    items.push(`
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-bold text-slate-800">${card.dataset.name}</p>
                                    <p class="mt-2 text-sm text-slate-500">${weight} kg</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-emerald-600">${formatNumber(subtotal)} poin</p>
                                    <p class="mt-1 text-sm font-bold text-rose-600">${formatCurrency(subtotalHarga)}</p>
                                </div>
                            </div>
                        </div>
                    `);
                }
            });

            summaryList.innerHTML = items.join('');

            if (count === 0) {
                summaryList.innerHTML = '';
                summaryList.appendChild(summaryEmpty);
                summaryEmpty.classList.remove('hidden');
            } else if (summaryEmpty) {
                summaryEmpty.classList.add('hidden');
            }

            selectedCount.textContent = `${count} Item`;
            totalPoints.textContent = formatNumber(total);
            totalTagihan.textContent = formatCurrency(totalHarga);
        }

        cards.forEach((card) => {
            const checkbox = card.querySelector('.category-checkbox');
            const weightInput = card.querySelector('.category-weight');

            card.addEventListener('click', (event) => {
                if (event.target === weightInput) {
                    return;
                }

                checkbox.checked = !checkbox.checked;
                renderSummary();
            });

            weightInput.addEventListener('click', (event) => event.stopPropagation());
            weightInput.addEventListener('input', renderSummary);
            checkbox.addEventListener('change', renderSummary);
        });

        renderSummary();
    </script>
</body>
</html>
