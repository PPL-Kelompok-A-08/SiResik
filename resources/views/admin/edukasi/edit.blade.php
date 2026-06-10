@extends('layouts.admin')

@section('title', 'Ubah Artikel Edukasi - Admin SiResik')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-success fw-bold">Ubah Artikel Edukasi</h1>
            <p class="text-muted">Perbaiki data artikel agar informasi tetap akurat dan relevan.</p>
        </div>
        <a href="{{ route('admin.edukasi.index') }}" class="btn btn-outline-success rounded-pill px-4">Batal</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.edukasi.update', $edukasi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8 mb-4">
                        <label for="judul" class="form-label fw-bold text-dark">Judul Artikel <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $edukasi->judul) }}">
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="kategori" class="form-label fw-bold text-dark">Kategori Artikel <span class="text-danger">*</span></label>
                        <input list="kategori-list" name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori', $edukasi->kategori) }}">
                        <datalist id="kategori-list">
                            @foreach($kategoriDefault as $kat)
                                <option value="{{ $kat }}">
                            @endforeach
                        </datalist>
                        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold d-block">Gambar Saat Ini:</label>
                        <img src="{{ Str::startsWith($edukasi->gambar, 'http') ? $edukasi->gambar : asset('storage/' . $edukasi->gambar) }}" class="img-fluid rounded border mb-2" style="max-height: 120px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <label for="gambar" class="form-label fw-bold text-dark">Ganti Gambar (Opsional)</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" onchange="previewImage(this)">
                        @error('gambar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <div id="prev-area" class="mt-2 d-none">
                            <span class="text-muted d-block small mb-1">Pratinjau Pengganti:</span>
                            <img id="image-preview" src="#" alt="Pratinjau" class="rounded border shadow-sm" style="max-height: 100px; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="isi" class="form-label fw-bold text-dark">Konten Lengkap Artikel <span class="text-danger">*</span></label>
                    <textarea name="isi" id="isi" rows="10" class="form-control @error('isi') is-invalid @enderror">{{ old('isi', $edukasi->isi) }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-4">
                    <button type="submit" class="btn btn-success rounded-pill px-5">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').setAttribute('src', e.target.result);
                document.getElementById('prev-area').classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection