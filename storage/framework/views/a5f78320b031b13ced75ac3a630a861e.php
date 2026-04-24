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
                <a href="<?php echo e(route('dashboard')); ?>" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700">Dashboard</a>
                <a href="#form-penjemputan" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white">+ Ajukan Penjemputan</a>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-emerald-900 p-5 text-white"><p class="text-sm text-emerald-100">Total</p><p class="mt-3 text-4xl font-black"><?php echo e($statusCounts['total']); ?></p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Menunggu</p><p class="mt-3 text-4xl font-black text-sky-600"><?php echo e($statusCounts['menunggu']); ?></p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Diproses</p><p class="mt-3 text-4xl font-black text-amber-600"><?php echo e($statusCounts['diproses']); ?></p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Selesai</p><p class="mt-3 text-4xl font-black text-emerald-600"><?php echo e($statusCounts['selesai']); ?></p></div>
        </section>

        <?php if($errors->any()): ?>
            <div class="mt-8 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                <p class="font-semibold">Pengajuan belum bisa diproses.</p>
                <ul class="mt-2 list-disc pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <section id="form-penjemputan" class="mt-8 rounded-[2rem] bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
            <div class="rounded-t-[2rem] bg-gradient-to-r from-emerald-500 to-emerald-400 px-8 py-8 text-white">
                <h2 class="text-5xl font-black tracking-tight">Ajukan Penjemputan</h2>
                <p class="mt-3 max-w-2xl text-lg text-emerald-50">Lengkapi data penjemputan agar petugas kami dapat segera meluncur.</p>
            </div>

            <?php if($canCreate): ?>
                <form action="<?php echo e(route('permintaan-penjemputan.store')); ?>" method="POST" class="grid gap-8 px-8 py-8 lg:grid-cols-[1.2fr,0.75fr]">
                    <?php echo csrf_field(); ?>

                    <div class="space-y-8">
                        <section>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">1. Data Lokasi & Kontak</p>
                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <?php if($isAdmin): ?>
                                    <label class="block md:col-span-2">
                                        <span class="mb-2 block text-sm font-semibold text-slate-700">Pengguna</span>
                                        <select name="pengguna_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                            <option value="">Pilih pengguna</option>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selectedUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($selectedUser->id); ?>" <?php if(old('pengguna_id') == $selectedUser->id): echo 'selected'; endif; ?>><?php echo e($selectedUser->name); ?> - <?php echo e($selectedUser->email); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </label>
                                <?php else: ?>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pengguna</p>
                                        <p class="mt-2 font-semibold text-slate-900"><?php echo e($user->name); ?></p>
                                        <p class="text-sm text-slate-500"><?php echo e($user->email); ?></p>
                                    </div>
                                <?php endif; ?>

                                <label class="block <?php echo e($isAdmin ? '' : 'md:col-span-2'); ?>">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Alamat penjemputan</span>
                                    <input type="text" name="alamat" value="<?php echo e(old('alamat')); ?>" placeholder="Jl. Merpati No. 12, Sukamaju" maxlength="45" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Nomor telepon aktif</span>
                                    <input type="text" name="nomor_telepon" value="<?php echo e(old('nomor_telepon')); ?>" placeholder="081234567890" maxlength="20" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Tanggal penjemputan</span>
                                    <input type="date" name="tanggal" value="<?php echo e(old('tanggal')); ?>" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>

                                <label class="block">
                                    <span class="mb-2 block text-sm font-semibold text-slate-700">Jadwal</span>
                                    <input type="text" name="jadwal" value="<?php echo e(old('jadwal')); ?>" placeholder="08.00 - 10.00 WIB" maxlength="45" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" required>
                                </label>
                            </div>
                        </section>

                        <section>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">2. Pilih Kategori Sampah</p>
                            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                <?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $oldSelected = collect(old('selected_categories', []))->contains($item->id);
                                        $oldBerat = old("berat.$item->id", 1);
                                    ?>
                                    <div class="kategori-card group block cursor-pointer rounded-3xl border border-slate-200 bg-white p-5 transition hover:border-emerald-300 hover:shadow-lg"
                                        data-card
                                        data-id="<?php echo e($item->id); ?>"
                                        data-name="<?php echo e($item->nama); ?>"
                                        data-poin="<?php echo e($item->poin_per_kg); ?>">
                                        <input type="checkbox" name="selected_categories[]" value="<?php echo e($item->id); ?>" class="sr-only category-checkbox" <?php if($oldSelected): echo 'checked'; endif; ?>>
                                        <div class="flex h-full flex-col">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="h-14 w-14 rounded-2xl bg-slate-100 text-2xl leading-[3.5rem] text-center text-emerald-600">♻</div>
                                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700"><?php echo e(number_format($item->poin_per_kg)); ?> poin/kg</span>
                                            </div>
                                            <h3 class="mt-5 text-lg font-bold text-slate-900"><?php echo e($item->nama); ?></h3>
                                            <p class="mt-2 text-sm leading-6 text-slate-500"><?php echo e($item->deskripsi); ?></p>

                                            <div class="mt-5">
                                                <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Berat (kg)</span>
                                                <input
                                                    type="number"
                                                    min="0"
                                                    step="0.5"
                                                    name="berat[<?php echo e($item->id); ?>]"
                                                    value="<?php echo e($oldBerat); ?>"
                                                    class="category-weight w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </section>

                        <section>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">3. Catatan Tambahan</p>
                            <label class="mt-4 block">
                                <textarea name="catatan" rows="4" maxlength="45" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-emerald-400 focus:bg-white" placeholder="Sampah sudah dipilah per karung"><?php echo e(old('catatan')); ?></textarea>
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

                        <button type="submit" class="mt-8 inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-6 py-4 text-lg font-bold text-white shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-600">
                            Ajukan Penjemputan
                        </button>

                        <p class="mt-5 text-center text-xs leading-6 text-slate-400">Dengan klik tombol di atas, Anda menyetujui syarat dan ketentuan layanan penjemputan sampah SiResik.</p>
                    </aside>
                </form>
            <?php else: ?>
                <div class="px-8 py-8">
                    <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                        Akun petugas tidak dapat membuat permintaan baru. Gunakan halaman ini untuk memantau antrean penjemputan yang sudah masuk.
                    </div>
                </div>
            <?php endif; ?>
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
                            <th class="pb-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $permintaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="align-top">
                                <td class="py-4 pr-4">
                                    <p class="font-semibold text-slate-900"><?php echo e($item->pengguna?->name ?? '-'); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($item->pengguna?->email ?? '-'); ?></p>
                                </td>
                                <td class="py-4 pr-4">
                                    <p><?php echo e($item->alamat); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($item->nomor_telepon); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($item->tanggal); ?> | <?php echo e($item->jadwal); ?></p>
                                </td>
                                <td class="py-4 pr-4">
                                    <div class="space-y-2">
                                        <?php $__currentLoopData = $item->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="rounded-2xl bg-slate-50 px-3 py-2">
                                                <p class="font-semibold text-slate-700"><?php echo e($detail->kategoriSampah?->nama); ?></p>
                                                <p class="text-xs text-slate-500"><?php echo e(rtrim(rtrim(number_format($detail->berat_kg, 2, '.', ''), '0'), '.')); ?> kg</p>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </td>
                                <td class="py-4 pr-4 font-bold text-emerald-600"><?php echo e(number_format($item->total_estimasi_poin)); ?></td>
                                <td class="py-4">
                                    <?php
                                        $statusClass = match ($item->status) {
                                            'Diproses' => 'bg-amber-100 text-amber-700',
                                            'Selesai' => 'bg-emerald-100 text-emerald-700',
                                            default => 'bg-sky-100 text-sky-700',
                                        };
                                    ?>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold <?php echo e($statusClass); ?>">
                                        <?php echo e($item->status); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-500">Belum ada permintaan penjemputan yang masuk.</td>
                            </tr>
                        <?php endif; ?>
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
        const selectedCount = document.getElementById('selected-count');

        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }

        function renderSummary() {
            if (!summaryList || !totalPoints || !selectedCount) {
                return;
            }

            let total = 0;
            let count = 0;
            const items = [];

            cards.forEach((card) => {
                const checkbox = card.querySelector('.category-checkbox');
                const weightInput = card.querySelector('.category-weight');
                const active = checkbox.checked;
                const weight = parseFloat(weightInput.value || 0);
                const pointsPerKg = parseInt(card.dataset.poin, 10);

                card.classList.toggle('border-emerald-500', active);
                card.classList.toggle('bg-emerald-50', active);
                card.classList.toggle('shadow-lg', active);

                if (active && weight > 0) {
                    const subtotal = Math.round(weight * pointsPerKg);
                    total += subtotal;
                    count += 1;
                    items.push(`
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-bold text-slate-800">${card.dataset.name}</p>
                                    <p class="mt-2 text-sm text-slate-500">${weight} kg</p>
                                </div>
                                <p class="text-sm font-bold text-emerald-600">${formatNumber(subtotal)} poin</p>
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
<?php /**PATH /Users/luckygirlsyndrome/Documents/College/SEM 6/PPL PROJECTS/SiResik/resources/views/permintaan-penjemputan/index.blade.php ENDPATH**/ ?>