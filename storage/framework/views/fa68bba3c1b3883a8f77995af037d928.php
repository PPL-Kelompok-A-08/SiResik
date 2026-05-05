<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen xl:grid xl:grid-cols-[300px,1fr]">
        <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="px-6 py-8 lg:px-10">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Jadwal hari ini</p><p class="mt-3 text-4xl font-black"><?php echo e($stats['jadwal_hari_ini']); ?></p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Menunggu</p><p class="mt-3 text-4xl font-black text-sky-600"><?php echo e($stats['menunggu']); ?></p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Diproses</p><p class="mt-3 text-4xl font-black text-amber-600"><?php echo e($stats['diproses']); ?></p></div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200"><p class="text-sm text-slate-500">Selesai</p><p class="mt-3 text-4xl font-black text-emerald-600"><?php echo e($stats['selesai']); ?></p></div>
        </section>

        <section class="mt-8 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-600">Antrean Operasional</p>
            <h2 class="mt-2 text-2xl font-bold">Permintaan terbaru</h2>
            <div class="mt-6 grid gap-4">
                <?php $__empty_1 = true; $__currentLoopData = $permintaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="rounded-3xl border border-slate-200 p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-lg font-bold"><?php echo e($item->pengguna?->name ?? '-'); ?></p>
                                <p class="text-sm text-slate-500"><?php echo e($item->pengguna?->email ?? '-'); ?></p>
                                <p class="mt-3 text-sm text-slate-600"><?php echo e($item->alamat); ?></p>
                                <p class="text-sm text-slate-600"><?php echo e($item->jadwal); ?> | <?php echo e($item->tanggal); ?></p>
                            </div>
                            <div class="flex flex-col gap-2 items-end">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700"><?php echo e($item->status); ?></span>
                                <?php if($item->status === 'Diproses' && $item->petugas_id === auth()->id()): ?>
                                    <a href="<?php echo e(route('petugas.bukti.show', $item)); ?>"
                                       class="rounded-xl bg-amber-400 px-3 py-1.5 text-xs font-bold text-slate-900 hover:bg-amber-500 transition whitespace-nowrap">
                                        📷 Upload Bukti
                                    </a>
                                <?php elseif($item->status === 'Selesai'): ?>
                                    <a href="<?php echo e(route('petugas.bukti.show', $item)); ?>"
                                       class="rounded-xl bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-200 transition whitespace-nowrap">
                                        ✅ Lihat Bukti
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">Catatan: <?php echo e($item->catatan); ?></p>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-3xl border border-dashed border-slate-300 p-8 text-center text-slate-500">Belum ada permintaan yang masuk.</div>
                <?php endif; ?>
            </div>
        </section>
        </main>
    </div>
</body>
</html>
<?php /**PATH /Users/mac/Downloads/SiResik/resources/views/dashboard/petugas.blade.php ENDPATH**/ ?>