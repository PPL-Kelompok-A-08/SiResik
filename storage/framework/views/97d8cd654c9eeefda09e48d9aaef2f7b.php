<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Tugas Petugas - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

    
    <header class="bg-slate-950 text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 py-8 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">SiResik · Petugas</p>
                <h1 class="mt-2 text-4xl font-black">Riwayat Tugas</h1>
                <p class="mt-2 text-sm text-slate-300">Semua permintaan penjemputan yang ditugaskan kepada Anda.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('dashboard.petugas')); ?>" class="rounded-full bg-amber-300 px-5 py-3 text-sm font-bold text-slate-950">← Dashboard</a>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="rounded-full border border-white/20 px-5 py-3 text-sm font-bold text-white">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-10 space-y-8">

        
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-slate-900 p-5 text-white">
                <p class="text-sm text-slate-400">Total Tugas</p>
                <p class="mt-3 text-4xl font-black"><?php echo e($stats['total']); ?></p>
            </div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Menunggu</p>
                <p class="mt-3 text-4xl font-black text-sky-600"><?php echo e($stats['menunggu']); ?></p>
            </div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Diproses</p>
                <p class="mt-3 text-4xl font-black text-amber-600"><?php echo e($stats['diproses']); ?></p>
            </div>
            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Selesai</p>
                <p class="mt-3 text-4xl font-black text-emerald-600"><?php echo e($stats['selesai']); ?></p>
            </div>
        </section>

        
        <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 px-6 py-5">
            <form method="GET" action="<?php echo e(route('petugas.riwayat')); ?>" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Status</label>
                    <select name="status" class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                        <option value="">Semua Status</option>
                        <?php $__currentLoopData = ['Menunggu','Diproses','Selesai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>" <?php if(request('status') === $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Dari Tanggal</label>
                    <input type="date" name="dari" value="<?php echo e(request('dari')); ?>"
                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="<?php echo e(request('sampai')); ?>"
                           class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300">
                </div>
                <button type="submit" class="rounded-xl bg-amber-400 px-5 py-2 text-sm font-bold text-slate-900 hover:bg-amber-500 transition">Cari</button>
                <?php if(request()->anyFilled(['status','dari','sampai'])): ?>
                    <a href="<?php echo e(route('petugas.riwayat')); ?>" class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Reset</a>
                <?php endif; ?>
            </form>
        </section>

        
        <section class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="px-7 py-6 border-b border-slate-100">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-600">Daftar Tugas Penjemputan</p>
                <h2 class="mt-1 text-2xl font-bold">Semua Permintaan</h2>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $permintaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusClass = match($item->status) {
                        'Selesai'  => 'bg-emerald-100 text-emerald-800',
                        'Diproses' => 'bg-amber-100 text-amber-800',
                        default    => 'bg-sky-100 text-sky-800',
                    };
                    $canUpload = $item->status === 'Diproses' && $item->petugas_id === auth()->id();
                ?>
                <article class="border-b border-slate-100 last:border-0 px-7 py-5 hover:bg-slate-50 transition">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-xs font-black text-slate-400">#<?php echo e($item->id); ?></span>
                                <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($statusClass); ?>"><?php echo e($item->status); ?></span>
                                <?php if($item->bukti_penyelesaian): ?>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold bg-violet-100 text-violet-700">📷 Ada Bukti</span>
                                <?php endif; ?>
                            </div>
                            <p class="mt-2 font-bold text-lg"><?php echo e($item->pengguna?->name ?? '-'); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e($item->pengguna?->email ?? '-'); ?></p>
                            <div class="mt-2 flex flex-wrap gap-4 text-sm text-slate-600">
                                <span>📍 <?php echo e($item->alamat); ?></span>
                                <span>🗓 <?php echo e($item->jadwal); ?></span>
                                <span>📅 <?php echo e($item->tanggal); ?></span>
                            </div>
                            <?php if($item->catatan && $item->catatan !== '-'): ?>
                                <p class="mt-1 text-xs text-slate-400">Catatan: <?php echo e($item->catatan); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-col gap-2 min-w-[160px]">
                            <?php if($canUpload): ?>
                                <a href="<?php echo e(route('petugas.bukti.show', $item)); ?>"
                                   class="rounded-xl bg-amber-400 px-4 py-2 text-sm font-bold text-slate-900 text-center hover:bg-amber-500 transition">
                                    📷 Upload Bukti
                                </a>
                            <?php elseif($item->petugas_id === auth()->id() || auth()->user()->role === 'admin'): ?>
                                <a href="<?php echo e(route('petugas.bukti.show', $item)); ?>"
                                   class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 text-center hover:bg-slate-100 transition">
                                    Lihat Detail
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-7 py-16 text-center text-slate-400">
                    <div class="text-5xl mb-4">📋</div>
                    <p class="font-semibold">Belum ada tugas yang ditemukan.</p>
                    <?php if(request()->anyFilled(['status','dari','sampai'])): ?>
                        <p class="text-sm mt-1">Coba ubah filter pencarian.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>

        
        <?php if($permintaan->hasPages()): ?>
            <div class="flex justify-center">
                <?php echo e($permintaan->links()); ?>

            </div>
        <?php endif; ?>

    </main>
</body>
</html>
<?php /**PATH /Users/mac/Downloads/SiResik/resources/views/petugas/riwayat.blade.php ENDPATH**/ ?>