<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Interaktif dengan Laravel (TUGAS 1)</title>

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            padding: 10px;
        }

        .map-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .map-wrapper {
            flex: 1;
            min-width: 300px;
            max-width: 600px;
            width: 100%;
        }

        .map {
            height: 400px;
            width: 100%;
        }

        .nav-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .nav-button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .map-container {
                flex-direction: column;
            }

            .map-wrapper {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <h1>Peta Interaktif dengan Laravel (TUGAS 1)</h1>

    <a href="/" class="nav-button">Kembali ke Halaman Awal</a>

    <div class="map-container">
        <div class="map-wrapper">
            <h2>Leaflet Map</h2>
            <div id="leaflet-map" class="map"></div>
        </div>
        <div class="map-wrapper">
            <h2>Google Maps</h2>
            <div id="google-map" class="map"></div>
        </div>
    </div>

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
</body>

</html>
