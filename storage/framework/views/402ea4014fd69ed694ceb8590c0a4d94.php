<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="relative min-h-[420px] overflow-hidden rounded-[2rem] border border-slate-200 bg-emerald-50/40 shadow-inner lg:min-h-[520px]" id="peta-wrap">
    <div id="peta-map" class="absolute inset-0 z-0 h-full w-full"></div>
</div>

<script>
(function () {
    const titikData = <?php echo json_encode($titikLayanan, 15, 512) ?>;
    const bandung = [-6.9175, 107.6191];

    const jenisMeta = {
        tps: { label: 'TPS', color: '#16a34a' },
        bank_sampah: { label: 'Bank Sampah', color: '#2563eb' },
        usulan: { label: 'Usulan baru', color: '#d97706' },
    };

    function escapeHtml(s) {
        if (s === null || s === undefined) return '';
        const d = document.createElement('div');
        d.textContent = String(s);
        return d.innerHTML;
    }

    const map = L.map('peta-map', { scrollWheelZoom: true }).setView(bandung, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    const markers = [];
    const layerGroup = L.layerGroup().addTo(map);

    function makeIcon(jenis) {
        const c = (jenisMeta[jenis] || jenisMeta.tps).color;
        return L.divIcon({
            className: 'siresik-pin',
            html: '<div style="width:18px;height:18px;border-radius:9999px;background:' + c + ';border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.25);"></div>',
            iconSize: [22, 22],
            iconAnchor: [11, 11],
        });
    }

    function buildPopup(row) {
        const meta = jenisMeta[row.jenis] || jenisMeta.tps;
        const jam = row.jam_operasional ? escapeHtml(row.jam_operasional) : '<span class="text-slate-400">—</span>';
        const sampah = row.jenis_sampah_diterima ? escapeHtml(row.jenis_sampah_diterima) : '<span class="text-slate-400">—</span>';
        return (
            '<div class="min-w-[220px] max-w-[280px] text-slate-800">' +
            '<p class="text-xs font-bold uppercase tracking-wider text-emerald-700">' + escapeHtml(meta.label) + '</p>' +
            '<p class="mt-1 text-base font-bold leading-snug">' + escapeHtml(row.nama) + '</p>' +
            '<p class="mt-2 text-sm text-slate-600">' + escapeHtml(row.alamat) + '</p>' +
            '<p class="mt-3 text-xs font-semibold text-slate-500">Jam operasional</p>' +
            '<p class="text-sm">' + jam + '</p>' +
            '<p class="mt-2 text-xs font-semibold text-slate-500">Jenis sampah</p>' +
            '<p class="text-sm">' + sampah + '</p>' +
            '</div>'
        );
    }

    function renderMarkers(list) {
        layerGroup.clearLayers();
        markers.length = 0;
        list.forEach(function (row) {
            const m = L.marker([row.latitude, row.longitude], { icon: makeIcon(row.jenis) })
                .bindPopup(buildPopup(row), { maxWidth: 320 });
            m.addTo(layerGroup);
            markers.push({ row: row, marker: m });
        });
        const layers = layerGroup.getLayers();
        if (layers.length === 0) {
            map.setView(bandung, 13);
        } else if (layers.length === 1) {
            map.setView([list[0].latitude, list[0].longitude], 15);
        } else {
            const g = L.featureGroup(layers);
            map.fitBounds(g.getBounds().pad(0.15));
        }
    }

    renderMarkers(titikData);

    const searchInput = document.getElementById('cari-lokasi');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            if (!q) {
                renderMarkers(titikData);
                return;
            }
            const filtered = titikData.filter(function (row) {
                const n = (row.nama || '').toLowerCase();
                const a = (row.alamat || '').toLowerCase();
                return n.includes(q) || a.includes(q);
            });
            renderMarkers(filtered);
        });
    }

    let userMarker = null;
    const btnLoc = document.getElementById('peta-lokasi-saya');
    if (btnLoc && navigator.geolocation) {
        btnLoc.addEventListener('click', function () {
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    map.setView([lat, lng], 15);
                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }
                    userMarker = L.circleMarker([lat, lng], {
                        radius: 8,
                        color: '#0c5b49',
                        fillColor: '#34d399',
                        fillOpacity: 0.35,
                        weight: 2,
                    }).addTo(map);
                    userMarker.bindPopup('Lokasi perkiraan Anda').openPopup();
                },
                function () {
                    alert('Izin lokasi ditolak atau tidak tersedia.');
                }
            );
        });
    }
})();
</script>
<?php /**PATH C:\Users\Raffi\Documents\GitHub\SiResik\resources\views/peta/_leaflet-map.blade.php ENDPATH**/ ?>