<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Polygon Corner Modification</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
</head>

<body>
    <div id="map" style="height: 100vh;"></div>

    <script>
        // Inisialisasi peta di Pekalongan
        var map = L.map('map').setView([-6.8883, 109.6753], 13);

        // Tambahkan layer tile ke peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Definisikan polygon dengan koordinat awal
        var editablePolygon = L.polygon([
            [-6.8968067801060275, 109.66059207916261],
            [-6.896854710515236, 109.66119289398195],
            [-6.897222176824768, 109.66117680072784],
            [-6.897179572049864, 109.66051161289215]
        ], {
            color: 'blue',
            draggable: true // Ini opsional, jika Anda ingin draggable
        }).addTo(map);

        // Fit map bounds to polygon
        map.fitBounds(editablePolygon.getBounds());

        // Buat event untuk menggambar dan mengedit
        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: L.featureGroup([editablePolygon]), // Edit group berisi polygon yang ada
                remove: false, // Nonaktifkan fitur penghapusan
            },
            draw: false // Nonaktifkan semua fitur menggambar, hanya editing
        });
        map.addControl(drawControl);

        // Event yang di-trigger setelah polygon diedit
        map.on(L.Draw.Event.EDITED, function(event) {
            var layers = event.layers;
            layers.eachLayer(function(layer) {
                var latlngs = layer.getLatLngs(); // Ambil koordinat baru dari polygon
                console.log("Koordinat baru: ", latlngs); // Log koordinat baru untuk verifikasi
            });
        });
    </script>
</body>

</html>