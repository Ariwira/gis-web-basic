<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasar Peta Interaktif</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>

    <style>
        .form-container {
            padding: 20px
        }

        form {
            margin-bottom: 20px;
        }

        input,
        textarea {
            display: block;
            margin: 10px 0;
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
                            <a href="/map" class="nav-link ">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Latihan1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/interactive" class="nav-link active">
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
            <div class="form-container">
                <h3>Tambahkan Marker</h3>
                <form id="markerForm" method="POST" action="{{ url('api/markers') }}">
                    @csrf
                    <input type="text" id="markerName" placeholder="Nama Lokasi" required />
                    <input type="text" id="markerLat" placeholder="Latitude" required />
                    <input type="text" id="markerLng" placeholder="Longitude" required />
                    <button type="submit">Tambah Marker</button>
                </form>

                <h3>Tambahkan Poligon</h3>
                <form id="polygonForm">
                    <textarea id="polygonCoords" placeholder="Koordinat Poligon (JSON)" required></textarea>
                    <button type="submit">Tambah Poligon</button>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Tambahkan event listener untuk marker
        document.getElementById("markerForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const name = document.getElementById("markerName").value;
            const lat = parseFloat(document.getElementById("markerLat").value);
            const lng = parseFloat(document.getElementById("markerLng").value);

            fetch("{{ url('api/markers') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        name,
                        latitude: lat,
                        longitude: lng
                    }),
                })
                .then((res) => res.json())
                .then((data) => {
                    alert("Marker ditambahkan!");
                });
        });

        // Tambahkan event listener untuk poligon
        document.getElementById("polygonForm").addEventListener("submit", function(e) {
            e.preventDefault();

            // Ambil nilai dari input dan parsing JSON
            const coordsInput = document.getElementById("polygonCoords").value;

            // Cek apakah input valid
            let coords;
            try {
                coords = JSON.parse(coordsInput);
            } catch (error) {
                alert("Format koordinat tidak valid. Pastikan input adalah JSON yang benar.");
                return;
            }

            // Kirim data ke server
            fetch("{{ url('api/polygons') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        coordinates: coords
                    }),
                })
                .then((res) => {
                    if (!res.ok) {
                        // Jika respons tidak ok, tampilkan pesan kesalahan
                        return res.json().then(errorData => {
                            throw new Error(errorData.error ||
                                "Terjadi kesalahan saat menambahkan poligon.");
                        });
                    }
                    return res.json();
                })
                .then((data) => {
                    alert("Poligon ditambahkan!");
                    // Anda bisa menambahkan logika tambahan di sini, seperti mereset form atau memperbarui tampilan
                })
                .catch((error) => {
                    alert("Error: " + error.message);
                });
        });
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
