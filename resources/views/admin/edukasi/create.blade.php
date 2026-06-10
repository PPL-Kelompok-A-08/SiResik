@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru - Admin SiResik')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-success fw-bold">Tulis Artikel Edukasi</h1>
            <p class="text-muted">Kemukakan materi informatif edukasi pelestarian lingkungan bagi warga.</p>
        </div>
        <a href="{{ route('admin.edukasi.index') }}" class="btn btn-outline-success rounded-pill px-4">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.edukasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Judul -->
                    <div class="col-md-8 mb-4">
                        <label for="judul" class="form-label fw-bold text-dark">Judul Artikel <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Contoh: Manfaat Memilah Sampah Plastik Rumah Tangga" value="{{ old('judul') }}">
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="col-md-4 mb-4">
                        <label for="kategori" class="form-label fw-bold text-dark">Kategori Artikel <span class="text-danger">*</span></label>
                        <input list="kategori-list" name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" placeholder="Pilih / Ketik" value="{{ old('kategori') }}">
                        <datalist id="kategori-list">
                            @foreach($kategoriDefault as $kat)
                                <option value="{{ $kat }}">
                            @endforeach
                        </datalist>
                        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Unggah Cover -->
                <div class="mb-4">
                    <label for="gambar" class="form-label fw-bold text-dark">Gambar Sampul/Cover <span class="text-danger">*</span></label>
                    <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror" onchange="previewImage(this)">
                    <div class="form-text small opacity-75">Tipe data: JPEG, PNG, JPG, WEBP. Maks: 2MB.</div>
                    @error('gambar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div id="prev-area" class="mt-3 d-none">
                        <img id="image-preview" src="#" alt="Pratinjau" class="rounded border shadow-sm" style="max-height: 200px; object-fit: cover;">
                    </div>
                </div>

                <!-- Konten Lengkap -->
                <div class="mb-4">
                    <label for="isi" class="form-label fw-bold text-dark">Konten Lengkap Artikel <span class="text-danger">*</span></label>
                    <textarea name="isi" id="isi" rows="10" class="form-control @error('isi') is-invalid @enderror" placeholder="Ketik isi lengkap artikel edukasi lingkungan di sini...">{{ old('isi') }}</textarea>
                    @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-4">
                    <button type="submit" class="btn btn-success rounded-pill px-5">Terbitkan Artikel</button>
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