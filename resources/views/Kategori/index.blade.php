<!DOCTYPE html>
<html>
<head>
    <title>Kategori Sampah</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Informasi Kategori Sampah</h2>

<div class="row">
    @foreach($kategori as $item)
        <div class="col-md-4">
            <div class="card mb-3 kategori-card" data-id="{{ $item->id }}">
                <div class="card-body">
                    <h5>{{ $item->nama }}</h5>
                    <p>{{ $item->deskripsi }}</p>
                    <p><strong>{{ $item->poin_per_kg }} poin/kg</strong></p>

                    <button class="btn btn-primary btn-detail" data-id="{{ $item->id }}">
                        Pilih
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<hr>

<!-- DETAIL -->
<div id="detailBox" style="display:none;">
    <h3 id="namaKategori"></h3>
    <p id="deskripsiKategori"></p>
    <p><strong id="poinKategori"></strong></p>

    <h5>Pilih Berat</h5>
    <select id="berat" class="form-control w-25">
        <option value="">-- Pilih Berat --</option>
        <option value="1">1 kg</option>
        <option value="2">2 kg</option>
        <option value="3">3 kg</option>
        <option value="5">5 kg</option>
        <option value="10">10 kg</option>
    </select>

    <br>

    <h4>Total Poin:</h4>
    <h3 id="totalPoin">0</h3>
</div>

<script>
let selectedKategori = null;

// Klik kategori
document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function () {
        let card = this.closest('.card');

        selectedKategori = this.dataset.id;

        document.getElementById('namaKategori').innerText =
            card.querySelector('h5').innerText;

        document.getElementById('deskripsiKategori').innerText =
            card.querySelector('p').innerText;

        document.getElementById('poinKategori').innerText =
            card.querySelector('strong').innerText;

        document.getElementById('detailBox').style.display = 'block';
    });
});

// Hitung poin
document.getElementById('berat').addEventListener('change', function () {
    let berat = this.value;

    fetch('/kategori/hitung', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            kategori_id: selectedKategori,
            berat: berat
        })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('totalPoin').innerText = data.total_poin + ' poin';
    });
});
</script>

</body>
</html>