@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>Available Channels</strong>
            </div>
            <div class="col-md-6 text-end" >
                <a href="{{ route('channels.create') }}" class="btn btn-sm btn-primary btn-block">+ Create New Channel</a>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Channel ID</th>
                    <th>Description</th>
                    <th>Enabled</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td id="id_{{$item->id}}">
                            {{$item->name}}
                        </td>
                        <td>
                            {{$item->channel_id}}
                        </td>
                        <td>
                            {{$item->description}}
                        </td>
                        <td>
                            {{$item->is_enabled}}
                        </td>
                        <td>
                            <a href="{{ route('channels.edit', ['id'=>$item->id]) }}" class="text-primary display-inline-block text-decoration-none">Edit</a>
                        </td>
                        <td>
                            <a href="{{ route('channels.delete', ['id'=>$item->id]) }}" class='text-decoration-none display-inline-block ms-3 text-danger'>Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row mt-4">
            <div class="col-md-12">
                {{ $items->appends($_GET)->links() }}
            </div>
        </div>
    </div>
@endsection
