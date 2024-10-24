<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Location in Polygon</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>
<body>
    <div id="map" style="height: 100vh;"></div>

    <script>
        // Inisialisasi peta di Pekalongan
        var map = L.map('map').setView([-6.8883, 109.6753], 13);

        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Definisikan polygon sebagai area radius yang ditentukan
        var editablePolygon = L.polygon([
            [-6.8853, 109.6605],
            [-6.8801, 109.6790],
            [-6.8981, 109.6815],
            [-6.9001, 109.6650],
            [-6.8853, 109.6605]  // Tutup polygon
        ], {
            color: 'blue'
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
          // Lokasi pengguna secara manual
          // var userLocation = L.latLng([-6.8884, 109.6754]); // Buat manual untuk cek lokasi saja menjadi objek L.latLng
            var userLocation = e.latlng; // Buat secara otomatis oleh sistem

            // Tambahkan marker untuk lokasi pengguna
            L.marker(userLocation).addTo(map)
                .bindPopup("Lokasi Anda").openPopup();

            // Cek apakah lokasi pengguna ada dalam polygon
            if (!isLocationInsidePolygon(userLocation, editablePolygon)) {
                alert("Anda tidak berada pada radius yang ditentukan!");
            } else {
                alert("Anda berada dalam radius yang ditentukan.");
            }
        }
        
        // Menangani error jika gagal mendapatkan lokasi
        function onLocationError(e) {
            alert("Error mendapatkan lokasi: " + e.message);
        }

        // Aktifkan fitur geolocation untuk mendapatkan lokasi pengguna
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);

        // Memulai proses untuk mendapatkan lokasi pengguna
        map.locate({setView: true, maxZoom: 16});

        // // Tambahkan marker untuk lokasi pengguna
        // L.marker(userLocation).addTo(map)
        //     .bindPopup("Lokasi Anda").openPopup();

        // // Cek apakah lokasi pengguna ada dalam polygon
        // if (!isLocationInsidePolygon(userLocation, editablePolygon)) {
        //     alert("Anda tidak berada pada radius yang ditentukan!");
        // } else {
        //     alert("Anda berada dalam radius yang ditentukan.");
        // }
    </script>
</body>
</html>