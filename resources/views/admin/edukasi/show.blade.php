@extends('layouts.admin')

@section('title', 'Detail Artikel - Admin SiResik')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-md-flex align-items-center justify-content-between mb-4">
        <div>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill mb-2 text-xs">{{ $edukasi->kategori }}</span>
            <h1 class="h3 mb-0 text-success fw-bold">{{ $edukasi->judul }}</h1>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.edukasi.index') }}" class="btn btn-outline-success rounded-pill px-4">Kembali</a>
            <a href="{{ route('admin.edukasi.edit', $edukasi->id) }}" class="btn btn-primary rounded-pill px-4">Ubah</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                <img src="{{ Str::startsWith($edukasi->gambar, 'http') ? $edukasi->gambar : asset('storage/' . $edukasi->gambar) }}" class="img-fluid" alt="Cover" style="width: 100%; max-height: 400px; object-fit: cover;">
                
                <div class="card-body p-4 p-md-5">
                    <div class="text-muted small mb-4">
                        <i class="bi bi-calendar-event me-2 text-success"></i>Diterbitkan: {{ \Carbon\Carbon::parse($edukasi->created_at)->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                    <hr class="my-3">
                    <div class="article-body text-dark lh-lg" style="white-space: pre-line; text-align: justify;">
                        {!! $edukasi->isi !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-success">Informasi Meta & URL</h5>
                <div class="mb-3">
                    <small class="text-muted d-block">Slug URL Publik:</small>
                    <code class="d-block p-2 bg-light border rounded text-success text-break" style="font-size: 0.8rem;">
                        /edukasi/{{ $edukasi->slug }}
                    </code>
                </div>
                <div>
                    <small class="text-muted d-block">Terakhir Diedit:</small>
                    <span class="text-dark small">{{ \Carbon\Carbon::parse($item->updated_at ?? now())->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection