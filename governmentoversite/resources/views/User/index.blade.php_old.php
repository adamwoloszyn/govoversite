@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>List of Users</strong>
            </div>
            <div class="col-md-6 text-end" >
                <span class="">
                    <form action="{{ url('/Users/search') }}" method="post" id="search">
                        @csrf
                        <input type="text" id="q" name="q" placeholder="Search for Users">
                        <input type="hidden" class="form-control" id="currentPageNumber" name="currentPageNumber"  value="{{$items->currentPage()}}">
                        <button type="submit" class="btn btn-sm btn-outline-info btn-block" onclick="">Search</button>
                        <button type="button" class="btn btn-sm btn-success btn-block" onclick="return addUser();">+ Add User</button>
                    </form>
                </span>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-2">
                Name
            </div>
            <div class="col-md-3">
                Email
            </div>
            <div class="col-md-1">
                Role
            </div>
            <div class="col-md-2">
                Subscription Date
            </div>
            <div class="col-md-2">
                Renewal Date
            </div>
            <div class="col-md-2">
                Action
            </div>
        </div>
        <hr class="col-12 result-header-hr">

        @foreach($items as $item)
            <div class="row">
                <div class="col-md-2" id="id_{{$item->id}}">
                    {{$item->name}}
                </div>
                <div class="col-md-3">
                    {{$item->email}}
                </div>
                <div class="col-md-1">
                    {{$item->role}}
                </div>
                <div class="col-md-2">
                    {{ is_null($item->subscriptionDate) ? '' : (new DateTime($item->subscriptionDate))->format('m-d-Y') }}
                </div>
                <div class="col-md-2">
                    {{ is_null($item->renewalDate) ? '' : (new DateTime($item->renewalDate))->format('m-d-Y') }}
                </div>
                <div class="col-md-2">
                    <a href="javascript:void()" onclick='return DeleteUser("{{$item->id}}");'>Delete</a>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete User Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="CloseModal('deleteModal')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your form inputs or content here -->
                    <form action="{{ url('/Users/delete') }}" method="post" id="delete">
                        @csrf
                        <div class="form-group">
                            <label for="user">Are you sure you want to delete: [<span id="userDelete"></span>]</label>
                            <input type="hidden" class="form-control" id="userIDDelete" name="userIDDelete">
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

        function DeleteUser(id)
        {
            $('#userDelete').html( ($('#id_' + id).html()).trim() );
            $('#userIDDelete').val(id);

            $('#deleteModal').modal('show');

            return false;
        }   // end of DeleteUser()

        function CloseModal(modalName)
        {
            $('#'+modalName).modal('hide');

            return false;
        }   // end of CloseModel()
    </script>
@endsection
