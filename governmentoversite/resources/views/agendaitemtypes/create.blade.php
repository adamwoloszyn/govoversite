<!-- resources/views/channels/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New Agenda Item Type</h1>

        <form action="{{ route('agendaitemtypes.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="short_description">Short Description:</label>
                <input type="text" name="short_description" id="short_description" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="long_description">Long Description:</label>
                <input type="text" name="long_description" id="long_description" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="template">Template:</label>
                <textarea name="template" id="template" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="order">Order:</label>
                <input type="text" name="order" id="order" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Agenda Item Type</button>
        </form>
    </div>
@endsection
