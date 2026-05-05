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
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
                <span class="font-black text-emerald-500">"<?php echo e($permintaan->status); ?>"</span>.
            </p>

            <div class="mt-8 rounded-3xl bg-slate-50 p-6 text-left ring-1 ring-slate-200">
                <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400">Detail Penjemputan</p>
                <div class="mt-4 space-y-3 text-sm text-slate-700">
                    <p><?php echo e($permintaan->alamat); ?></p>
                    <p><?php echo e($permintaan->nomor_telepon); ?></p>
                    <p><?php echo e($permintaan->items->count()); ?> Kategori Sampah</p>
                </div>

                <div class="mt-5 space-y-3">
                    <?php $__currentLoopData = $permintaan->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl bg-white px-4 py-3 ring-1 ring-slate-200">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-bold text-slate-800"><?php echo e($item->kategoriSampah?->nama); ?></p>
                                    <p class="text-sm text-slate-500"><?php echo e(rtrim(rtrim(number_format($item->berat_kg, 2, '.', ''), '0'), '.')); ?> kg</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-[0.05em]"><?php echo e(number_format($item->estimasi_poin)); ?> poin</p>
                                    <p class="mt-1 font-bold text-emerald-600">Rp <?php echo e(number_format($item->total_tagihan, 0, ',', '.')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-5 space-y-3 border-t border-slate-200 pt-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400">Total Estimasi Poin</p>
                        <p class="text-2xl font-black text-emerald-500"><?php echo e(number_format($permintaan->total_estimasi_poin)); ?></p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400">Total Tagihan</p>
                        <p class="text-2xl font-black text-rose-600">Rp <?php echo e(number_format($permintaan->total_tagihan, 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <a href="<?php echo e(route('permintaan-penjemputan.index')); ?>" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-6 py-4 text-xl font-bold text-white">Buat Pengajuan Baru</a>
                <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 text-lg font-semibold text-slate-700">Kembali ke Dashboard</a>
            </div>
        </section>
    </main>
</body>
</html>
<?php /**PATH /Users/mac/Downloads/SiResik/resources/views/permintaan-penjemputan/success.blade.php ENDPATH**/ ?>