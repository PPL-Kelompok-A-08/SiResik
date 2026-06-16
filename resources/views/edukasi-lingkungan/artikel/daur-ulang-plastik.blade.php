<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daur Ulang Plastik - SiResik</title>
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
            <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=1200&q=80" class="h-64 w-full rounded-xl object-cover mb-8">
            <span class="rounded-full bg-blue-100 text-blue-700 px-3 py-1 text-[10px] font-black uppercase tracking-widest">🌿 DAUR ULANG</span>
            <h1 class="mt-4 text-3xl font-black text-slate-800">Mengubah Sampah Plastik Menjadi Kerajinan Bernilai</h1>
            <div class="mt-6 space-y-4 text-slate-600 leading-relaxed">
                <p>Plastik membutuhkan waktu hingga 500 tahun untuk terurai di alam. Oleh karena itu, *upcycling* atau mendaur ulang kreatif adalah salah satu cara terbaik untuk memperpanjang usia pakai plastik sebelum akhirnya masuk ke mesin daur ulang pabrik.</p>
                <h3 class="text-xl font-bold text-slate-800 mt-6">Ide Kerajinan Sederhana</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Pot Tanaman Vertikal:</strong> Gunakan botol air mineral ukuran 1,5 liter, potong bagian sampingnya, dan gantung menggunakan tali tambang di dinding rumah.</li>
                    <li><strong>Ecobrick:</strong> Masukkan sampah plastik sisa kemasan makanan (yang sudah dicuci bersih dan dikeringkan) ke dalam botol plastik hingga padat. Ecobrick ini bisa disusun menjadi kursi atau meja.</li>
                    <li><strong>Wadah Serbaguna:</strong> Bagian bawah botol soda dapat dipotong dan disetrika ujungnya agar tidak tajam, lalu digunakan sebagai wadah alat tulis atau kuas rias.</li>
                </ul>
                <p class="mt-4">Jangan lupa, jika sampah plastik Anda sudah menumpuk dan tidak bisa diolah menjadi kerajinan, setor langsung ke pengepul lokal melalui layanan <strong>Pick Up SiResik</strong>!</p>
            </div>
        </article>
    </main>
</div>
</body>
</html>