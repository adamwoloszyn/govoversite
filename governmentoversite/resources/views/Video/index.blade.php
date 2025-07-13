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

        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" aria-label="Checkbox for selecting all"></th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Keywords</th>
                    <th>Status</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>
                     <input type="checkbox" aria-label="Checkbox for following row">
                </td>
                <td id="id_{{$item->id}}">
                    {{$item->title}}
                </td>
                <td>
                    {{$item->VideoCategory->short_description}}
                </td>
                <td>
                    Keywords
                </td>
                <td>
                    {{$item->VideoProcessingState->short_description}}
                </td>
                <td>
                    @if ( $item->CanBeEditted() )
                        <a href="{{ route('EditVideo', ['id'=>$item->id]) }}" class="text-primary display-inline-block text-decoration-none">Edit</a>
                    @else
                        Processing
                    @endif
                </td>
                <td>
                    <a href="javascript:void()" onclick='return DeleteVideo("{{$item->id}}");' class='text-decoration-none display-inline-block ms-3 text-danger'>Delete</a>
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

    <!-- Start of Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Video Confirmation</h5>
                    <button type="button" class="close bg-transparent border-0" data-dismiss="modal" aria-label="Close"  onclick="CloseModal('deleteModal')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your form inputs or content here -->
                    <form action="{{ url('/Video/delete') }}" method="post" id="delete">
                        @csrf
                        <div class="form-group">
                            <label for="video">Are you sure you want to delete: <span id="videoDelete"></span></label>
                            <input type="hidden" class="form-control" id="videoIDDelete" name="videoIDDelete">
                            <input type="hidden" class="form-control" id="currentPageNumberDelete" name="currentPageNumberDelete"  value="{{$items->currentPage()}}">
                        </div>
                        <!-- Add more form fields as needed -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="$('#delete').submit();">Delete</button>
                    <!--<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="CloseModal('deleteModal')">Cancel</button>-->
                </div>
            </div>
        </div>
    </div>
    <!-- End of Delete Modal -->    

    <script language="javascript">
        $(document).ready(function() {
            setTimeout(function(){
                window.location.reload(1);
            }, 5000);
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
