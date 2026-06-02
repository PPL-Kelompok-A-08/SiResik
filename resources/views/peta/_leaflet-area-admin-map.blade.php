<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-inner">
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-4xl font-black tracking-tight text-slate-900">Visualisasi Zona Layanan</h2>
                <p class="mt-1 text-lg text-slate-500">Poligon zona layanan dan titik layanan yang berada di dalamnya.</p>
            </div>
            <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center lg:w-auto">
                <label class="relative flex-1 min-w-[260px]">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">⌕</span>
                    <input type="search" id="cari-lokasi" autocomplete="off" placeholder="Cari nama atau alamat..."
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-base text-slate-800 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                </label>
                <button type="button" id="peta-lokasi-saya"
                    class="whitespace-nowrap rounded-2xl border border-emerald-600 bg-emerald-50 px-5 py-3 text-base font-semibold text-emerald-900 transition hover:bg-emerald-100">
                    Lokasi saya
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[280px,1fr]">
        <aside class="border-b border-slate-200 bg-white px-5 py-5 lg:border-b-0 lg:border-r">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-500">Zona Layanan</p>
                    <p class="mt-1 text-xl font-black tracking-tight text-slate-800">Area Cakupan</p>
                </div>
                <button type="button" id="zona-reset"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Semua
                </button>
            </div>

            <div id="zona-list" class="mt-4 space-y-2">
                @forelse (($zonaLayanan ?? []) as $z)
                    <div class="flex items-stretch gap-2">
                        <button type="button"
                            class="zona-select flex w-full items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left transition hover:bg-slate-50"
                            data-zona-id="{{ $z->id }}"
                            data-zona-nama="{{ $z->nama }}"
                            data-zona-warna="{{ $z->warna }}"
                            data-zona-update="{{ route('admin.zona-layanan.update', $z) }}">
                            <span class="flex items-center gap-3">
                                <span class="h-4 w-4 rounded-full" style="background: {{ $z->warna }}"></span>
                                <span class="font-bold text-slate-800">{{ $z->nama }}</span>
                            </span>
                            <span class="zona-count rounded-xl bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600" data-count-for="{{ $z->id }}">0</span>
                        </button>

                        <button type="button"
                            class="zona-edit shrink-0 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-100"
                            title="Edit zona"
                            data-zona-id="{{ $z->id }}"
                            data-zona-nama="{{ $z->nama }}"
                            data-zona-warna="{{ $z->warna }}"
                            data-zona-update="{{ route('admin.zona-layanan.update', $z) }}">
                            ✎
                        </button>

                        <form method="POST" action="{{ route('admin.zona-layanan.destroy', $z) }}"
                            onsubmit="return confirm('Hapus zona ini?')"
                            class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="rounded-2xl border border-red-200 bg-red-50 px-3 py-3 text-sm font-bold text-red-700 transition hover:bg-red-100"
                                title="Hapus zona">
                                ✕
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-sm text-slate-500">
                        Belum ada zona layanan. Klik <span class="font-semibold text-slate-700">+ Tambah Area</span> untuk membuat zona baru.
                    </div>
                @endforelse
            </div>

            <div class="mt-6 border-t border-slate-200 pt-5">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Keterangan</p>
                <div class="mt-3 space-y-2 text-sm font-semibold text-slate-700">
                    <div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-green-600"></span> TPS</div>
                    <div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-blue-600"></span> Bank Sampah</div>
                    <div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-amber-600"></span> Usulan baru</div>
                </div>
            </div>

            <div class="mt-6 border-t border-slate-200 pt-5">
                <div class="flex items-end justify-between gap-3">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Titik Dalam Zona</p>
                    <p class="text-xs font-semibold text-slate-400" id="zona-points-count">0</p>
                </div>
                <div id="zona-points" class="mt-3 max-h-[220px] space-y-2 overflow-auto pr-1 text-sm text-slate-700">
                    <p class="text-sm text-slate-500">Pilih zona untuk melihat titik layanan di dalamnya.</p>
                </div>
            </div>
        </aside>

        <div class="relative min-h-[420px] bg-emerald-50/40 lg:min-h-[560px]">
            <div id="peta-area-map" class="absolute inset-0 z-0 h-full w-full"></div>
        </div>
    </div>
</div>

<script>
(function () {
    // Exposed initializer for the "Tambah Area" modal (lives on dashboard page).
    window.__initZonaRadiusMap = function () {};

    const titikData = @json($titikLayanan);
    const usulanData = @json($usulanMenunggu ?? []);
    const zonaData = @json($zonaLayanan ?? []);
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

    const map = L.map('peta-area-map', { scrollWheelZoom: true }).setView(bandung, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    const markerLayer = L.layerGroup().addTo(map);
    const zonaLayer = L.layerGroup().addTo(map);
    const labelLayer = L.layerGroup().addTo(map);

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

    function pointInRing(lat, lng, ringLngLat) {
        // Ray casting. ringLngLat: [[lng,lat], ...]
        let inside = false;
        for (let i = 0, j = ringLngLat.length - 1; i < ringLngLat.length; j = i++) {
            const xi = ringLngLat[i][0], yi = ringLngLat[i][1];
            const xj = ringLngLat[j][0], yj = ringLngLat[j][1];
            const intersect = ((yi > lat) !== (yj > lat)) &&
                (lng < (xj - xi) * (lat - yi) / ((yj - yi) || 1e-12) + xi);
            if (intersect) inside = !inside;
        }
        return inside;
    }

    function pointInGeometry(lat, lng, geom) {
        if (!geom || !geom.type || !geom.coordinates) return false;
        if (geom.type === 'Polygon') {
            const outer = geom.coordinates[0] || [];
            return pointInRing(lat, lng, outer);
        }
        if (geom.type === 'MultiPolygon') {
            return geom.coordinates.some(function (poly) {
                const outer = (poly && poly[0]) ? poly[0] : [];
                return pointInRing(lat, lng, outer);
            });
        }
        return false;
    }

    const zonaMembers = {}; // zonaId -> rows
    const combinedPoints = titikData.concat(
        (usulanData || []).map(function (u) {
            const jenisLayananLabel = u.jenis_layanan === 'bank_sampah' ? 'Bank Sampah' : 'TPS';
            return {
                id: 'usulan-' + String(u.id),
                nama: 'Usulan ' + jenisLayananLabel,
                jenis: 'usulan',
                latitude: u.latitude,
                longitude: u.longitude,
                alamat: u.alamat_detail,
                jam_operasional: null,
                jenis_sampah_diterima: null,
                _usulan: {
                    alasan: u.deskripsi_alasan,
                    pengusul: (u.pengusul && u.pengusul.name) ? u.pengusul.name : null,
                    created_at: u.created_at || null,
                },
            };
        })
    );

    function computeZonaMembers() {
        zonaMembers.__all = combinedPoints.slice();
        zonaData.forEach(function (z) {
            const geom = z.geojson;
            const members = combinedPoints.filter(function (t) {
                return pointInGeometry(Number(t.latitude), Number(t.longitude), geom);
            });
            zonaMembers[String(z.id)] = members;
        });
    }

    function renderMarkers(list) {
        markerLayer.clearLayers();
        const layers = [];
        list.forEach(function (row) {
            const m = L.marker([row.latitude, row.longitude], { icon: makeIcon(row.jenis) })
                .bindPopup(buildPopup(row), { maxWidth: 320 });
            m.addTo(markerLayer);
            layers.push(m);
        });

        if (layers.length === 0) {
            map.setView(bandung, 14);
        } else if (layers.length === 1) {
            map.setView([list[0].latitude, list[0].longitude], 16);
        } else {
            const g = L.featureGroup(layers);
            map.fitBounds(g.getBounds().pad(0.15));
        }
    }

    function buildZonaLabel(zona, bounds) {
        const center = bounds.getCenter();
        return L.marker(center, {
            interactive: false,
            icon: L.divIcon({
                className: '',
                html: '<div style="padding:2px 10px;border-radius:9999px;background:rgba(255,255,255,.85);border:1px solid rgba(15,23,42,.15);font-weight:800;font-size:12px;color:#0f172a;box-shadow:0 1px 6px rgba(0,0,0,.12)">' + escapeHtml(zona.nama) + '</div>',
            }),
        });
    }

    const zonaLayersById = {};

    function renderZonaLayers() {
        zonaLayer.clearLayers();
        labelLayer.clearLayers();
        Object.keys(zonaLayersById).forEach(function (k) { delete zonaLayersById[k]; });

        zonaData.forEach(function (z) {
            const layer = L.geoJSON(z.geojson, {
                style: function () {
                    return {
                        color: z.warna || '#16a34a',
                        fillColor: z.warna || '#16a34a',
                        fillOpacity: 0.22,
                        weight: 2,
                        dashArray: '6 6',
                    };
                },
            });
            layer.addTo(zonaLayer);
            zonaLayersById[String(z.id)] = layer;

            try {
                const b = layer.getBounds();
                buildZonaLabel(z, b).addTo(labelLayer);
            } catch (e) {}
        });
    }

    function zonaRowHtml(z, count, selected) {
        const active = selected ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 bg-white hover:bg-slate-50';
        return (
            '<button type="button" data-zona-id="' + String(z.id) + '" class="zona-item flex w-full items-center justify-between gap-3 rounded-2xl border px-4 py-3 text-left transition ' + active + '">' +
            '<span class="flex items-center gap-3">' +
                '<span class="h-4 w-4 rounded-full" style="background:' + escapeHtml(z.warna || '#16a34a') + '"></span>' +
                '<span class="font-bold text-slate-800">' + escapeHtml(z.nama) + '</span>' +
            '</span>' +
            '<span class="rounded-xl bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">' + String(count) + '</span>' +
            '</button>'
        );
    }

    let state = { zonaId: null, query: '' };

    function currentZonaList() {
        if (!state.zonaId) return titikData;
        return zonaMembers[String(state.zonaId)] || [];
    }

    function applyFilter() {
        let base = currentZonaList();
        const q = (state.query || '').trim().toLowerCase();
        if (q) {
            base = base.filter(function (row) {
                const n = (row.nama || '').toLowerCase();
                const a = (row.alamat || '').toLowerCase();
                return n.includes(q) || a.includes(q);
            });
        }
        renderMarkers(base);
    }

    function updateZonaSidebar() {
        const listEl = document.getElementById('zona-list');
        if (!listEl) return;
        const html = zonaData.length
            ? zonaData.map(function (z) {
                const members = zonaMembers[String(z.id)] || [];
                return zonaRowHtml(z, members.length, String(state.zonaId) === String(z.id));
            }).join('')
            : '<div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-sm text-slate-500">Belum ada zona layanan. Klik <span class="font-semibold text-slate-700">+Tambah Area</span> untuk membuat zona baru.</div>';
        listEl.innerHTML = html;

        listEl.querySelectorAll('.zona-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-zona-id');
                state.zonaId = id;
                highlightZona(id);
                updateZonaSidebar();
                updateZonaPoints();
                applyFilter();
            });
        });
    }

    function updateZonaPoints() {
        const pointsEl = document.getElementById('zona-points');
        const countEl = document.getElementById('zona-points-count');
        if (!pointsEl || !countEl) return;

        if (!state.zonaId) {
            countEl.textContent = '0';
            pointsEl.innerHTML = '<p class="text-sm text-slate-500">Pilih zona untuk melihat titik layanan di dalamnya.</p>';
            return;
        }

        const rows = (zonaMembers[String(state.zonaId)] || []).slice().sort(function (a, b) {
            return String(a.nama || '').localeCompare(String(b.nama || ''), 'id');
        });

        countEl.textContent = String(rows.length);
        if (rows.length === 0) {
            pointsEl.innerHTML = '<p class="text-sm text-slate-500">Belum ada titik layanan di zona ini.</p>';
            return;
        }

        pointsEl.innerHTML = rows.map(function (r) {
            const meta = jenisMeta[r.jenis] || jenisMeta.tps;
            return (
                '<button type="button" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-left transition hover:bg-slate-50" data-focus-lat="' + String(r.latitude) + '" data-focus-lng="' + String(r.longitude) + '">' +
                    '<div class="flex items-center justify-between gap-3">' +
                        '<span class="font-bold text-slate-800">' + escapeHtml(r.nama) + '</span>' +
                        '<span class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600">' +
                            '<span class="h-2.5 w-2.5 rounded-full" style="background:' + escapeHtml(meta.color) + '"></span>' +
                            escapeHtml(meta.label) +
                        '</span>' +
                    '</div>' +
                    '<div class="mt-1 text-xs text-slate-500">' + escapeHtml(r.alamat || '') + '</div>' +
                '</button>'
            );
        }).join('');

        pointsEl.querySelectorAll('button[data-focus-lat]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const lat = Number(this.getAttribute('data-focus-lat'));
                const lng = Number(this.getAttribute('data-focus-lng'));
                if (Number.isFinite(lat) && Number.isFinite(lng)) {
                    map.setView([lat, lng], 17);
                }
            });
        });
    }

    function setZonaStyle(layer, active) {
        layer.eachLayer(function (l) {
            if (!l.setStyle) return;
            l.setStyle({
                weight: active ? 4 : 2,
                fillOpacity: active ? 0.28 : 0.22,
                dashArray: active ? null : '6 6',
            });
        });
    }

    function highlightZona(id) {
        Object.keys(zonaLayersById).forEach(function (k) {
            setZonaStyle(zonaLayersById[k], String(k) === String(id));
        });
        const activeLayer = zonaLayersById[String(id)];
        if (activeLayer) {
            try { map.fitBounds(activeLayer.getBounds().pad(0.2)); } catch (e) {}
        }
    }

    // Initial render
    computeZonaMembers();
    renderZonaLayers();
    renderMarkers(combinedPoints);
    // counts + selection style
    document.querySelectorAll('.zona-count[data-count-for]').forEach(function (el) {
        const id = el.getAttribute('data-count-for');
        const members = zonaMembers[String(id)] || [];
        el.textContent = String(members.length);
    });
    updateZonaPoints();

    // Sidebar reset
    const resetBtn = document.getElementById('zona-reset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            state.zonaId = null;
            highlightZona('__none__');
            syncZonaSelection();
            updateZonaPoints();
            applyFilter();
        });
    }

    function syncZonaSelection() {
        document.querySelectorAll('.zona-select[data-zona-id]').forEach(function (btn) {
            const id = btn.getAttribute('data-zona-id');
            const active = state.zonaId && String(state.zonaId) === String(id);
            btn.classList.toggle('border-emerald-500', !!active);
            btn.classList.toggle('bg-emerald-50', !!active);
        });
    }

    document.querySelectorAll('.zona-select[data-zona-id]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-zona-id');
            state.zonaId = id;
            highlightZona(id);
            syncZonaSelection();
            updateZonaPoints();
            applyFilter();
        });
    });

    // Edit zona -> reuse modalTambahArea on dashboard page (switch to PUT)
    document.querySelectorAll('.zona-edit[data-zona-update]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const { modal, form, warnaInput, warnaText, geoInput } = getModalEls();
            if (!modal || !form) return;

            const nama = btn.getAttribute('data-zona-nama') || '';
            const warna = btn.getAttribute('data-zona-warna') || '#16a34a';
            const updateUrl = btn.getAttribute('data-zona-update') || '';

            // open modal via existing dashboard modal system if present
            if (typeof openModal === 'function') {
                openModal('modalTambahArea');
            } else {
                modal.classList.add('open');
            }

            const namaInput = form.querySelector('input[name="nama"]');
            if (namaInput) namaInput.value = nama;
            if (warnaInput) warnaInput.value = warna;
            if (warnaText) warnaText.value = warna;

            form.action = updateUrl;
            // add _method=PUT if not exists
            let method = form.querySelector('input[name="_method"]');
            if (!method) {
                method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                form.appendChild(method);
            }
            method.value = 'PUT';

            // allow update without changing geojson; keep empty unless user reselects
            if (geoInput) geoInput.value = '';

            setTimeout(function () {
                if (window.__initZonaRadiusMap) window.__initZonaRadiusMap();
            }, 160);
        });
    });

    // Search input (shared id across pages)
    const searchInput = document.getElementById('cari-lokasi');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            state.query = this.value || '';
            applyFilter();
        });
    }

    // Locate me button (shared id across pages)
    let userMarker = null;
    const btnLoc = document.getElementById('peta-lokasi-saya');
    if (btnLoc && navigator.geolocation) {
        btnLoc.addEventListener('click', function () {
            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    map.setView([lat, lng], 16);
                    if (userMarker) map.removeLayer(userMarker);
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

    // Modal tambah area (lazily initialized when modal is opened)
    const mapWrapId = 'zona-form-map';

    let modalMap = null;
    let modalHandlersBound = false;
    let areaCenterMarker = null;
    let areaPreviewPolygon = null;

    function getModalEls() {
        const modal = document.getElementById('modalTambahArea');
        const closeModalBtn = document.getElementById('btnTutupModalArea');
        const geoInput = document.getElementById('zona-geojson');
        const warnaInput = document.getElementById('zona-warna');
        const warnaText = document.getElementById('zona-warna-text');
        const form = document.getElementById('formTambahArea');
        const mapEl = document.getElementById(mapWrapId);
        const radiusKm = document.getElementById('zona-radius-km');
        const radiusPresets = document.querySelectorAll('.zona-radius-preset');
        const centerLat = document.getElementById('zona-center-lat');
        const centerLng = document.getElementById('zona-center-lng');
        return {
            modal,
            closeModalBtn,
            geoInput,
            warnaInput,
            warnaText,
            form,
            mapEl,
            radiusKm,
            radiusPresets,
            centerLat,
            centerLng,
        };
    }

    function deg2rad(d) { return d * Math.PI / 180; }
    function rad2deg(r) { return r * 180 / Math.PI; }

    function circleToPolygon(lat, lng, radiusMeters, steps) {
        const R = 6378137;
        const d = radiusMeters / R;
        const lat1 = deg2rad(lat);
        const lng1 = deg2rad(lng);
        const ring = [];
        const n = steps || 64;

        for (let i = 0; i < n; i++) {
            const brng = 2 * Math.PI * i / n;
            const lat2 = Math.asin(Math.sin(lat1) * Math.cos(d) + Math.cos(lat1) * Math.sin(d) * Math.cos(brng));
            const lng2 = lng1 + Math.atan2(
                Math.sin(brng) * Math.sin(d) * Math.cos(lat1),
                Math.cos(d) - Math.sin(lat1) * Math.sin(lat2)
            );
            ring.push([rad2deg(lng2), rad2deg(lat2)]);
        }
        // close ring
        ring.push(ring[0].slice());
        return {
            type: 'Polygon',
            coordinates: [ring],
        };
    }

    function updateRadiusGeometry() {
        const { geoInput, radiusKm, centerLat, centerLng } = getModalEls();
        if (!geoInput || !radiusKm || !centerLat || !centerLng) return;
        const km = Number(radiusKm.value);
        const lat = Number(centerLat.value);
        const lng = Number(centerLng.value);
        if (!Number.isFinite(km) || km <= 0 || !Number.isFinite(lat) || !Number.isFinite(lng)) {
            geoInput.value = '';
            if (areaPreviewPolygon && modalMap) { modalMap.removeLayer(areaPreviewPolygon); areaPreviewPolygon = null; }
            return;
        }

        const radiusM = km * 1000;
        const geom = circleToPolygon(lat, lng, radiusM, 72);
        geoInput.value = JSON.stringify(geom);

        if (modalMap) {
            if (areaPreviewPolygon) modalMap.removeLayer(areaPreviewPolygon);
            areaPreviewPolygon = L.geoJSON(geom, {
                style: function () {
                    return { color: '#10b981', fillColor: '#10b981', fillOpacity: 0.15, weight: 2, dashArray: '6 6' };
                },
            }).addTo(modalMap);
            try { modalMap.fitBounds(areaPreviewPolygon.getBounds().pad(0.2)); } catch (e) {}
        }
    }

    function ensureModalMap() {
        if (modalMap) return;
        const { mapEl } = getModalEls();
        if (!mapEl) return;

        modalMap = L.map(mapWrapId, { scrollWheelZoom: true }).setView(bandung, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(modalMap);

        modalMap.on('click', function (e) {
            const { centerLat, centerLng } = getModalEls();
            if (!centerLat || !centerLng) return;
            centerLat.value = String(e.latlng.lat);
            centerLng.value = String(e.latlng.lng);

            if (areaCenterMarker) modalMap.removeLayer(areaCenterMarker);
            areaCenterMarker = L.circleMarker([e.latlng.lat, e.latlng.lng], {
                radius: 7,
                color: '#0c5b49',
                fillColor: '#34d399',
                fillOpacity: 0.5,
                weight: 2,
            }).addTo(modalMap);

            updateRadiusGeometry();
        });
    }

    function bindModalHandlersOnce() {
        if (modalHandlersBound) return;
        modalHandlersBound = true;

        const { form, radiusKm, radiusPresets, warnaInput, warnaText, geoInput, centerLat, centerLng } = getModalEls();

        if (warnaInput && warnaText) {
            warnaText.value = warnaInput.value || '#16a34a';
            warnaInput.addEventListener('input', function () {
                warnaText.value = warnaInput.value;
            });
        }

        if (radiusKm) {
            radiusKm.addEventListener('input', function () {
                updateRadiusGeometry();
            });
        }
        if (radiusPresets && radiusPresets.length) {
            radiusPresets.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const v = Number(btn.getAttribute('data-km'));
                    const { radiusKm } = getModalEls();
                    if (radiusKm && Number.isFinite(v)) {
                        radiusKm.value = String(v);
                        updateRadiusGeometry();
                    }
                });
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                if (geoInput && !geoInput.value) {
                    e.preventDefault();
                    const hasCenter = centerLat && centerLng && centerLat.value && centerLng.value;
                    alert(hasCenter ? 'Silakan isi radius (km) terlebih dahulu.' : 'Silakan klik peta untuk menentukan titik pusat terlebih dahulu.');
                }
            });
        }
    }

    window.__initZonaRadiusMap = function () {
        ensureModalMap();
        bindModalHandlersOnce();
        setTimeout(function () {
            if (modalMap) modalMap.invalidateSize();
        }, 80);
    };
})();
</script>
