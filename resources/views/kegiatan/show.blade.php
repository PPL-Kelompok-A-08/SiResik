<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kegiatan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <a href="{{ route('kegiatan.index') }}" class="btn btn-light btn-sm shadow-xs mb-4">← Kembali</a>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <span class="badge badge-success align-self-start mb-2">AGENDA SIRESIK</span>
                <h2 class="font-weight-bold text-dark">{{ $kegiatan->judul }}</h2>
                <hr>
                <h5 class="font-weight-bold text-success mb-2">Deskripsi Pelaksanaan:</h5>
                <p class="text-muted font-medium leading-relaxed" style="white-space: pre-line;">{{ $kegiatan->deskripsi }}</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-white p-4">
                <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3">Rincian Tiket</h5>
                <p class="small text-secondary">📅 <b>Tanggal:</b> {{ $kegiatan->tanggal->format('d F Y') }}</p>
                <p class="small text-secondary">📍 <b>Titik Kumpul:</b> {{ $kegiatan->lokasi }}</p>
                <p class="small text-secondary">👥 <b>Kapasitas Tersedia:</b> {{ $kegiatan->sisaKuota() }} Kursi Sisa</p>
                <hr>
                @auth
                    @if($sudahDaftar)
                        <button class="btn btn-secondary btn-block disabled font-weight-bold" disabled>✓ Anda Sudah Terdaftar</button>
                    @elseif($kegiatan->isPenuh())
                        <button class="btn btn-danger btn-block disabled font-weight-bold" disabled>Kuota Penuh</button>
                    @else
                        <form action="{{ route('kegiatan.daftar', $kegiatan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block font-weight-bold" onclick="return confirm('Apakah Anda berkomitmen menghadiri kegiatan ini?')">Daftar Sekarang</button>
                        </form>
                    @endif
                @else
                    <div class="alert alert-warning text-center small p-2">Anda disimulasikan sebagai Guest. Silakan gunakan bypass Controller jika ingin mencoba mendaftar tanpa auth.</div>
                @endauth
            </div>
        </div>
    </div>
</div>

</body>
</html>