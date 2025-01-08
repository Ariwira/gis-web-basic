@extends('layout.app')

@section('title')
    Dashboard
@endsection
@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">#</li>
    </ol>
@endsection
@section('content')
    <div class="card mb-12 border-left-primary card-info">
        <div class="card-body">
            <div class="row">
                <p>Hello Testing!</p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        var token = '{{ csrf_token() }}';

        $(document).ready(function() {

        })
    </script>
@endsection
