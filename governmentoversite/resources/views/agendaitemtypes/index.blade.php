@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>Available Agenda Item Types</strong>
            </div>
            <div class="col-md-6 text-end" >
                <a href="{{ route('agendaitemtypes.create') }}" class="btn btn-sm btn-primary btn-block">+ Create New Agenda Item Type</a>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Order</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            {{$item->id}}
                        </td>
                        <td>
                            {{$item->short_description}}
                        </td>
                        <td>
                            {{$item->order}}
                        </td>
                        <td>
                            <a href="{{ route('agendaitemtypes.edit', ['id'=>$item->id]) }}" class="text-primary display-inline-block text-decoration-none">Edit</a>
                        </td>
                        <td>
                            <a href="{{ route('agendaitemtypes.delete', ['id'=>$item->id]) }}" class='text-decoration-none display-inline-block ms-3 text-danger'>Delete</a>
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
