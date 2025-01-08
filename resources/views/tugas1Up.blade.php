@extends('layout.app')

@section('content')
    <style>
        .map-container {
            width: 100%;
            /* Lebar penuh */
            aspect-ratio: 16 / 9;
            /* Proporsi lebar dan tinggi */
            background-color: #e9ecef;
            /* Warna latar untuk visualisasi */
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h3 class="my-3">GIS Interactive Map</h3>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <h3>Leaflet Map</h3>
                    <div id="leaflet-map" class="map-container rounded"></div>
                </div>
                <div class="col-md-6">
                    <h3>Google Map</h3>
                    <div id="google-map" class="map-container rounded"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('script')
    <script>
        // Inisialisasi peta Leaflet
        const leafletMap = L.map('leaflet-map').setView([-8.7984047, 115.1698715, ], 13);
        // Tambahkan tile layer default (OpenStreetMap)
        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });

        // Tambahkan tile layer CartoDB
        const cartoDBLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.carto.com/">CartoDB</a>'
        });

        // Tambahkan tile layer Esri World Imagery
        const esriLayer = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Earthstar Geographics'
            });

        // Tambahkan layer ke peta dan set default layer
        osmLayer.addTo(leafletMap);

        // Kontrol layer untuk mengganti tile layer
        const baseLayers = {
            "OpenStreetMap": osmLayer,
            "CartoDB Light": cartoDBLayer,
            "Esri World Imagery": esriLayer
        };

        L.control.layers(baseLayers).addTo(leafletMap);

        // Inisialisasi peta Google Maps
        const googleMap = new google.maps.Map(document.getElementById('google-map'), {
            center: {
                lat: -8.7984047,
                lng: 115.1698715
            },
            zoom: 13,
        });

        // Fungsi untuk menambahkan marker
        function addMarker(lat, lng, title, description) {
            // Leaflet
            const marker = L.marker([lat, lng]).addTo(leafletMap);
            marker.bindPopup(`<b>${title}</b><br>${description}`);
            marker.on('click', function() {
                leafletMap.setView([lat, lng], 15);
            });

            // Event listener untuk popup close
            marker.on('popupclose', function() {
                leafletMap.setZoom(13);
            });

            // Google Maps
            const googleMarker = new google.maps.Marker({
                position: {
                    lat,
                    lng
                },
                map: googleMap,
                title: title,
            });

            // Membuat InfoWindow
            const infoWindow = new google.maps.InfoWindow({
                content: `<b>${title}</b><br>${description}`
            });

            // Menampilkan InfoWindow saat marker diklik
            googleMarker.addListener('click', function() {
                infoWindow.open(googleMap, googleMarker);
                googleMap.setZoom(15);
                googleMap.setCenter(googleMarker.getPosition());
            });
            // Event listener untuk InfoWindow close
            google.maps.event.addListener(infoWindow, 'closeclick', function() {
                googleMap.setZoom(13);
            });
        }

        // Tambahkan marker
        addMarker(-8.7984047, 115.1698715, "Kantor : Rektorat Universitas Udayana", "Badung, Bali");
        addMarker(-8.675358644252073, 115.20693941821968, "Fore Coffe", "Denpasar, Bali");
        addMarker(-8.663372743705459, 115.22739868277444, "Taman Kopi", "Denpasar, Bali");
        addMarker(-8.778414925021037, 115.16906282510554, "Gogo Fried Chicken", "Badung, Bali")
    </script>
@endsection
