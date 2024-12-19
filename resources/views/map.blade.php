<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasar Peta Interaktif</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
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

        #leaflet-map,
        #google-map {
            height: 400px;
            margin: 20px auto;
            max-width: 90%;
            border-radius: 16px;
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
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Peta Interaktif</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="/map" class="nav-link active ">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Latihan1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/interactive" class="nav-link">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Latihan2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tugas1" class="nav-link">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Tugas1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/tugas2" class="nav-link">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Tugas2</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <h1>Peta Interaktif dengan Laravel (LATIHAN)</h1>

            <div id="leaflet-map"></div>
            <div id="google-map"></div>
        </div>
    </div>
    <script>
        // Inisialisasi peta Leaflet
        const leafletMap = L.map('leaflet-map').setView([-8.6509, 115.2194], 13);
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
                lat: -8.6509,
                lng: 115.2194
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
        addMarker(-8.6509, 115.2194, "Universitas Udayana", "Denpasar, Bali");
        addMarker(-8.675358644252073, 115.20693941821968, "Fore Coffe", "Denpasar, Bali");
        addMarker(-8.663372743705459, 115.22739868277444, "Taman Kopi", "Denpasar, Bali");
        addMarker(-8.7984047, 115.1698715, "Rektorat Universitas Udayana", "Badung, Bali");
    </script>
    </script>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
</body>

</html>
