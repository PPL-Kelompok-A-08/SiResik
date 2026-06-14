<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiResik - Edukasi Lingkungan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        .card-h { transition: transform 0.25s, box-shadow 0.25s; }
        .card-h:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1.5rem rgba(0,100,0,.08) !important; }
        .hover-su:hover { color: #198754 !important; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-lg-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-success text-decoration-none">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edukasi</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold text-success mb-3">
                <i class="bi bi-journal-richtext me-2"></i>Edukasi Lingkungan
            </h1>
            <p class="lead text-muted">
                Dapatkan wawasan seputar pengolahan sampah organik, anorganik, daur ulang kreatif, dan gaya hidup minim limbah di SiResik.
            </p>
        </div>
        <div class="col-lg-5 d-none d-lg-block text-center">
            <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80" alt="Edukasi Hijau" class="img-fluid rounded-4 shadow-sm" style="max-height: 220px; object-fit: cover; width: 100%;">
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold mb-3 text-success">
                    <i class="bi bi-search me-2"></i>Cari Artikel
                </h5>
                <form action="{{ route('edukasi.index') }}" method="GET">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <div class="input-group mb-0">
                        <input type="text" name="search" class="form-control border-success-subtle bg-white" placeholder="Kata kunci..." value="{{ $search ?? '' }}">
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-success border-bottom pb-2">
                    <i class="bi bi-tags me-2"></i>Daftar Kategori
                </h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('edukasi.index', request()->only('search')) }}" class="list-group-item list-group-item-action border-0 px-0 {{ !request('kategori') ? 'text-success fw-bold' : 'text-muted' }}">
                        <i class="bi bi-folder2-open me-2"></i>Semua Kategori
                    </a>
                    @foreach($daftarKategori as $kat)
                        <a href="{{ route('edukasi.index', array_merge(request()->only('search'), ['kategori' => $kat])) }}" class="list-group-item list-group-item-action border-0 px-0 {{ request('kategori') === $kat ? 'text-success fw-bold' : 'text-muted' }}">
                            <i class="bi bi-chevron-right me-1 text-success"></i> {{ $kat }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @if($edukasis->count() > 0)
                <div class="row g-4">
                    @foreach($edukasis as $item)
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-h bg-white">
                                <div class="position-relative">
                                    <img src="{{ Str::startsWith($item->gambar, 'http') ? $item->gambar : asset('storage/' . $item->gambar) }}" class="card-img-top" alt="{{ $item->judul }}" style="height: 200px; object-fit: cover;">
                                    <span class="position-absolute badge bg-success px-3 py-2 rounded-pill shadow-sm" style="top: 1rem; left: 1rem;">
                                        {{ $item->kategori }}
                                    </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="text-muted small mb-2">
                                        <i class="bi bi-calendar3 me-2"></i> {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                                    </div>
                                    <h5 class="card-title fw-bold">
                                        <a href="{{ route('edukasi.show', $item->slug) }}" class="text-decoration-none text-dark hover-su">{{ $item->judul }}</a>
                                    </h5>
                                    <p class="card-text text-muted small mb-4 flex-grow-1">
                                        {{ Str::limit(strip_tags($item->isi), 100) }}
                                    </p>
                                    <a href="{{ route('edukasi.show', $item->slug) }}" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-semibold mt-auto">
                                        Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-5">
                    {{ $edukasis->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 text-center py-5 px-4 bg-white">
                    <i class="bi bi-journal-x text-success display-1 mb-3"></i>
                    <h4 class="fw-bold text-dark">Artikel Belum Tersedia</h4>
                    <p class="text-muted mb-4 opacity-75">Maaf, kata kunci atau topik edukasi yang Anda cari belum diterbitkan.</p>
                    <a href="{{ route('edukasi.index') }}" class="btn btn-success rounded-pill px-4">Reset Pencarian</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>