<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Titik Layanan - Admin SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

            <div class="mt-12">
                <p class="text-sm font-black uppercase tracking-[0.2em] text-emerald-300">Mode Supervisi</p>
                <div class="mt-4 flex items-center justify-between rounded-2xl bg-emerald-600/70 px-4 py-3">
                    <span class="text-sm font-bold">Akses: Administrator</span>
                    <span class="text-lg">⌄</span>
                </div>
            </div>

            <?php
                $menuItems = [
                    ['label' => 'Admin Dashboard', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Kelola Jadwal', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Verifikasi Laporan', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Kategori & Reward', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Kelola Reward', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Area Layanan', 'active' => true, 'href' => route('dashboard.admin.peta')],
                    ['label' => 'Pantau Petugas', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Riwayat Layanan', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Laporan Periodik', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Konten Edukasi', 'active' => false, 'href' => route('dashboard.admin')],
                    ['label' => 'Broadcast', 'active' => false, 'href' => route('dashboard.admin')],
                ];
            ?>

            <nav class="mt-10 space-y-2">
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item['href']); ?>"
                        class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition <?php echo e($item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5'); ?>">
                        <span class="text-xl"><?php echo e($item['active'] ? '▣' : '◦'); ?></span>
                        <span><?php echo e($item['label']); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-8">
                <?php echo csrf_field(); ?>
                <button type="submit" class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-lg text-emerald-50 transition hover:bg-white/5">
                    <span class="text-xl">↪</span>
                    <span>Keluar (Log Out)</span>
                </button>
            </form>

            <div class="mt-10 rounded-3xl bg-white/5 px-4 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black">R</div>
                    <div>
                        <p class="text-xl font-bold"><?php echo e($user->name); ?></p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Administrator</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Peta Titik Layanan</h1>
                    <p class="mt-2 text-lg text-slate-500">Sebaran TPS, bank sampah, dan usulan titik (Kota Bandung)</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('dashboard.admin')); ?>" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 text-lg font-semibold text-slate-700">Kembali ke jadwal</a>
                </div>
            </header>

            <section class="mt-10 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm shadow-slate-200/50">
                <?php if(session('success')): ?>
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-4xl font-black tracking-tight text-slate-800">Pemetaan komprehensif</h2>
                        <p class="mt-2 text-xl text-slate-500">Koordinat dari basis data; pembaruan dilakukan lewat pengelolaan data titik layanan.</p>
                    </div>
                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
                        <label class="relative flex-1 min-w-[220px]">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">⌕</span>
                            <input type="search" id="cari-lokasi" autocomplete="off" placeholder="Cari nama atau alamat..."
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-lg text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        </label>
                        <button type="button" id="peta-lokasi-saya"
                            class="whitespace-nowrap rounded-2xl border border-emerald-600 bg-emerald-50 px-5 py-3 text-lg font-semibold text-emerald-900 transition hover:bg-emerald-100">
                            Lokasi saya
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-6 text-sm font-semibold text-slate-600">
                    <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-green-600"></span> TPS</span>
                    <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-blue-600"></span> Bank Sampah</span>
                    <span class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-amber-600"></span> Usulan baru</span>
                </div>

                <div class="mt-6">
                    <?php echo $__env->make('peta._leaflet-map', ['titikLayanan' => $titikLayanan], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <div class="mt-10 border-t border-slate-200 pt-8">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h3 class="text-2xl font-black tracking-tight text-slate-800">Usulan Titik Layanan Menunggu Verifikasi</h3>
                            <p class="text-sm text-slate-500">Admin dapat menyetujui atau menolak usulan lokasi masyarakat.</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-500"><?php echo e($usulanMenunggu->count()); ?> usulan menunggu</p>
                    </div>

                    <div class="mt-5 space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $usulanMenunggu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <article class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-5">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-xl bg-amber-100 px-3 py-1 text-xs font-black uppercase tracking-[0.12em] text-amber-700">Diajukan</span>
                                            <span class="rounded-xl bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                                <?php echo e($usulan->jenis_layanan === 'bank_sampah' ? 'Bank Sampah' : 'Titik Sampah'); ?>

                                            </span>
                                        </div>

                                        <p class="mt-3 text-lg font-bold text-slate-800"><?php echo e($usulan->alamat_detail); ?></p>
                                        <p class="mt-1 text-sm text-slate-600">Koordinat: <?php echo e(number_format($usulan->latitude, 6)); ?>, <?php echo e(number_format($usulan->longitude, 6)); ?></p>
                                        <p class="mt-2 text-sm text-slate-600">Alasan: <?php echo e($usulan->deskripsi_alasan); ?></p>
                                        <p class="mt-2 text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">
                                            Pengusul: <?php echo e($usulan->pengusul?->name ?? 'Pengguna'); ?> • <?php echo e(optional($usulan->created_at)->translatedFormat('d M Y H:i')); ?>

                                        </p>
                                    </div>

                                    <div class="flex w-full flex-col gap-2 sm:w-auto">
                                        <form method="POST" action="<?php echo e(route('dashboard.admin.usulan.approve', $usulan)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-emerald-700">
                                                Setujui & Jadikan Titik Layanan
                                            </button>
                                        </form>

                                        <form method="POST" action="<?php echo e(route('dashboard.admin.usulan.reject', $usulan)); ?>" class="space-y-2">
                                            <?php echo csrf_field(); ?>
                                            <input type="text" name="catatan_verifikasi" placeholder="Alasan penolakan (opsional)"
                                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-100">
                                            <button type="submit" class="w-full rounded-xl border border-red-300 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 transition hover:bg-red-100">
                                                Tolak Usulan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-5 py-10 text-center text-sm text-slate-500">
                                Tidak ada usulan yang menunggu verifikasi saat ini.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </section>
        </main>
    </div>
</body>
</html>
<?php /**PATH D:\File Kuliah\Semester 6\Proyek Perangkat Lunak\SiResik\resources\views/peta/lokasi-admin.blade.php ENDPATH**/ ?>