<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Berita SiResik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h1 class="font-weight-bold text-success">Papan Berita SiResik</h1>
            <p class="text-muted">Simak broadcast pengumuman jadwal operasional, perubahan armada, dan informasi penting lainnya.</p>
        </div>
        <div class="col-md-4 text-md-right">
            <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-success">📅 Lihat Kegiatan Lingkungan</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9 mx-auto">
            @forelse($pengumuman as $item)
                <div class="card p-4 mb-4 border-0 shadow-sm bg-white rounded-lg">
                    <div class="d-flex justify-content-between small text-muted">
                        <span class="badge badge-success px-2 py-1 text-white">📢 Siaran Publik</span>
                        <span>Terbit: {{ $item->tanggal_publish->format('d/m/Y') }}</span>
                    </div>
                    <h4 class="font-weight-bold mt-3">
                        <a href="{{ route('pengumuman.show', $item->id) }}" class="text-dark text-decoration-none hover:text-success">{{ $item->judul }}</a>
                    </h4>
                    <p class="text-muted small mt-2">{{ Str::limit(strip_tags($item->isi), 150) }}</p>
                    <div class="text-right">
                        <a href="{{ route('pengumuman.show', $item->id) }}" class="text-success font-weight-bold small text-decoration-none">Baca Selengkapnya →</a>
                    </div>
                </div>
            @empty
                <div class="card p-5 text-center border-0 shadow-sm bg-white rounded-lg">
                    <p class="text-muted mb-0">Mading digital bersih, belum ada pengumuman baru saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

</body>
</html>