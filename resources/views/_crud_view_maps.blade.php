@extends('layout.app')

@section('title')
    Maps View
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Crud</a></li>
        <li class="breadcrumb-item active" aria-current="page">Maps View</li>
    </ol>
@endsection

@section('content')
    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U&libraries=places">
    </script>

    <style type="text/css">
        .select2 {
            width: 100% !important;
            /* overrides computed width, 100px in your demo */
        }
    </style>

    <div class="card card-info card-outline" id="main-box" style="">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-star"></i> Maps View
            </h3>
            <div class="card-tools">
                <a href="{{ url('handson3') }}" class="btn btn-danger btn-sm pull-right" data-toggle="tooltip"
                    data-placement="top" title="Kembali ke daftar data"><i class="fa fa-undo"></i> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div id="leaflet-map" style="height: 400px; width: 100%;"></div>
            <div id="google-map" style="height: 400px; width: 100%; margin-top: 20px;"></div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var token = '{{ csrf_token() }}';

        $(document).ready(function() {
            const marker = @json($marker); // Mengirim data marker dari PHP ke JavaScript
            console.log(marker);

            // Leaflet Map
            const leafletMap = L.map('leaflet-map').setView([marker.latitude, marker.longitude], 13);

            // Tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(leafletMap);

            // Koordinat awal dan akhir
            const startPoint = L.latLng(marker.latitude, marker.longitude); // Universitas Udayana
            const endPoint = L.latLng(-8.684644, 115.216843); // Universitas Udayana Sudirman

            // Tambahkan routing
            L.Routing.control({
                waypoints: [startPoint, endPoint],
                routeWhileDragging: true,
                showAlternatives: true,
                altLineOptions: {
                    styles: [{
                        color: 'blue',
                        opacity: 0.7,
                        weight: 5
                    }]
                },
            }).addTo(leafletMap);


            // Google Maps API Map
            const googleMapDiv = document.getElementById('google-map');
            const googleMap = new google.maps.Map(googleMapDiv, {
                center: {
                    lat: parseFloat(marker.latitude),
                    lng: parseFloat(marker.longitude)
                },
                zoom: 13,
            });

            // Tambahkan marker pada lokasi awal
            new google.maps.Marker({
                position: {
                    lat: parseFloat(marker.latitude),
                    lng: parseFloat(marker.longitude)
                },
                map: googleMap,
                title: marker.name,
            });

            // Layanan Directions
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();

            // Tampilkan rute di peta
            directionsRenderer.setMap(googleMap);

            // Tentukan titik awal dan akhir
            const origin = {
                lat: parseFloat(marker.latitude),
                lng: parseFloat(marker.longitude)
            };
            const destination = {
                lat: -8.684644,
                lng: 115.216843
            }; // Koordinat Universitas Udayana Sudirman

            const request = {
                origin: origin,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            // Hitung rute
            directionsService.route(request, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error(`Error fetching directions: ${status}`);
                }
            });
        });

        function goBack() {
            window.history.back();
        }
    </script>
@endsection
