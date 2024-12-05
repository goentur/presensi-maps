@extends('layouts.data')

@push('vendor-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
@endpush
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-6">
                <div class="fs-3 text-uppercase">KOORDINAT {{ $data->nama }}</div>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route($attribute['link'].'index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> KEMBALI DATA</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info">Klik tombol ubah pada bagian kiri atas atas <i class="fa-regular fa-pen-to-square"></i>, kemudian klik save apabila sudah selesai</div>
        <div id="map" style="height: 65vh;"></div>
    </div>
</div>
@endsection
@push('vendor-js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
@endpush
@push('js')
<script>
    // Inisialisasi peta di Pekalongan
    var map = L.map('map').setView([-6.8883, 109.6753], 13);

    // Tambahkan layer tile ke peta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Definisikan polygon dengan koordinat awal
    var editablePolygon = L.polygon(JSON.parse(@json($data->koordinat)), {
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
            var latlngs = layer.getLatLngs(); // Ambil koordinat baru dari polygon)
            $.ajax({
                url: "{{ route($attribute['link'].'ubah-koordinat') }}",
                type: "POST",
                data: {
                    id: "{{$data->id}}",
                    koordinat: JSON.stringify(latlngs), // Konversi array ke JSON
                },
                dataType: "JSON",
                success: function(t) {
                    t.status ? alertApp("success", t.message) : alertApp("error", t.message)
                },
                error: function(t, a, e) {
                    alertApp("error", e)
                }
            });
        });
    });
</script>
@endpush