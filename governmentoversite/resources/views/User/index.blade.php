@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <strong>List of Users </strong>
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

        <table class="mt-4 w-100 table" >
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" aria-label="Checkbox for selecting all">
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Role
                    </th>
                    <th>
                        Subscription Date
                    </th>
                    <th>
                        Renewal Date
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>

                @foreach($items as $item)
                    <tr>
                        <td>
                            <input type="checkbox" aria-label="Checkbox for following row">
                        </td>
                        <td id="id_{{$item->id}}">
                            {{$item->name}}
                        </td>
                        <td>
                            {{$item->email}}
                        </td>
                        <td>
                            {{$item->role}}
                        </td>
                        <td>
                            {{ is_null($item->subscriptionDate) ? '' : (new DateTime($item->subscriptionDate))->format('m-d-Y') }}
                        </td>
                        <td>
                            {{ is_null($item->renewalDate) ? '' : (new DateTime($item->renewalDate))->format('m-d-Y') }}
                        </td>
                        <td>
                            <a href="javascript:void()" onclick='return DeleteUser("{{$item->id}}");' class="text-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <div class="row">
            <div class="col-12">
                 <nav aria-label="Pagination justify-content-center block">
                    <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                    <li class="page-item"><a class="page-link" href="#">7</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div> --}}
        <div class="row mt-4">
            <div class="col-md-12">
                {{-- {{$items->currentPage()}}
                {{ $items->count() }}

                {{ $items->hasPages() }}

                {{$items->total()}} --}}


                {{ $items->appends($_GET)->links() }}
                
            </div>
            {{-- <span>Show:</span>
            <select id="pageSize" onchange="changePageSize(event)">
                <option value="10" {{ $items->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $items->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $items->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $items->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select> --}}
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

        // function changePageSize(event) {
        //     var pageSize = event.target.value;
        //     window.location.href = document.URL + "&pageSize=" + pageSize;
        // }
    </script>
@endsection
