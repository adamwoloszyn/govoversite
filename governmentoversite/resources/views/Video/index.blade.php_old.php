@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>Available Videos</strong>
            </div>
            <div class="col-md-6 text-end" >
                <span class="">
                    <form action="{{ url('/Video/search') }}" method="post" id="search">
                        @csrf
                        <input type="text" id="q" name="q" class="p-1" placeholder="Search for videos / keywords">
                        <input type="hidden" class="form-control" id="currentPageNumber" name="currentPageNumber"  value="{{$items->currentPage()}}">
                        <button type="submit" class="p-1 btn btn-outline-info btn-block mr-2" onclick="">Search</button>
                        <a href="{{ route('addVideo') }}" class="btn btn-sm btn-primary btn-block">+ Create New Video</a>
                    </form>
                </span>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                Title
            </div>
            <div class="col-md-3">
                Category
            </div>
            <div class="col-md-2">
                Keywords
            </div>
            <div class="col-md-3">
                Status
            </div>
            <div class="col-md-1">
                Action
            </div>
        </div>
        <hr class="col-12 result-header-hr">

        @foreach($items as $item)
            <div class="row">
                <div class="col-md-3" id="id_{{$item->id}}">
                    {{$item->title}}
                </div>
                <div class="col-md-3">
                    {{$item->VideoCategory->short_description}}
                </div>
                <div class="col-md-2">
                    Keywords
                </div>
                <div class="col-md-3">
                    {{$item->VideoProcessingState->short_description}}
                </div>
                <div class="col-md-1">
                    <a href="{{ route('EditVideo', ['id'=>$item->id]) }}">Edit</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void()" onclick='return DeleteVideo("{{$item->id}}");'>Delete</a>
                </div>
            </div>
        @endforeach

        <div class="row mt-4">
            <div class="col-md-12">
                {{ $items->appends($_GET)->links() }}
            </div>
        </div>
    </div>

    <!-- Start of Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Video Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="CloseModal('deleteModal')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your form inputs or content here -->
                    <form action="{{ url('/Video/delete') }}" method="post" id="delete">
                        @csrf
                        <div class="form-group">
                            <label for="video">Are you sure you want to delete: [<span id="videoDelete"></span>]</label>
                            <input type="hidden" class="form-control" id="videoIDDelete" name="videoIDDelete">
                            <input type="hidden" class="form-control" id="currentPageNumberDelete" name="currentPageNumberDelete"  value="{{$items->currentPage()}}">
                        </div>
                        <!-- Add more form fields as needed -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="$('#delete').submit();">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="CloseModal('deleteModal')">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Delete Modal -->    

    <script language="javascript">
        $(document).ready(function() {
        });

        function addUser()
        {
            alert("What is this supposed to do?");

            return false;
        }   // end of addUser()

        function DeleteVideo(id)
        {
            $('#videoDelete').html( ($('#id_' + id).html()).trim() );
            $('#videoIDDelete').val(id);

            $('#deleteModal').modal('show');

            return false;
        }   // end of DeleteVideo()

        function CloseModal(modalName)
        {
            $('#'+modalName).modal('hide');

            return false;
        }   // end of CloseModel()
    </script>
@endsection
