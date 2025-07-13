<!-- resources/views/channels/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New Channel</h1>

        <form action="{{ route('channels.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="slug">SEO Slug:</label>
                <input type="text" name="slug" id="slug" class="form-control" required />
            </div>

            <div class="form-group">
                <label for="channel_id">Channel ID:</label>
                <input type="text" name="channel_id" id="channel_id" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <br />
                <p>Please upload a thumbnail:</p>
                <input class="form-control" type="file" name="thumbnail" id="thumbnail" /><br /><br />
            </div>

            <button type="submit" class="btn btn-primary">Add Channel</button>
        </form>
    </div>
@endsection
