<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Edukasi - Admin SiResik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container-fluid px-4 py-4">
    <div class="d-md-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-success fw-bold">Konten Edukasi Lingkungan (Admin Space)</h1>
            <p class="text-muted mb-0">Kelola informasi materi edukatif, panduan memilah sampah, dan tips penghijauan SiResik.</p>
        </div>
        <a href="{{ route('admin.edukasi.create') }}" class="btn btn-success rounded-pill px-4 mt-2 mt-md-0">
            <i class="bi bi-plus-circle me-2"></i>Tulis Artikel Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible border-0 shadow-sm rounded-4 fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3">
            <form action="{{ route('admin.edukasi.index') }}" method="GET" class="row">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari judul atau jenis kategori..." value="{{ $search ?? '' }}">
                        <button class="btn btn-success" type="submit">Cari</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th style="width: 120px;">Gambar</th>
                        <th>Kategori & Judul</th>
                        <th>Dibuat Tanggal</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($edukasis->count() > 0)
                        @foreach($edukasis as $idx => $item)
                            <tr>
                                <td class="ps-4 text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <img src="{{ Str::startsWith($item->gambar, 'http') ? $item->gambar : asset('storage/' . $item->gambar) }}" class="rounded shadow-sm border" alt="Cover" style="width: 90px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border rounded-pill mb-1 d-inline-block">{{ $item->kategori }}</span>
                                    <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                </td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }} WIB</td>
                                <td class="text-center">
                                    <div class="btn-group gap-1">
                                        <a href="{{ route('admin.edukasi.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.edukasi.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delModal{{ $item->id }}"><i class="bi bi-trash"></i></button>
                                    </div>

                                    <div class="modal fade text-start" id="delModal{{ $item->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0">
                                                <div class="modal-body text-center p-4">
                                                    <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                                                    <h5 class="fw-bold mt-3">Hapus Artikel?</h5>
                                                    <p class="text-muted small">Anda yakin ingin menghapus materi edukasi <strong>"{{ $item->judul }}"</strong> secara permanen?</p>
                                                    <div class="d-flex justify-content-center gap-2 mt-4">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('admin.edukasi.destroy', $item->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada konten edukasi lingkungan yang tersimpan.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
            {{ $edukasis->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>