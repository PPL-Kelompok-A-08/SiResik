<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal - {{ $titikLayanan->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 50; display: none; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: white; border-radius: 24px; padding: 32px; width: 480px; max-width: 90vw; max-height: 85vh; overflow-y: auto; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('dashboard.admin') }}" class="text-emerald-600 hover:underline mb-2 inline-block">← Kembali ke Dashboard</a>
                <h1 class="text-3xl font-black text-slate-900">Jadwal Operasional</h1>
                <p class="text-slate-500">{{ $titikLayanan->nama }} ({{ $titikLayanan->jenis }})</p>
            </div>
            <button onclick="openModal('modal-tambah')" class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20">+ Tambah Jadwal</button>
        </div>

        @if(session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 ring-1 ring-slate-200 p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b border-slate-100 text-sm font-bold text-slate-400 uppercase tracking-wider">Hari</th>
                        <th class="py-3 px-4 border-b border-slate-100 text-sm font-bold text-slate-400 uppercase tracking-wider">Jam Buka</th>
                        <th class="py-3 px-4 border-b border-slate-100 text-sm font-bold text-slate-400 uppercase tracking-wider">Jam Tutup</th>
                        <th class="py-3 px-4 border-b border-slate-100 text-sm font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                        <th class="py-3 px-4 border-b border-slate-100 text-sm font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $j)
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-4 font-semibold text-slate-800">{{ $j->hari }}</td>
                        <td class="py-4 px-4 text-slate-600">{{ \Carbon\Carbon::parse($j->jam_buka)->format('H:i') }}</td>
                        <td class="py-4 px-4 text-slate-600">{{ \Carbon\Carbon::parse($j->jam_tutup)->format('H:i') }}</td>
                        <td class="py-4 px-4 text-slate-600">{{ $j->keterangan ?? '-' }}</td>
                        <td class="py-4 px-4 text-right">
                            <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">Belum ada jadwal operasional yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="modal-tambah" class="modal-overlay" onclick="if(event.target.id === 'modal-tambah') document.getElementById('modal-tambah').classList.remove('open')">
        <div class="modal">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-slate-800">Tambah Jadwal</h3>
                <button onclick="document.getElementById('modal-tambah').classList.remove('open')" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
            </div>
            <form action="{{ route('admin.jadwal.store', $titikLayanan->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Hari</label>
                        <select name="hari" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-emerald-500 outline-none" required>
                            <option value="Senin">Senin</option>
                            <option value="Senin-Kamis">Senin-Kamis</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Jam Buka</label>
                            <input type="time" name="jam_buka" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-emerald-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-emerald-500 outline-none" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Istirahat 12:00-13:00" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-emerald-500 outline-none">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.remove('open')" class="flex-1 rounded-xl border border-slate-300 py-3 text-sm font-semibold text-slate-600">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-emerald-500 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('open');
        }
    </script>
</body>
</html>
