<!-- resources/views/channels/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Channel</h1>

        <form action="{{ route('channels.update', ['id'=>$currentChannel->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $currentChannel->name }}" required />
            </div>

            <div class="form-group">
                <label for="slug">SEO Slug:</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ $currentChannel->slug }}" required />
            </div>

            <div class="form-group">
                <label for="channel_id">Channel ID:</label>
                <input type="text" name="channel_id" id="channel_id" class="form-control" value="{{ $currentChannel->channel_id }}" required />
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control">{{ $currentChannel->description }}</textarea>
            </div>

            <div class="form-group">
                <br />
                @if($currentChannel->thumbnail)
                    <img src="{{ asset($currentChannel->thumbnail) }}" alt="" class="mb-4 rounded d-block" />
                @else
                    <p>No thumbnail selected yet. Please upload:</p>
                @endif
                <input class="form-control" type="file" name="thumbnail" id="thumbnail" /><br />
            </div>

            <div class="form-group">
                <div>
                    <input type="checkbox" id="is_enabled" name="is_enabled" value="1" {{ $currentChannel->is_enabled ? 'checked' : '' }}>
                    <label for="is_enabled">Check if Enabled</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Channel</button>
        </form>
    </div>
@endsection
