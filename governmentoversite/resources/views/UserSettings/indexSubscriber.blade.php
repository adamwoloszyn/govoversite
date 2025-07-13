@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="accordion accordion-flush  d-none d-md-block" id="accordionFlushExample">
                @foreach ($videoCategories as $category)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading-{{ $category->id }}">
                            @if ($loop->first)
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $category->id }}" aria-expanded="false" aria-controls="flush-collapse-{{ $category->id }}">
                                    {{ $category->short_description }}
                                </button>
                            @else
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $category->id }}" aria-expanded="false" aria-controls="flush-collapse-{{ $category->id }}">
                                    {{ $category->short_description }}
                                </button>
                            @endif
                        </h2>
                        @if ($category->children_count > 0)
                            @if ($loop->first)
                                <div id="flush-collapse-{{ $category->id }}" class="accordion-collapse collapse show" aria-labelledby="flush-heading-{{ $category->id }}" data-bs-parent="#accordionFlushExample">
                            @else
                              <div id="flush-collapse-{{ $category->id }}" class="accordion-collapse collapse " aria-labelledby="flush-heading-{{ $category->id }}" data-bs-parent="#accordionFlushExample">
                            @endif
                                <div class="accordion-body">
                                    <ul class="videoList">
                                        @foreach ($category->children as $subcategory)
                                            <li>{{ $subcategory->short_description }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif  
                    </div>
                @endforeach
              </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <h4>User Settings</h4>
                    </div>
                    <div class="col-md-6 text-end d-none d-md-block">
                        <form action="{{ url('/Video/search') }}" method="post" id="search">
                            <input type="text" id="q" name="q" class="p-2 pe-3 ps-3 w-50 rounded-3 border border-1" placeholder="Search videos by title, keyword, date">
                            <button type="submit" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 btn-block mr-2" onclick="">Search</button>
                        </form>
                    </div>
                    <div class="col-md-6 mt-4 mb-4">
                        @if($user->image)
                        <img src="{{ asset($user->image) }}" alt="" class="mb-4 rounded d-block" />
                        @endif
                        {{-- <img src="{{ asset('images/profile.png') }}" alt="" class="mb-4 rounded d-block" /> --}}
                        <form method="POST" action="{{ route('users.updateProfilePicture') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Other form fields -->
                        
                            <div class="form-group">
                                <label for="image">Upload new profile image</label>
                                <input type="file" name="image" id="image">
                            </div>
                        
                            <button type="submit" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 inline-block me-2">Update Photo</button>
                        </form>
                        <h4 class="mt-4">
                            Manage Subscription
                        </h4>
                        @if($user_subscription)
                            <p>
                                Plan Name: One Year Plan <br />
                                From: {{ $user_subscription->start_date }} <br />
                                Valid Thru: {{ $user_subscription->end_date }}
                            </p>
                            <input type="hidden" id="txtCustomerNumber" value="{{$user_subscription->customer}}" />
                            <input type="hidden" id="txtSubscriptionID" value="{{$user_subscription->subscription}}" />
                            
                            <button id="btnCancelSubscription" type="button" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 inline-block me-2">Cancel Subscription</button>
                            {{-- <button id="btnRenewSubscription" type="button" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 inline-block">Renew Subscription</button> --}}
                        @else
                        <form method="POST" action="/addSubscription">
                            @csrf <!-- Include the CSRF token for Laravel forms -->
                            @if($user->customer != "")
                            <input type="hidden" id="customer" value="{{$user->customer}}" />
                            @endif
                            <button type="submit" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 inline-block me-2" >Start Subscription</button>
                          </form>
                        
                        @endif
                        
                    </div>
                    <div class="col-md-6 mt-4 mb-4">
                        <form method="POST" action="{{ route('users.updateProfile') }}">
                            @csrf <!-- Include the CSRF token for Laravel forms -->
                            <label for="fullname">Full Name</label>
                            <input id="fullname" type="text" name="fullname" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Full Name" value="{{ $user->name }}">

                            <label class="mt-3" for="email">Email</label>
                            <input id="email" type="text" name="email" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="email@domain.com" value="{{ $user->email }}">

                            <label class="mt-3"  for="phone">Phone</label>
                            <input id="phone" type="text" name="phone" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Phone" value="{{ $user->phone }}">

                            <label class="mt-3"  for="password">Edit Password</label>
                            <input id="password" type="password" name="password" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Password">

                            <label class="mt-3"  for="confirm">Confirm New Password</label>
                            <input id="confirm" type="password" name="confirm" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Confirm Password">
                            
                            <label class="mt-3 mb-2 d-block"  for="keywords">Current keyword notifications. Click the "x" to remove them.</label>
                            @if(count($user_keywords) > 0)
                                @foreach ($user_keywords as $user_keyword)
                                    <div style="float: left;">
                                        <input id="keywords_subscribed_to[]" name="keywords_subscribed_to[]" type="hidden" value="{{$user_keyword->id}}" />
                                        <button data-value="{{$user_keyword->id}}" data-text="{{$user_keyword->keyword}}" type="button" class="btnRemoveCurrentlySubscribed btn btn-outline-dark btn-sm be-2">{{$user_keyword->keyword}} <span> &times;</span></button>
                                    </div>
                                @endforeach
                            @else
                                <p>You are not subscribed to any keywords currently.</p>
                            @endif
                            <br style="clear:both;" /><br />
                            <p>Select a keyword from the list below and click the "+" to add it. Then save your profile.</p>
                            
                            <select id="ddlSystemKeywords">
                                {{$system_keywords}}
                                @foreach ($system_keywords as $system_keyword)
                                    <option value="{{$system_keyword->id}}">{{$system_keyword->keyword}}</option>
                                @endforeach
                            </select>
                            <button id="addNewKeyword" class="btn btn-sm btn-success">+</button>

                            <div id="keywordsToSave">
                                
                            </div>
                            <br style="clear:both;" />
                            
                            <div class="text-end mt-4">
                                <button type="submit" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 inline-block me-2" onclick="">Back</button>
                                <button type="submit" class="p-2 pe-3 ps-3 btn btn-primary w-30 inline-block" onclick="">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                @php
                    $currentUser = Auth::user();
                    //echo $currentUser;
                    //$user = User::find($currentUser->id);
                    $user = App\Models\User::find($currentUser->id);
                @endphp

            @if ( $user->HasAValidSubscription())
            <!--
                <div class="card mt-5">
                    <div class="card-header">Account Subscription</div>

                    <div class="card-body">
                        View subscription information
                    </div>


                        <div class="card-body">
                            <form action="{{ route('unsubscribe') }}" method="get" id="unsubscribe">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-info btn-block" onclick="">Unsubscribe</button>
                            </form>
                        </div>
                </div>
            -->
            @endif
        </div>
    </div>
</div>


    <!-- Start of Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Cancel Subscription</h5>
                    <button data-modal-name="cancelModal" type="button" class="btnCloseModal close bg-transparent border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{" method="post" id="">
                        @csrf
                        <div class="form-group">
                            <label for="keyword">Are you sure you want to cancel your subscription? If yes, please continue.</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnConfirmCancellation" type="button" class="btn btn-danger">Yes, Cancel Subscription</button>
                    <button data-modal-name="cancelModal" type="button" class="btnCloseModal btn btn-secondary" data-dismiss="modal">Close this window</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Cancel Modal -->
    <!-- Start of Renew Modal -->
    <div class="modal fade" id="renewModal" tabindex="-1" role="dialog" aria-labelledby="renewModallabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="renewModallabel">Manage Subscription</h5>
                    <button data-modal-name="renewModal" type="button" class="btnCloseModal close bg-transparent border-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{" method="post" id="">
                        @csrf
                        <div class="form-group">
                            <label for="keyword">Are you sure you'd like to manage your subscription? If so, click below.</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnGoToStripe" type="button" class="btn btn-success">Yes, go to Subscription Management</button>
                    <button data-modal-name="renewModal" type="button" class="btnCloseModal btn btn-secondary" data-dismiss="modal">Close this window</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Cancel Modal -->

    <script language="javascript">
        
        $(document).ready(function() {

            $('#addNewKeyword').click(function(e){
                e.preventDefault();
                var index = $('#ddlSystemKeywords')[0].selectedIndex;
                var text = $("#ddlSystemKeywords option:selected").text();
                var value = $("#ddlSystemKeywords option:selected").val();
                
                // remove the selected index from the DOM
                $(`#ddlSystemKeywords option:eq(${index})`).remove();

                var newKeyword = '<div style="float: left;"><button data-value="keyword_'+value+'" data-text="'+text+'" type="button" class="newlyAddedKeyword btn btn-outline-dark btn-sm be-2">'+text+'<span> &times;</span></button>';
                newKeyword += '<input id="keywords_to_add[]" name="keywords_to_add[]" type="hidden" value="'+value+'" /></div>';

                var newKeywordHolder = $('#keywordsToSave');

                newKeywordHolder.append(newKeyword);

                if ($('#ddlSystemKeywords :selected').length == 0){
                    $('#addNewKeyword').attr('disabled', 'disabled');
                }else{
                    $('#addNewKeyword').removeAttr ('disabled');
                }
                    
                return false;
            });
            $(document).on('click', 'button.newlyAddedKeyword span', function(e) {
                e.preventDefault();
                var currentKeywordId = $(this).parent().attr("data-value");
                var currentKeywordText = $(this).parent().attr("data-text");

                $('#ddlSystemKeywords').append(
                    $('<option></option>').val(currentKeywordId).html(currentKeywordText)
                );
                $(this).parent().parent().remove();
            });
            $(document).on('click', 'button.btnRemoveCurrentlySubscribed span', function(e) {
                e.preventDefault();
                var currentKeywordId = $(this).parent().attr("data-value");
                var currentKeywordText = $(this).parent().attr("data-text");

                console.log(currentKeywordId);
                console.log(currentKeywordText);
                
                $('#ddlSystemKeywords').append(
                    $('<option></option>').val(currentKeywordId).html(currentKeywordText)
                );
                $(this).parent().parent().remove();
            });

            $('#btnCancelSubscription').click(function(e){
                $('#cancelModal').modal('show');
                return false;
            });
            $('#btnManageSubscription').click(function(e){
                $('#renewModal').modal('show');
                return false;
            });

            $('#btnConfirmCancellation').click(function() {
                var customerID = $('#txtCustomerNumber').val();
                var subscriptionID = $('#txtSubscriptionID').val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var payload = {
                    customer_id: customerID,
                    subscription_id: subscriptionID
                };
                var headers = {
                    'X-CSRF-TOKEN': csrfToken
                };
                $.ajax({
                url: 'http://127.0.0.1:8000/subscription/cancel',
                type: 'POST',
                data: payload,
                headers: headers,
                success: function(response) {
                    console.log(response.updatedSubscription);
                    if(response.updatedSubscription.status=="canceled"){
                        window.location.href = 'http://127.0.0.1:8000/UserSettings';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                }
                });
            });

            $('.btnCloseModal').click(function(e){
                var modalName = $(this).data('modal-name');
                $('#'+modalName).modal('hide');
                return false;
            });
        });
    </script>
@endsection
