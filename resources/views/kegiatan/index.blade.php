<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiResik - Kegiatan Lingkungan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="font-weight-bold text-success">Kegiatan Lingkungan</h1>
            <p class="text-muted">Ayo berpartisipasi menjaga kebersihan dan kelestarian ekosistem bersama SiResik!</p>
        </div>
        <div class="col-md-4 text-md-right">
            <a href="{{ route('pengumuman.index') }}" class="btn btn-outline-success">📢 Lihat Berita & Pengumuman</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-xs rounded">{{ session('success') }}</div>
    @endif

    <div class="row mt-4">
        @forelse($kegiatan as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-lg">
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge badge-success align-self-start mb-2">{{ $item->tanggal->format('d M Y') }}</span>
                        <h5 class="font-weight-bold text-dark mb-2">{{ $item->judul }}</h5>
                        <p class="text-muted small leading-relaxed">{{ Str::limit($item->deskripsi, 110) }}</p>
                        
                        <hr class="my-3 mt-auto">
                        <div class="d-flex justify-content-between text-secondary small mb-3">
                            <span>📍 {{ Str::limit($item->lokasi, 16) }}</span>
                            <span>👥 Sisa Kuota: <b>{{ $item->sisaKuota() }}</b> / {{ $item->kuota }}</span>
                        </div>

                        <a href="{{ route('kegiatan.show', $item->id) }}" class="btn btn-success btn-sm btn-block font-weight-bold shadow-xs">Detail & Ambil Bagian</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white shadow-xs rounded">
                <p class="text-muted mb-0">Belum ada agenda aksi sosial kebersihan lingkungan saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>