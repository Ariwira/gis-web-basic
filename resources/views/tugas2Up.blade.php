@extends('layout.app')

@section('content')
    <style>
        #map {
            height: 500px;
        }

        @media (max-width: 768px) {
            #map {
                height: 300px;
                /* Reduce map height on smaller screens */
            }
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div class="container-fluid">
        <div class="row">
            <!-- Map Section -->
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Peta Interaktif</h3>
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambahkan Data</h3>
                    </div>
                    <div class="card-body">
                        <h5>Tambahkan Marker</h5>
                        <form id="markerForm">
                            <div class="form-group">
                                <input type="text" id="markerName" class="form-control" placeholder="Nama Lokasi"
                                    required />
                            </div>
                            <div class="form-group">
                                <input type="text" id="markerLat" class="form-control" placeholder="Latitude" required />
                            </div>
                            <div class="form-group">
                                <input type="text" id="markerLng" class="form-control" placeholder="Longitude"
                                    required />
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah Marker</button>
                        </form>

                        <h5 class="mt-4">Tambahkan Poligon</h5>
                        <form id="polygonForm">
                            <div class="form-group">
                                <textarea id="polygonCoords" class="form-control" placeholder='Koordinat Poligon (JSON)' rows="4" required></textarea>
                            </div>
                            <button type="submit" class=" btn btn-primary">Tambah Poligon</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Marker</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="markerTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Poligon</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="polygonTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Koordinat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('script')
    <script type="text/javascript">
        // Initialize the map
        const map = L.map('map').setView([-8.409518, 115.188919], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Store markers and polygons in memory for easy reference
        const markers = new Map();
        const polygons = new Map();

        // Load existing markers and polygons
        async function loadExistingData() {
            try {
                const [markersResponse, polygonsResponse] = await Promise.all([
                    fetch('/api/markers'),
                    fetch('/api/polygons')
                ]);

                const markersData = await markersResponse.json();
                const polygonsData = await polygonsResponse.json();

                // Add existing markers
                markersData.forEach(markerData => {
                    addMarkerToMap(markerData);
                    addMarkerToTable(markerData);
                });

                // Add existing polygons
                polygonsData.forEach(polygonData => {
                    addPolygonToMap(polygonData);
                    addPolygonToTable(polygonData);
                });
            } catch (error) {
                console.error('Error loading data:', error);
                alert('Error loading existing data');
            }
        }

        // Function to add marker to map
        function addMarkerToMap(markerData) {
            const marker = L.marker([markerData.latitude, markerData.longitude])
                .addTo(map)
                .bindPopup(markerData.name);
            markers.set(markerData.id, marker);
        }

        // Function to add polygon to map
        function addPolygonToMap(polygonData) {
            const coords = JSON.parse(polygonData.coordinates);
            const latLngs = coords.map(coord => [coord.lat, coord.lng]);
            const polygon = L.polygon(latLngs).addTo(map);
            polygons.set(polygonData.id, polygon);
        }

        function addMarkerToTable(markerData) {
            // Hitung jumlah baris yang ada di tabel
            const rowCount = document.querySelectorAll('#markerTable tbody tr').length;

            // Nomor urut dimulai dari 1
            const rowNumber = rowCount + 1;

            const row = `
    <tr data-id="${markerData.id}">
        <td>${rowNumber}</td> <!-- Menggunakan nomor urut di sini -->
        <td>${markerData.name}</td>
        <td>${markerData.latitude}</td>
        <td>${markerData.longitude}</td>
        <td>
            <div class="btn-group" role="group">
                <button onclick="viewMarker(${markerData.id})" class="btn btn-info btn-sm mr-1">View</button>
                <button onclick="deleteMarker(${markerData.id})" class="btn btn-danger btn-sm mr-1">Hapus</button>
            </div>
        </td>
    </tr>
`;
            document.querySelector('#markerTable tbody').insertAdjacentHTML('beforeend', row);
        }

        // Function to add polygon to table
        function addPolygonToTable(polygonData) {
            // Hitung jumlah baris yang ada di tabel
            const rowCount = document.querySelectorAll('#polygonTable tbody tr').length;

            // Nomor urut dimulai dari 1
            const rowNumber = rowCount + 1;

            const row = `
    <tr data-id="${polygonData.id}">
        <td>${rowNumber}</td> <!-- Menggunakan nomor urut di sini -->
        <td>${polygonData.coordinates}</td>
        <td>
            <div class="btn-group" role="group">
                <button onclick="viewPolygon(${polygonData.id})" class="btn btn-info btn-sm mr-1">View</button>
                <button onclick="deletePolygon(${polygonData.id})" class="btn btn-danger btn-sm mr-1">Hapus</button>
            </div>
        </td>
    </tr>
`;
            document.querySelector('#polygonTable tbody').insertAdjacentHTML('beforeend', row);
        }

        // Add marker form submission
        document.getElementById("markerForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            const formData = {
                name: document.getElementById("markerName").value,
                latitude: parseFloat(document.getElementById("markerLat").value),
                longitude: parseFloat(document.getElementById("markerLng").value)
            };

            try {
                const response = await fetch('/api/markers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const markerData = await response.json();
                addMarkerToMap(markerData);
                addMarkerToTable(markerData);
                this.reset();
                alert('Marker berhasil ditambahkan');
            } catch (error) {
                console.error('Error:', error);
                alert('Error menambahkan marker');
            }
        });

        // Tambahkan event listener untuk poligon
        document.getElementById("polygonForm").addEventListener("submit", async function(e) {
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
            try {
                const response = await fetch("{{ url('api/polygons') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        coordinates: coords
                    }),
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const polygonData = await response.json();
                addPolygonToMap(polygonData); // Menambahkan polygon ke peta
                addPolygonToTable(polygonData); // Menambahkan polygon ke tabel
                this.reset(); // Mereset form
                alert('Polygon berhasil ditambahkan'); // Menampilkan pesan sukses
            } catch (error) {
                alert("Error: " + error.message);
            }
        });

        // View marker function
        async function viewMarker(id) {
            const marker = markers.get(id);
            if (marker) {
                map.setView(marker.getLatLng(), 15);
                marker.openPopup();
            }
        }

        // View polygon function
        async function viewPolygon(id) {
            const polygon = polygons.get(id);
            if (polygon) {
                map.fitBounds(polygon.getBounds());
            }
        }

        // Delete marker function
        async function deleteMarker(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus marker ini?')) return;

            try {
                const response = await fetch(`/api/markers/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                // Remove marker from map
                const marker = markers.get(id);
                if (marker) {
                    map.removeLayer(marker);
                    markers.delete(id);
                }

                // Remove from table
                document.querySelector(`#markerTable tr[data-id="${id}"]`).remove();
                alert('Marker berhasil dihapus');
            } catch (error) {
                console.error('Error:', error);
                alert('Error menghapus marker');
            }
        }

        // Delete polygon function
        async function deletePolygon(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus polygon ini?')) return;

            try {
                const response = await fetch(`/api/polygons/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                // Remove polygon from map
                const polygon = polygons.get(id);
                if (polygon) {
                    map.removeLayer(polygon);
                    polygons.delete(id);
                }

                // Remove from table
                document.querySelector(`#polygonTable tr[data-id="${id}"]`).remove();
                alert('Polygon berhasil dihapus');
            } catch (error) {
                console.error('Error:', error);
                alert('Error menghapus polygon');
            }
        }

        // Load existing data when page loads
        loadExistingData();
    </script>
@endsection
