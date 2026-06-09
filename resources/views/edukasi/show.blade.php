@extends('layouts.app')

@section('title', $edukasi->judul . ' - SiResik')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-success text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('edukasi.index') }}" class="text-success text-decoration-none">Edukasi</a></li>
            <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 250px;">{{ $edukasi->judul }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mb-5">
            <article class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="{{ Str::startsWith($edukasi->gambar, 'http') ? $edukasi->gambar : asset('storage/' . $edukasi->gambar) }}" class="img-fluid" alt="{{ $edukasi->judul }}" style="width: 100%; max-height: 400px; object-fit: cover;">
                
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-wrap align-items-center mb-3 text-muted gap-3">
                        <span class="badge bg-success px-3 py-2 rounded-pill text-white">{{ $edukasi->kategori }}</span>
                        <div class="small"><i class="bi bi-calendar3 me-1 text-success"></i> {{ \Carbon\Carbon::parse($edukasi->created_at)->translatedFormat('d F Y') }}</div>
                    </div>

                    <h1 class="fw-bold text-success h2 mb-4">{{ $edukasi->judul }}</h1>
                    <hr class="my-4 border-light-subtle">

                    <div class="article-content text-dark fs-5 lh-lg mb-5" style="white-space: pre-line; text-align: justify;">
                        {!! $edukasi->isi !!}
                    </div>

                    <hr class="mb-4">
                    <a href="{{ route('edukasi.index') }}" class="btn btn-outline-success rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </article>
        </div>

        <!-- Sidebar Pendukung -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-success mb-3 border-bottom pb-2">Rekomendasi Terkait</h5>
                @if($artikelTerkait->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($artikelTerkait as $terkait)
                            <div class="row g-2 align-items-center">
                                <div class="col-4">
                                    <img src="{{ Str::startsWith($terkait->gambar, 'http') ? $terkait->gambar : asset('storage/' . $terkait->gambar) }}" class="img-fluid rounded-3" alt="{{ $terkait->judul }}" style="height: 55px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="col-8">
                                    <h6 class="fw-semibold text-dark mb-1 text-truncate" style="font-size: 0.85rem;" title="{{ $terkait->judul }}">
                                        <a href="{{ route('edukasi.show', $terkait->slug) }}" class="text-decoration-none text-dark hover-success">{{ $terkait->judul }}</a>
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($terkait->created_at)->translatedFormat('d M Y') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">Belum ada bacaan sejenis untuk kategori ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection