@extends('layouts.data')

@push('vendor-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush
@section('content')
<div class="card">
    <div class="card-body justify-align-center text-center">
        @if ($pengaturan)
            <h1>{{ $pengaturan->tipe }}</h1>

            <video id="camera-stream" autoplay playsinline></video>
            <button id="capture">Capture</button>
            <canvas id="snapshot"></canvas>


            <div id="kamera" style="width: 100%;height: 30vh;"></div>
            <div id="results"></div>
            <div id="map" class="mt-1" style="height: 30vh;"></div>
            <div id="ambilGambar" class="mt-3" style="display:none">
                <h3 class="m-0 p-0 text-primary" id="local-date"></h3>
                <h3 class="m-0 p-0 text-primary fw-semibold" id="local-time"></h3>
                <button type="button" class="btn btn-primary" onclick="preview_snapshot()"><i class="h1 m-0 p-0 fa fa-camera"></i></button>
            </div>
            <div id="simpanGambar" class="mt-3" style="display:none">
                <button type="button" class="btn btn-danger" onclick="cancel_preview()"><i class="h1 m-0 p-0 fa fa-rotate-left"></i></button>
                <button type="button" class="btn btn-success" onclick="save_photo()"><i class="h1 m-0 p-0 fa fa-save"></i></button>
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

      // Request access to the user's camera
      function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
          .then(function (stream) {
            video.srcObject = stream;
          })
          .catch(function (err) {
            alert('Error accessing the camera: ' + err.message);
          });
      }

      // Capture the current frame from the video stream
      $('#capture').on('click', function () {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        $('#snapshot').show(); // Display the snapshot
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


    // // Inisialisasi Webcam
    // Webcam.set({
    //     image_format: 'jpeg',
    //     jpeg_quality: 100
    // });
    // Webcam.on('error', function() {
    //     alertApp("error","Tidak bisa akses WebCam");
    //     $('#ambilGambar').hide();
    // });
    // Webcam.attach('#kamera');

    // // Fungsi untuk mengambil snapshot
    // function preview_snapshot() {
    //     Webcam.snap(function(gambar) {
    //         berkas = gambar
    //         $('#results').html('<img class="img-fluid" src="' + gambar + '"/>');
    //     });
    //     $('#kamera').hide();
    //     $('#ambilGambar').hide();
    //     $('#simpanGambar').show();
    //     $.stopTimer(); // Hentikan timer saat snapshot
    // }

    // // Fungsi untuk membatalkan snapshot
    // function cancel_preview() {
    //     Webcam.unfreeze();
    //     $('#kamera').show();
    //     $('#results').html('');
    //     $('#ambilGambar').show();
    //     $('#simpanGambar').hide();
    //     $.startTimer(); // Mulai ulang timer
    // }

    // Fungsi untuk menyimpan foto
    function save_photo() {
        $.ajax({
            url: "{{ route($attribute['link'].'store') }}",
            type: "POST",
            data: {
                pegawai: "{{ $pegawai->id }}",
                pengaturan: "{{ $pengaturan->id }}",
                tempatKerja: "{{ $pegawai->tempat_kerja_id }}",
                berkas: berkas,
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
        // success
    }
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
            $('#ambilGambar').hide();
        } else {
            koordinat = userLocation;
            $('#ambilGambar').show();
        }
    }
    
    // Menangani error jika gagal mendapatkan lokasi
    function onLocationError(e) {
        $('#ambilGambar').hide();
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