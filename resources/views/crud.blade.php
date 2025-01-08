@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h3 class="my-3">GIS Interactive - Add and View Data</h3>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Marker</h3>
                    </div>
                    <div class="card-body">
                        <!-- Add Marker -->
                        <form id="add-marker-form" action="{{ route('handson3.storeMarker') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Marker Name:</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Latitude:</label>
                                <input type="text" name="latitude" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Longitude:</label>
                                <input type="text" name="longitude" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-default">Add Marker</button>
                        </form>
                        <hr>
                        <table class="table table-bordered table-striped" id="tb_marker">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Lat</th>
                                    <th>Long</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="data-table-body">
                                <!-- Dynamic rows will be added here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Polygon</h3>
                    </div>
                    <div class="card-body">
                        <!-- Add Polygon -->
                        <form id="add-polygon-form" action="{{ route('handson3.storePolygon') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Polygon Coordinates (JSON):</label>
                                <textarea name="coordinates" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-default">Add Polygon</button>
                        </form>
                        <hr>
                        <table class="table table-bordered table-striped" id="tb_polygon">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Coordinates</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="data-table-body">
                                <!-- Dynamic rows will be added here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endsection

    @section('script')
        <script type="text/javascript">
            var token = '{{ csrf_token() }}';
            var tb_marker = $('#tb_marker').dataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ url('/listDataMarker') }}',
                    data: function(d) {}
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        width: "2%"
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'latitude',
                        name: 'latitude'
                    },
                    {
                        data: 'longitude',
                        name: 'longitude'
                    },
                    {
                        data: 'action',
                        name: 'id',
                        orderable: true,
                        searchable: true
                    }
                ],
                rowCallback: function(row, data, iDisplayIndex) {
                    var api = this.api();
                    var info = api.page.info();
                    var page = info.page;
                    var length = info.length;
                    var index = (page * length + (iDisplayIndex + 1));
                    $('td:eq(0)', row).html(index);
                },
                stateSaveCallback: function(settings, data) {
                    localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
                },
                stateLoadCallback: function(settings) {
                    return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
                },
                drawCallback: function(settings) {
                    var api = this.api();
                }
            });


            var tb_polygon = $('#tb_polygon').dataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ url('/listDataPolygon') }}',
                    data: function(d) {}
                },
                columns: [{
                        data: 'no',
                        name: 'no',
                        width: "2%"
                    },
                    {
                        data: 'coordinates',
                        name: 'coordinates'
                    },
                    {
                        data: 'action',
                        name: 'id',
                        orderable: true,
                        searchable: true
                    }
                ],
                rowCallback: function(row, data, iDisplayIndex) {
                    var api = this.api();
                    var info = api.page.info();
                    var page = info.page;
                    var length = info.length;
                    var index = (page * length + (iDisplayIndex + 1));
                    $('td:eq(0)', row).html(index);
                },
                stateSaveCallback: function(settings, data) {
                    localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data))
                },
                stateLoadCallback: function(settings) {
                    return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance))
                },
                drawCallback: function(settings) {
                    var api = this.api();
                }
            });


            $(document).ready(function() {})


            function edit_data(id) {
                $.ajax({
                    type: "GET",
                    url: '{{ url('handson4') }}/' + id + '/edit',
                    async: true,
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    beforeSend: function(data) {
                        // on_load();
                        $('#modal-xl').find('.modal-xl').find(".modal-content").find(".modal-header").attr("class",
                            "modal-header bg-light-blue");
                        $("#modal-xl .modal-title").html("Edit Data - Maps");
                        $('#modal-xl').modal("show");
                        $('#modal-xl').find('.modal-body-content').html('');
                        $("#modal-xl").find(".overlay").fadeIn("200");
                    },
                    success: function(data) {
                        $('#modal-xl').find('.modal-body-content').html(data);
                    },
                    complete: function(data) {
                        $("#modal-xl").find(".overlay").fadeOut("200");
                    },
                    error: function(data) {
                        alert("error ajax occured!");
                    }

                });
            }
        </script>
    @endsection
