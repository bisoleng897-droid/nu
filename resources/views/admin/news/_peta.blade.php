{{--
    Pemilih lokasi untuk admin (Leaflet + OpenStreetMap, tanpa API key).

    Cara pakai:
      $alamat  = teks alamat saat ini (string, boleh kosong)
      $lat     = koordinat latitude saat ini (float|null)
      $lng     = koordinat longitude saat ini (float|null)

    Admin bisa mengetik nama tempat lalu tekan "Cari", atau langsung klik
    di peta. Alamat dan koordinat terisi otomatis dan bisa diedit manual.
--}}
@php
    // Default: sekitar Rancamaya, Cilongok, Banyumas.
    $pickerLat = $lat ?? -7.3849675;
    $pickerLng = $lng ?? 109.1414820;
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha384-sHL9NAb7lN7rfvG5lfHpm643Xkcjzp4jFvuavGOndn6pjVqS6ny56CAt3nsEVT4H" crossorigin="">

<div class="field mt-5 {{ $errors->has('alamat') || $errors->has('lat') || $errors->has('lng') ? 'has-error' : '' }}"
     id="peta-picker">
    <label for="alamat" class="flex items-center gap-2">
        <svg class="w-4 h-4" style="color:#2F6B2F" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Lokasi Kegiatan
    </label>

    {{-- Pencarian tempat --}}
    <div class="flex gap-2">
        <input id="cari-lokasi" type="text" autocomplete="off"
               placeholder="Cari nama tempat, misal: Masjid NU Rancamaya"
               aria-label="Cari nama tempat">
        <button type="button" id="btn-cari-lokasi" class="btn btn-soft btn-sm shrink-0">Cari</button>
    </div>

    {{-- Peta --}}
    <div id="peta" class="mt-2 w-full h-72 rounded-2xl border overflow-hidden" style="border-color:#E5E2D6"></div>

    <div class="flex flex-wrap items-center gap-2 mt-2">
        <button type="button" id="btn-lokasi-saya" class="btn btn-soft btn-sm">Gunakan Lokasi Saya</button>
        <button type="button" id="btn-hapus-lokasi" class="btn btn-ghost btn-sm">Hapus Lokasi</button>
        <span id="status-peta" class="text-xs" style="color:#7D8575" role="status" aria-live="polite"></span>
    </div>

    {{-- Alamat hasil pencarian / klik peta, tetap bisa diedit manual --}}
    <input id="alamat" type="text" name="alamat" value="{{ old('alamat', $alamat) }}"
           maxlength="500"
           placeholder="Contoh: Masjid NU Rancamaya, Rancamaya, Cilongok, Banyumas"
           class="mt-3">

    <input type="hidden" name="lat" id="lat" value="{{ old('lat', $lat) }}">
    <input type="hidden" name="lng" id="lng" value="{{ old('lng', $lng) }}">

    <p class="hint">
        Klik lokasi di peta atau cari nama tempat — alamat dan pin terisi otomatis.
        Peta dipakai untuk menampilkan lokasi di halaman berita. Kosongkan bila berita ini tidak punya lokasi.
    </p>
    @error('alamat')
        <p class="error-text">{{ $message }}</p>
    @enderror
    @error('lat')
        <p class="error-text">{{ $message }}</p>
    @enderror
    @error('lng')
        <p class="error-text">{{ $message }}</p>
    @enderror
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha384-cxOPjt7s7Iz04uaHJceBmS+qpjv2JkIHNVcuOrM+YHwZOmJGBXI00mdUXEq65HTH" crossorigin=""></script>

@push('scripts')
<script>
    (function () {
        const petaEl = document.getElementById('peta');
        if (!petaEl || typeof L === 'undefined') {
            return;
        }

        // Default: sekitar Rancamaya, Cilongok, Banyumas.
        const DEFAULT_LAT = @json($pickerLat);
        const DEFAULT_LNG = @json($pickerLng);

        const alamatEl = document.getElementById('alamat');
        const latEl = document.getElementById('lat');
        const lngEl = document.getElementById('lng');
        const cariEl = document.getElementById('cari-lokasi');
        const statusEl = document.getElementById('status-peta');

        const map = L.map(petaEl).setView([DEFAULT_LAT, DEFAULT_LNG], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);

        const marker = L.marker([DEFAULT_LAT, DEFAULT_LNG], { draggable: true }).addTo(map);

        function status(pesan, warna) {
            statusEl.textContent = pesan;
            statusEl.style.color = warna || '#7D8575';
        }

        function isiLokasi(lat, lng, label) {
            latEl.value = Number(lat).toFixed(7);
            lngEl.value = Number(lng).toFixed(7);
            if (label) {
                alamatEl.value = label;
            }
            status('Lokasi dipilih.');
        }

        // Susun alamat ringkas dari hasil reverse geocoding Nominatim.
        function susunAlamat(a, displayName) {
            if (!a) {
                return displayName || '';
            }
            return [
                a.house_number,
                a.road,
                a.suburb || a.neighbourhood || a.quarter,
                a.city || a.town || a.village || a.municipality || a.county,
                a.state,
                a.country_code === 'id' ? 'Indonesia' : a.country,
            ].filter(Boolean).join(', ') || displayName || '';
        }

        function cariTempat(query) {
            if (!query) {
                return;
            }

            status('Mencari lokasi...');

            fetch('https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=' + encodeURIComponent(query))
                .then((res) => res.json())
                .then((hasil) => {
                    if (!hasil.length) {
                        status('Lokasi tidak ditemukan. Coba nama yang lebih umum.', '#B91C1C');
                        return;
                    }
                    const tempat = hasil[0];
                    map.setView([tempat.lat, tempat.lon], 17);
                    marker.setLatLng([tempat.lat, tempat.lon]);
                    isiLokasi(tempat.lat, tempat.lon, tempat.display_name);
                })
                .catch(() => status('Gagal menghubungi server peta. Cek koneksi internet.', '#B91C1C'));
        }

        function ambilAlamat(lat, lng) {
            fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&zoom=18&addressdetails=1&lat=' + lat + '&lon=' + lng)
                .then((res) => res.json())
                .then((res) => isiLokasi(lat, lng, susunAlamat(res.address, res.display_name)))
                .catch(() => isiLokasi(lat, lng, ''));
        }

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            status('Menerjemahkan titik...');
            ambilAlamat(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            status('Menerjemahkan titik...');
            ambilAlamat(pos.lat, pos.lng);
        });

        cariEl.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cariTempat(cariEl.value.trim());
            }
        });

        document.getElementById('btn-cari-lokasi').addEventListener('click', function () {
            cariTempat(cariEl.value.trim());
        });

        document.getElementById('btn-lokasi-saya').addEventListener('click', function () {
            if (!navigator.geolocation) {
                status('Browser ini tidak mendukung pengambilan lokasi.', '#B91C1C');
                return;
            }

            status('Mendeteksi lokasi Anda...');

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    map.setView([lat, lng], 17);
                    marker.setLatLng([lat, lng]);
                    ambilAlamat(lat, lng);
                },
                function () {
                    status('Gagal mendeteksi lokasi. Pastikan izin lokasi diizinkan.', '#B91C1C');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });

        document.getElementById('btn-hapus-lokasi').addEventListener('click', function () {
            marker.setLatLng([DEFAULT_LAT, DEFAULT_LNG]);
            map.setView([DEFAULT_LAT, DEFAULT_LNG], 15);
            latEl.value = '';
            lngEl.value = '';
            cariEl.value = '';
            status('Lokasi dikosongkan.');
        });

        // Admin yang mengetik atau menghapus alamat sendiri ikut membersihkan pin.
        alamatEl.addEventListener('input', function () {
            if (!alamatEl.value.trim()) {
                latEl.value = '';
                lngEl.value = '';
            }
        });

        // Tampilan peta dirapikan lagi setelah layout selesai dimuat.
        setTimeout(function () { map.invalidateSize(); }, 200);
    })();
</script>
@endpush