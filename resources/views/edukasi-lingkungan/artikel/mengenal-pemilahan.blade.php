<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mengenal Jenis Sampah - SiResik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap'); * { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-[#f1f5f1] text-slate-900">
<div class="min-h-screen xl:grid xl:grid-cols-[260px,1fr]">
    <x-sidebar />
    <main class="flex flex-col gap-5 px-7 py-6">
        <header>
            <a href="{{ url('/edukasi-lingkungan') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">← Kembali ke Wawasan</a>
        </header>
        <article class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <img src="https://images.unsplash.com/photo-1604187351574-c75ca79f5807?w=1200&q=80" class="h-64 w-full rounded-xl object-cover mb-8">
            <span class="rounded-full bg-purple-100 text-purple-700 px-3 py-1 text-[10px] font-black uppercase tracking-widest">🌿 EDUKASI</span>
            <h1 class="mt-4 text-3xl font-black text-slate-800">Mengenal Jenis Sampah dan Cara Pemilahan yang Benar</h1>
            <div class="mt-6 space-y-4 text-slate-600 leading-relaxed">
                <p>Memilah sampah adalah pondasi dasar dari ekonomi sirkular. Sampah yang tercampur akan sangat sulit didaur ulang, bahkan seringkali langsung berakhir di TPA (Tempat Pembuangan Akhir). Mari kenali 3 kategori utamanya:</p>
                <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 mt-4">
                    <h4 class="font-bold text-emerald-700">1. Sampah Organik (Hijau)</h4>
                    <p class="text-sm mt-1">Sampah yang mudah membusuk. Contoh: sisa tulang, kulit buah, sisa sayuran, dan dedaunan. Pisahkan sampah ini untuk dijadikan kompos atau pakan maggot.</p>
                </div>
                <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 mt-4">
                    <h4 class="font-bold text-blue-700">2. Sampah Anorganik (Kuning/Biru)</h4>
                    <p class="text-sm mt-1">Sampah yang sulit terurai tetapi bisa didaur ulang. Contoh: botol plastik, kaleng soda, kertas kardus, dan kaca. Pastikan Anda membersihkan sisa makanan/minuman dari sampah ini sebelum dibuang agar tidak mengundang semut dan bau.</p>
                </div>
                <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 mt-4">
                    <h4 class="font-bold text-red-700">3. Sampah B3 (Merah)</h4>
                    <p class="text-sm mt-1">Bahan Berbahaya dan Beracun. Contoh: baterai bekas, lampu neon, obat kadaluwarsa, dan kemasan pestisida. Sampah ini memerlukan penanganan khusus dan tidak boleh dicampur dengan sampah biasa.</p>
                </div>
                <p class="mt-6 text-sm text-slate-500 italic">Mulailah dengan menyediakan minimal dua tempat sampah di rumah: satu untuk organik dan satu untuk anorganik.</p>
            </div>
        </article>
    </main>
</div>
</body>
</html>