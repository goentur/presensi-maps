@extends('layouts.data')

@push('vendor-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush
@section('content')
<div class="card">
    <div class="card-body justify-align-center text-center">
        @if ($pengaturan)
            <h1>{{ $pengaturan->tipe }}</h1>

            <div class="d-flex flex-column align-items-center">
                <video id="camera-stream" autoplay playsinline class="border rounded mb-3" style="max-width: 100%;"></video>
                <canvas id="snapshot" class="border rounded mb-3" style="display: none; max-width: 100%;"></canvas>

                <!-- Buttons -->
            </div>
            <div id="map" class="mt-1" style="height: 30vh;"></div>
            <div id="button-group">
                <h3 class="m-0 p-0 text-primary" id="local-date"></h3>
                <h3 class="m-0 p-0 text-primary fw-semibold" id="local-time"></h3>
                <button id="capture" class="btn btn-primary"><i class="h1 m-0 p-0 fa fa-camera"></i></button>
                <button id="save" class="btn btn-success d-none"><i class="h1 m-0 p-0 fa fa-save"></i></button>
                <button id="reset" class="btn btn-secondary d-none"><i class="h1 m-0 p-0 fa fa-rotate-left"></i></button>
            </div>
        @else
            <div class="alert alert-warning">
                <h1>MAAF</h1>
                Waktu Presensi Sudah Habis
            </div>
        @endif
        <a href="{{ route('home') }}" class="btn btn-danger mt-3"><i class="fa fa-arrow-left"></i> KEMBALI KE MENU</a>
    </div>
</div>
@endsection
@push('js')
@if ($pengaturan)
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script language="JavaScript">
    let timer; // Timer untuk interval
    let berkas;
    let tanggal;
    let waktu;
    let koordinat;
    $(document).ready(function() {
        const video = $('#camera-stream')[0];
        const canvas = $('#snapshot')[0];
        const context = canvas.getContext('2d');

        // Start the camera
        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                video.srcObject = stream;
            })
            .catch(function (err) {
                $('#capture').addClass('d-none');
                alertApp("error","Error accessing the camera: "+ err.message);
            });
        }

        // Handle Capture Button
        $('#capture').on('click', function () {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Show snapshot and toggle buttons
            $('#snapshot').show();
            $('#camera-stream').hide();
            $('#capture').addClass('d-none'); // Hide Capture button
            $('#save, #reset').removeClass('d-none'); // Show Save and Reset buttons
            $.stopTimer();
        });

        // Handle Reset Button
        $('#reset').on('click', function () {
            $('#snapshot').hide();
            $('#camera-stream').show();
            $('#capture').removeClass('d-none'); // Show Capture button
            $('#save, #reset').addClass('d-none'); // Hide Save and Reset buttons
            $.startTimer(); // Mulai ulang timer
        });

        // Handle Save Button
        $('#save').on('click', function () {
            const imageData = canvas.toDataURL('image/png');
            $.ajax({
                url: "{{ route($attribute['link'].'store') }}",
                type: "POST",
                data: {
                    pegawai: "{{ $pegawai->id }}",
                    pengaturan: "{{ $pengaturan->id }}",
                    tempatKerja: "{{ $pegawai->tempat_kerja_id }}",
                    berkas: canvas.toDataURL('image/png'),
                    tanggal: tanggal,
                    waktu: waktu,
                    koordinat: JSON.stringify(koordinat),
                    tipe: "{{ $pengaturan->tipe }}",
                },
                dataType: "JSON",
                success: function(t) {
                    t.status ? (alertApp("success", t.message), cancel_preview()) : alertApp("error", t.message)
                },
                error: function(t, a, e) {
                    alertApp("error", e)
                }
            });
        });

        // Start the camera on page load
        startCamera();



        function updateTime() {
            const now = new Date();

            // Format tanggal (YYYY-MM-DD)
            tanggal = now.toISOString().split('T')[0];

            // Format waktu (HH:mm:ss)
            waktu = now.toTimeString().split(' ')[0];

            // Tampilkan di elemen HTML
            $('#local-date').text(tanggal);
            $('#local-time').text(waktu);
        }

        function startTimer() {
            // Perbarui waktu setiap detik
            timer = setInterval(updateTime, 1000);
            updateTime(); // Panggilan awal untuk sinkronisasi
        }

        function stopTimer() {
            clearInterval(timer); // Hentikan timer
        }

        // Ekspor fungsi ke dalam namespace jQuery
        $.extend({
            startTimer: startTimer,
            stopTimer: stopTimer
        });

        // Panggil timer pertama kali
        startTimer();
    });

    // maps
    // Inisialisasi peta di Pekalongan
    var map = L.map('map').setView([-6.8883, 109.6753], 16);

    // Tambahkan tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Definisikan polygon sebagai area radius yang ditentukan
    var editablePolygon = L.polygon(JSON.parse(@json($pegawai->tempat_kerja->koordinat)), {
        color: 'green'
    }).addTo(map);

    // Fit the map to the polygon
    map.fitBounds(editablePolygon.getBounds());

    // Fungsi ray-casting untuk mengecek apakah lokasi ada dalam polygon
    function isLocationInsidePolygon(latlng, polygon) {
        var x = latlng.lat, y = latlng.lng;
        var inside = false;

        // Ambil semua koordinat dari polygon
        var polyPoints = polygon.getLatLngs()[0];

        for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
            var xi = polyPoints[i].lat, yi = polyPoints[i].lng;
            var xj = polyPoints[j].lat, yj = polyPoints[j].lng;

            var intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }

        return inside;
    }

    function onLocationFound(e) {
        var userLocation = e.latlng;

        // Tambahkan marker untuk lokasi pengguna
        L.marker(userLocation).addTo(map)
            .bindPopup("Lokasi Anda").openPopup();

        // Cek apakah lokasi pengguna ada dalam polygon
        if (!isLocationInsidePolygon(userLocation, editablePolygon)) {
            $('#capture').addClass('d-none');
        } else {
            koordinat = userLocation;
            $('#capture').removeClass('d-none');
        }
    }
    
    // Menangani error jika gagal mendapatkan lokasi
    function onLocationError(e) {
        $('#capture').addClass('d-none');
        alertApp("error",e.message);
    }
    
    // Aktifkan fitur geolocation untuk mendapatkan lokasi pengguna
    map.on('locationfound', onLocationFound);
    map.on('locationerror', onLocationError);

    // Memulai proses untuk mendapatkan lokasi pengguna
    map.locate({ setView: true, maxZoom: 18 });

</script>
@endif
@endpush