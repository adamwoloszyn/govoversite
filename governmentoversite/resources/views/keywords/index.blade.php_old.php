@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>List of Listening Keywords</strong>
            </div>
            <div class="col-md-6 text-right" >
                <span class="">
                    <form action="{{ url('/Keywords/search') }}" method="post" id="search">
                        @csrf
                        <input type="text" id="q" name="q" placeholder="Search for Keywords">
                        <input type="hidden" class="form-control" id="currentPageNumber" name="currentPageNumber"  value="{{$items->currentPage()}}">
                        <button type="submit" class="btn btn-sm btn-outline-info btn-block" onclick="">Search</button>
                        <button type="button" class="btn btn-sm btn-success btn-block" onclick="return addKeyword();">+ Add Keyword</button>
                    </form>
                </span>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-9">
                Keyword
            </div>
            <div class="col-md-3">
                Action
            </div>
        </div>
        <hr class="col-12 result-header-hr">

        @foreach($items as $item)
            <div class="row">
                <div class="col-md-9" id="id_{{$item->id}}">
                    {{$item->keyword}}
                </div>
                <div class="col-md-3">
                    <a href="javascript:void()" onclick='return EditKeyword("{{$item->id}}");'>Edit</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void()" onclick='return DeleteKeyword("{{$item->id}}");'>Delete</a>
                </div>
            </div>
        @endforeach

        <div class="row mt-4">
            <div class="col-md-12">
            {{ $items->appends($_GET)->links() }} <!--&nbsp;Number of Entires {{$items->count()}}-->
            </div>
        </div>        
    </div>

<!-- Start of Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Keyword</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="CloseModal('editModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form inputs or content here -->
                <form action="{{ url('/Keywords/update') }}" method="post" id="update">
                    @csrf
                    <div class="form-group">
                        <label for="keyword">Keyword:</label>
                        <input type="text" class="form-control" id="keywordEdit" name="keywordEdit">
                        <input type="hidden" class="form-control" id="keywordIDEdit"  name="keywordIDEdit">
                        <input type="hidden" class="form-control" id="currentPageNumberEdit" name="currentPageNumberEdit"  value="{{$items->currentPage()}}">
                    </div>
                    <!-- Add more form fields as needed -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="$('#update').submit();">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="CloseModal('editModal')">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Edit Modal -->

<!-- Start of Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Keyword</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="CloseModal('addModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form inputs or content here -->
                <form action="{{ url('/Keywords/add') }}" method="post" id="add">
                    @csrf
                    <div class="form-group">
                        <label for="keyword">Keyword:</label>
                        <input type="text" class="form-control" id="keywordAdd" name="keywordAdd">
                        <input type="hidden" class="form-control" id="currentPageNumberAdd" name="currentPageNumberAdd"  value="{{$items->currentPage()}}">
                    </div>
                    <!-- Add more form fields as needed -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="$('#add').submit();">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="CloseModal('addModal')">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Add Modal -->

<!-- Start of Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Keyword Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="CloseModal('deleteModal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your form inputs or content here -->
                <form action="{{ url('/Keywords/delete') }}" method="post" id="delete">
                    @csrf
                    <div class="form-group">
                        <label for="keyword">Are you sure you want to delete: [<span id="keywordDelete"></span>]</label>
                        <input type="hidden" class="form-control" id="keywordIDDelete" name="keywordIDDelete">
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

    function addKeyword()
    {
        $('#keywordAdd').val( "" );

        $('#addModal').modal('show');

        return false;
    }   // end of addKeyword()

    function EditKeyword(id)
    {
        $('#keywordEdit').val( ($('#id_' + id).html()).trim() );
        $('#keywordIDEdit').val(id);

        $('#editModal').modal('show');

        return false;
    }   // end of EditKeyword()

    function DeleteKeyword(id)
    {
        $('#keywordDelete').html( ($('#id_' + id).html()).trim() );
        $('#keywordIDDelete').val(id);

        $('#deleteModal').modal('show');

        return false;
    }   // end of DeleteKeyword()

    function CloseModal(modalName)
    {
        $('#'+modalName).modal('hide');

        return false;
    }   // end of CloseModel()
</script>

@endsection
