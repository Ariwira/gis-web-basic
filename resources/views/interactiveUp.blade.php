@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h3 class="my-3">GIS Interactive Add Data</h3>
            </div>
        </div>
        <div class="form-container">
            <h3>Tambahkan Marker</h3>
            <form class="form-group" id="markerForm" method="POST" action="{{ url('api/markers') }}">
                @csrf
                <div class="form-group">
                    <label for="markerName">Nama Lokasi</label>
                    <input type="text" class="form-control" id="markerName" placeholder="Nama Lokasi" required />
                </div>
                <div class="form-group">
                    <label for="markerLat">Latitude</label>
                    <input type="text" class="form-control" id="markerLat" placeholder="Latitude" required />
                </div>
                <div class="form-group">
                    <label for="markerLng">Longitude</label>
                    <input type="text" class="form-control" id="markerLng" placeholder="Longitude" required />
                </div>
                <button type="submit" class="btn btn-primary">Tambah Marker</button>
            </form>

            <h3>Tambahkan Poligon</h3>
            <form id="polygonForm">
                <div class="form-group">
                    <label for="polygonCoords">Koordinat Poligon (JSON)</label>
                    <textarea class="form-control" id="polygonCoords" placeholder="Koordinat Poligon (JSON)" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Poligon</button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('script')
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
@endsection
