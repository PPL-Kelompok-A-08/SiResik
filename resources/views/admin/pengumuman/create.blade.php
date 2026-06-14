@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light btn-sm text-secondary">← Batal</a>
    </div>

    <div class="card border-0 shadow-sm p-4 bg-white rounded-lg max-w-lg mx-auto">
        <h4 class="font-weight-bold text-success mb-4">Rilis Broadcast Pengumuman Resmi</h4>
        <form action="{{ route('admin.pengumuman.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted">JUDUL PENGUMUMAN *</label>
                <input type="text" class="form-control" name="judul" required placeholder="Contoh: Info Kenaikan Poin Kategori Plastik">
            </div>
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted">JADWAL TANGGAL TERBIT *</label>
                <input type="date" class="form-control" name="tanggal_publish" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted">ISI DETAIL BERITA *</label>
                <textarea class="form-control" name="isi" rows="6" required placeholder="Masukkan detail komunikasi pengumuman..."></textarea>
            </div>
            <button type="submit" class="btn btn-success btn-block font-weight-bold py-2.5">Siarkan Sekarang 📢</button>
        </form>
    </div>
</div>
@endsection