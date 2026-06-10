<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Kelola Pengumuman</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">Manajemen Mading Broadcast (Admin)</h2>
            <p class="text-muted small">Kelola siaran pengumuman operasional berkala SiResik.</p>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-success font-weight-bold">+ Siarkan Berita Anyar</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-xs rounded">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm bg-white overflow-hidden rounded-lg">
        <table class="table table-hover mb-0 align-middle small">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="py-3 px-4">Headline Materi</th>
                    <th class="py-3">Tanggal Rilis</th>
                    <th class="py-3 text-center">Aksi / Kontrol</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengumuman as $item)
                    <tr>
                        <td class="px-4 py-3"><b>{{ $item->judul }}</b></td>
                        <td class="py-3">{{ $item->tanggal_publish->format('d/m/Y') }}</td>
                        <td class="text-center py-3">
                            <a href="{{ route('admin.pengumuman.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.pengumuman.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus Berita?')">Hapus</button>
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