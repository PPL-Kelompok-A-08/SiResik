@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light btn-sm text-secondary">← Batal</a>
    </div>

    <div class="card border-0 shadow-sm p-4 bg-white rounded-lg max-w-lg mx-auto">
        <h4 class="font-weight-bold text-warning mb-4">Sunting Materi Siaran</h4>
        <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted">JUDUL *</label>
                <input type="text" class="form-control" name="judul" value="{{ $pengumuman->judul }} " required>
            </div>
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted">TANGGAL PUBLISH *</label>
                <input type="date" class="form-control" name="tanggal_publish" value="{{ $pengumuman->tanggal_publish->format('Y-m-d') }}" required>
            </div>
            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted">KONTEN ISI *</label>
                <textarea class="form-control" name="isi" rows="6" required>{{ $pengumuman->isi }}</textarea>
            </div>
            <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2.5 text-dark">Perbarui Pengumuman</button>
        </form>
    </div>
</div>
@endsection