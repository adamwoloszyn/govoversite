<!-- resources/views/channels/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Agenda Item Type</h1>

        <form action="{{ route('agendaitemtypes.update', ['id'=>$agendaItemType->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="short_description">Short Description:</label>
                <input type="text" name="short_description" id="short_description" class="form-control" value="{{ $agendaItemType->short_description }}" required>
            </div>

            <div class="form-group">
                <label for="long_description">Long Description:</label>
                <input type="text" name="long_description" id="long_description" class="form-control" value="{{ $agendaItemType->long_description }}" required>
            </div>

            <div class="form-group">
                <label for="template">Template:</label>
                <input id="description" type="hidden" name="description" value="{{ $agendaItemType->template }}">
                <trix-editor name="template" id="template"  input="description"></trix-editor>
            </div>

            <div class="form-group">
                <label for="order">Order:</label>
                <input type="text" name="order" id="order" class="form-control" required value="{{ $agendaItemType->order }}">
            </div>

            <button type="submit" class="btn btn-primary">Update Agenda Item Type</button>
        </form>
    </div>
@endsection
