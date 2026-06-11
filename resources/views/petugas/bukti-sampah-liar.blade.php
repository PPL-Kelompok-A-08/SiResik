@extends('layouts.petugas')

@section('title', 'Unggah Bukti Penanganan - SiResik')

@section('content')
<div class="p-6 lg:p-8 max-w-2xl">

    {{-- PAGE HEADER --}}
    <div class="mb-8">
        <a href="{{ route('dashboard.petugas') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 mb-4">
            ← Kembali ke Dashboard
        </a>
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-600">SiResik · Petugas</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">Unggah Bukti Penanganan</h1>
        <p class="mt-1 text-sm text-slate-500">Laporan: <strong>{{ $laporan->lokasi }}</strong> — Dilaporkan oleh {{ $laporan->pengguna->name ?? '—' }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 flex items-start gap-3">
            <span class="text-2xl">✅</span>
            <p class="text-emerald-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="rounded-[2rem] bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 px-7 py-6">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-200">Status Laporan</p>
            <p class="mt-2 text-xl font-black text-white">{{ ucfirst($laporan->status) }}</p>
            @if($laporan->petugas)
                <p class="mt-1 text-sm text-emerald-200">Ditugaskan ke: {{ $laporan->petugas->name }}</p>
            @endif
        </div>

        <div class="px-7 py-6">
            <form action="{{ route('petugas.bukti.sampah_liar.upload', $laporan) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Foto Bukti Penanganan <span class="text-rose-500">*</span>
                        <span class="text-slate-400 font-normal">(jpeg/png/webp, maks 5MB)</span>
                    </label>
                    <input type="file"
                           name="bukti_foto"
                           required
                           accept="image/*"
                           class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    @error('bukti_foto')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Catatan <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="catatan_petugas"
                              rows="4"
                              class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 transition resize-none"
                              placeholder="Contoh: Sampah liar telah dibersihkan...">{{ old('catatan_petugas') }}</textarea>
                    @error('catatan_petugas')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="rounded-2xl bg-emerald-600 px-6 py-3 text-white font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                        ✅ Unggah &amp; Tandai Selesai
                    </button>
                    <a href="{{ route('dashboard.petugas') }}"
                       class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
