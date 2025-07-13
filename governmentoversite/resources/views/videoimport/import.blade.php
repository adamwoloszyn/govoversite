@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Old Video Importer</h1>
        <form method="POST" action="{{ route('old-video-import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="csv_file">Upload CSV File:</label>
                <input type="file" name="csv_file" id="csv_file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Import CSV</button>
        </form>
    </div>
@endsection
