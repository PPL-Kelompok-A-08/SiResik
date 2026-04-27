<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Poin - SiResik</title>
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

            <?php
                $menuItems = [
                    ['label' => 'Dashboard', 'active' => false, 'href' => route('dashboard.masyarakat')],
                    ['label' => 'Penjemputan', 'active' => false, 'href' => route('permintaan-penjemputan.index')],
                    ['label' => 'Status Layanan', 'active' => false],
                    ['label' => 'Riwayat Layanan', 'active' => false],
                    ['label' => 'Poin & Reward', 'active' => true, 'href' => route('poin.index')],
                    ['label' => 'Sampah Liar', 'active' => false],
                    ['label' => 'Peta & Lokasi', 'active' => false, 'href' => route('peta.lokasi')],
                    ['label' => 'Usulkan Titik', 'active' => false, 'href' => route('peta.usulan-titik')],
                    ['label' => 'Edukasi Lingkungan', 'active' => false],
                    ['label' => 'Kegiatan Lingkungan', 'active' => false],
                    ['label' => 'Notifikasi', 'active' => false],
                ];
            ?>

            <nav class="mt-14 space-y-2">
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item['href'] ?? '#'); ?>"
                        class="flex items-center gap-4 rounded-2xl px-5 py-4 text-lg transition <?php echo e($item['active'] ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-50 hover:bg-white/5'); ?>">
                        <span class="text-xl"><?php echo e($item['active'] ? '◉' : '◦'); ?></span>
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
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-xl font-black"><?php echo e(substr($user->name, 0, 1)); ?></div>
                    <div>
                        <p class="text-xl font-bold"><?php echo e($user->name); ?></p>
                        <p class="text-xs uppercase tracking-[0.15em] text-emerald-100">Warga Terverifikasi</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="px-6 py-8 lg:px-10">
            <header class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-5xl font-black tracking-tight text-slate-900">Dashboard Poin</h1>
                    <p class="mt-2 text-lg text-slate-500">Pantau perolehan poin dan penukaran reward Anda</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="<?php echo e(route('reward.index')); ?>" class="rounded-2xl bg-orange-500 px-6 py-3 text-lg font-bold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600">Tukar Reward</a>
                </div>
            </header>

            <section class="mt-12 grid gap-8 xl:grid-cols-[1fr,1.5fr]">
                <!-- Panel Poin Total -->
                <div class="flex flex-col gap-8">
                    <div class="rounded-[2.5rem] bg-emerald-500 p-8 shadow-xl shadow-emerald-500/20 text-white relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 text-9xl opacity-10 font-black">🌟</div>
                        <h2 class="text-xl font-black uppercase tracking-[0.1em] text-emerald-100">Poin Saya</h2>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-7xl font-black tracking-tight"><?php echo e(number_format($totalPoin, 0, ',', '.')); ?></span>
                            <span class="text-2xl font-bold text-emerald-100">Poin</span>
                        </div>
                    </div>

                    <!-- List Riwayat Poin -->
                    <div class="rounded-[2.5rem] bg-white p-8 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                        <h2 class="text-2xl font-black text-slate-800 mb-6">Riwayat Poin Pengguna</h2>

                        <div class="space-y-6">
                            <?php $__empty_1 = true; $__currentLoopData = $poins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center justify-between pb-6 <?php echo e(!$loop->last ? 'border-b border-slate-100' : ''); ?>">
                                    <div class="flex items-center gap-5">
                                        <?php if($poin->tipe === 'masuk'): ?>
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-2xl text-emerald-600">
                                                ⬇
                                            </div>
                                        <?php else: ?>
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 text-2xl text-red-600">
                                                ⬆
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="text-xl font-bold text-slate-800"><?php echo e($poin->keterangan); ?></p>
                                            <p class="text-sm text-slate-400 mt-1"><?php echo e(\Carbon\Carbon::parse($poin->tanggal)->translatedFormat('d M Y')); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-black <?php echo e($poin->tipe === 'masuk' ? 'text-emerald-500' : 'text-red-500'); ?>">
                                            <?php echo e($poin->tipe === 'masuk' ? '+' : '-'); ?><?php echo e(number_format($poin->jumlah, 0, ',', '.')); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="py-10 text-center">
                                    <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-slate-50 text-4xl text-slate-300 mb-4">📭</div>
                                    <p class="text-lg font-medium text-slate-500">Belum ada aktivitas poin</p>
                                    <p class="text-sm text-slate-400 mt-1">Mulai setorkan sampah untuk mendapatkan poin perdana Anda!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tren Poin Chart / Placeholder -->
                <div class="rounded-[2.5rem] bg-white p-8 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200 flex flex-col">
                    <h2 class="text-2xl font-black text-slate-800 mb-6">Tren Poin Bulan Ini</h2>
                    <div class="flex-1 rounded-3xl border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center relative p-8">
                        <!-- Simple visual bars representation -->
                        <div class="w-full h-full flex items-end justify-between gap-4">
                            <?php
                                $heights = [40, 60, 30, 80, 50, 90, 70];
                            ?>
                            <?php $__currentLoopData = $heights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $height): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="w-full bg-emerald-500/20 rounded-t-xl relative group transition hover:bg-emerald-500/40" style="height: <?php echo e($height); ?>%">
                                    <div class="opacity-0 group-hover:opacity-100 absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs font-bold py-1 px-2 rounded whitespace-nowrap transition">
                                        Minggu <?php echo e($loop->iteration); ?>

                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Dhydo Aryo Jayanata\Documents\GitHub\TUBES\SiResik\resources\views/poin/index.blade.php ENDPATH**/ ?>