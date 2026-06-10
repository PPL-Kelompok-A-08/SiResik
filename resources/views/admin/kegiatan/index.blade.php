<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Kelola Kegiatan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">Kelola Kegiatan Lingkungan (Admin)</h2>
            <p class="text-muted small">Monitor absensi dan kapasitas kuota gotong royong warga.</p>
        </div>
        <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-success font-weight-bold">+ Buat Kegiatan Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm bg-white overflow-hidden rounded-lg">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="py-3 px-4">Nama Kegiatan</th>
                    <th class="py-3">Lokasi</th>
                    <th class="py-3">Tanggal</th>
                    <th class="py-3 text-center">Rekap Peserta</th>
                    <th class="py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatan as $item)
                    <tr>
                        <td class="px-4"><b>{{ $item->judul }}</b></td>
                        <td>{{ $item->lokasi }}</td>
                        <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                        <td class="text-center"><span class="badge badge-info px-2.5 py-1.5">{{ $item->pendaftaran_count }} / {{ $item->kuota }} Terdaftar</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.kegiatan.show', $item->id) }}" class="btn btn-sm btn-light border">Absensi</a>
                            <form action="{{ route('admin.kegiatan.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus agenda?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>