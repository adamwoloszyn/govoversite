@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 ">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Menu</span>
                </a>
                {{-- <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="#" onclick='return ShowContentRegion("BasicInformation");' class="nav-link align-middle px-0">
                            <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Basic Detail</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link align-middle px-0"  onclick='return ShowContentRegion("EditInformation");' >
                            <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Edit Information</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0 align-middle" onclick='return ShowContentRegion("Comments");' >
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Comments</span></a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0 align-middle" onclick='return ShowContentRegion("Transcript");' >
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Transcript</span></a>
                    </li>
                </ul> --}}
            </div>
        </div>

        <!-- 
            Basic Information
        -->
        <div class="col py-3">
            <div class="container BasicInformation ContentArea" style="display: none;">
                <div class="row mt-12">
                    <h1>Basic Information</h1>
                    <hr/>
                    <div class="row mt-4 justify-content-center_">
                        <div class="col-md-2">
                            <label for="Title">
                                Title
                            </label>
                        </div>
                        <div class="col-md-10">
                            {{$Video->title}}
                        </div>
                    </div> 
                    <div class="row mt-4 justify-content-center_">
                        <div class="col-md-2">
                            <label for="Title">
                                Category
                            </label>
                        </div>
                        <div class="col-md-10">
                            @foreach ($videoCategories as $aVideoCategory)
                                @if ($Video->video_category_id == $aVideoCategory->id )
                                    {{$aVideoCategory->short_description}}
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-center_">
                            <div class="col-md-2">
                                Speakers
                            </div>
                            <div class="col-md-10">
                                {{$Video->speakers}}
                            </div>
                        </div>                
                    </div>
                </div>
            </div>

            
            <!-- 
                Edit Information
            -->
            <div class="container EditInformation ContentArea" style="display: block;">
                <div class="col-md-9">
                    <div class="row mt-12">
                        <h1>Video Details</h1>
                        <hr/>
                    </div>
                    <form id="editVideoForm" action="{{ route('EditVideoUpdate') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->has('error'))
                            <div class="alert alert-danger">{{ $errors->first('error') }}</div>
                        @endif

                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                <label for="Title">
                                    Title
                                </label>
                            </div>
                            <div class="col-md-11">
                                <input name="VideoID" type="hidden" value="{{$Video->id}}"/>
                                <input name="Title" class="form-control" type="text"  value="{{ $Video->title }}"/>
                                @error('Title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                <label for="WhenWasVideoCreated">
                                    Video Created
                                </label>
                            </div>
                            <div class="col-md-11">
                                <input name="WhenWasVideoCreated" class="form-control" type="text"  value="{{ $Video->when_was_video_created }}"/>
                                @error('WhenWasVideoCreated')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> 
                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                <label for="Slug">
                                    Slug
                                </label>
                            </div>
                            <div class="col-md-11">
                                <input name="Slug" class="form-control" type="text"  value="{{ $Video->slug }}"/>
                                @error('Slug')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>                    
                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                Category
                            </div>
                            <div class="col-md-11">
                                <div class="dropdown">
                                    <select name="Category" class="form-control" >
                                        @foreach ($videoCategories as $aVideoCategory)
                                            @php
                                                $parentDescription = $aVideoCategory->parent ? $aVideoCategory->parent->long_description . ' - ' : '';
                                            @endphp
                                            <option value="{{$aVideoCategory->id}}" {{ old('Category') == $aVideoCategory->id ? 'selected' : '' }}>
                                                {{$parentDescription}}{{$aVideoCategory->long_description}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                Agenda Summary
                            </div>
                            <div class="col-md-11">
                                <textarea class="form-control" rows="5" name="AgendaSummary" placeholder="Video Agenda Summary" >{{ $Video->agendaSummary }}</textarea>
                                @error('AgendaSummary')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                Keywords
                            </div>
                            <div class="col-md-11">
                                @php
                                    $variableName = 'value';
                                @endphp
                                @foreach( $AssociatedKeyWords as $AssociatedKeyWord)
                                    <button type="button" id="Keyword_{{$AssociatedKeyWord->id}}" class="btn btn-outline-success btn-sm" onclick="return removeKeyWord({{$AssociatedKeyWord->id}});">{{$AssociatedKeyWord->keyword}} &nbsp; X</button>
                                @endforeach
                                <select multiple name="keywords[]" id="keywords" style="display:none;">
                                    @foreach( $AssociatedKeyWords as $AssociatedKeyWord)
                                        <option value="{{$AssociatedKeyWord->id}}" selected>{{$AssociatedKeyWord->keyword}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                Speakers
                            </div>
                            <div class="col-md-11">
                                <textarea class="form-control" rows="1" name="Speakers" placeholder="Speakers Involved ( Separated by commas )" >{{ $Video->speakers }}</textarea>
                                @error('Speakers')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                Video Thumbnail
                            </div>
                            <div class="col-md-11">
                                @if($Video->thumbnail)
                                    <img src="{{ asset($Video->thumbnail) }}" alt="" class="mb-4 rounded d-block" />
                                @else
                                    <p>No thumbnail selected yet. Please upload:</p>
                                @endif
                                <input class="form-control" type="file" name="Thumbnail" id="Thumbnail" /><br />
                                @error('Thumbnail')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-1">
                                Agenda Text
                            </div>
                            <div class="col-md-11">
                                <button class="btn btn-sm btn-success" id="addAgendaItem" >+</button>
                                <select name="AgendaItemType" id ="AgendaItemType" >
                                    @foreach( $AgendaTypes as $AgendaType)
                                        <option value="{{$AgendaType->id}}">{{$AgendaType->short_description}}</option>
                                    @endforeach
                                </select>

                                @php
                                    $videoAgendaItemIndex = 1;
                                    $contentMap = [];
                                @endphp

                                @foreach( $VideoAgendaItems as $VideoAgendaItem)
                                    <div id="AgendaItemHolder_{{$videoAgendaItemIndex}}" class="AgendaItemHolder">
                                        <p></p>
                                        <input class="agendaTimeStamp" type="text" value="{{$VideoAgendaItem->video_jump_point}}" value="" id="AgendaItemTimeStamp_{{$videoAgendaItemIndex}}" name="AgendaItemTimeStamp_{{$videoAgendaItemIndex}}"/>
                                        <a href="#" class="btnRemoveAgendaItem" data-id="{{$videoAgendaItemIndex}}">Remove Agenda Item</a><br />
                                        <textarea class="AgendaItemText ck-editor-text-area form-control" rows="10" id="AgendaItemText_{{$videoAgendaItemIndex}}" name="AgendaItemText_{{$videoAgendaItemIndex}}">{{$VideoAgendaItem->comment}}</textarea>
                                        @php
                                            $contentMap["trix-editor-".$videoAgendaItemIndex] = $VideoAgendaItem->comment;
                                            $videoAgendaItemIndex++;
                                        @endphp
                                    </div>    
                                @endforeach

                                <div class='endOfAgendaText' style=""></div>
                            </div>
                        </div>

                        <div class="row mt-4 justify-content-center_">
                            <div class="col-md-12  text-center">
                                <a href="{{route('Videos')}}" class="btn btn-sm btn-outline-info btn-block">Cancel</a>
                                @if ( $Video->IsProcessingComplete() )
                                    <input type="submit" class="btn btn-sm btn-success" value="Save"/>
                                @else
                                    <input type="submit" class="btn btn-sm btn-success" value="Publish"/>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 
                Comments
            -->
            <div class="container Comments ContentArea" style="display: none;">
                <div class="row mt-12">
                    <h1>Video Comments</h1>
                    <hr/>

                    Comments
                </div>
            </div>

            
            <!-- 
                Transcript
            -->
            <div class="container Transcript ContentArea" style="display: none;">
                <div class="row mt-12">
                    <h1>Transcription of video</h1>
                    <hr/>
                    <pre>
                    {{$Transcript}}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- used for load dynamic agenda item entries -->
@foreach( $AgendaTypes as $AgendaType)
    <div id='at_{{$AgendaType->id}}' style="display:none;">
        {{$AgendaType->template}}
    </div>        
@endforeach

    <script language="javascript">
        var contentMap = {!! json_encode($contentMap) !!};
        var agendaItemCount = {{$videoAgendaItemIndex}};

        $(document).ready(function() {
            function initializeCKEditor(element) {
                ClassicEditor
                .create(element)
                .then(editor => {
                    console.log('Editor created:', editor);
                })
                .catch(error => {
                    console.error('Error creating editor:', error);
                });
            }
            
            $('.ck-editor-text-area').each(function() {
                initializeCKEditor(this);
            });

            $(document).on('click', '.btnRemoveAgendaItem', function(e) {
                e.preventDefault();
                $('#AgendaItemHolder_' + $(this).attr('data-id')).remove();
                agendaItemCount--;

                var counter = 1;
                $('.AgendaItemHolder').each(function(index) {
                    // Update the ID and class of each div
                    var holderID = 'AgendaItemHolder_' + counter;
                    var txtTimeStampID = 'AgendaItemTimeStamp_' + counter;
                    var textAreaAgendaItem = 'AgendaItemText_' + counter;

                    $(this).attr('id', holderID); 
                    $(this).find('.btnRemoveAgendaItem').attr('data-id', counter); 
                    $(this).find('.agendaTimeStamp').attr('id', txtTimeStampID).attr('name', txtTimeStampID); 
                    $(this).find('textarea').attr('id', textAreaAgendaItem).attr('name', textAreaAgendaItem); 
                    
                    counter++;
                });

            });
            $('#addAgendaItem').click(function(e) {
                e.preventDefault();
                var selectedValue = $('#AgendaItemType').val();
                var text = $('#at_' + selectedValue).html();

                var agendaItemHolder = $('<div id="AgendaItemHolder_'+ agendaItemCount +'" class="AgendaItemHolder"></div>');
                var timeStamp = '<p></p><input class="agendaTimeStamp" type="text" value="00:00:00" name="AgendaItemTimeStamp_' + agendaItemCount + '" id="AgendaItemTimeStamp_' + agendaItemCount + '" />';
                var removeAgendaItem = '<a href="#" class="btnRemoveAgendaItem" data-id="' + agendaItemCount + '">Remove Agenda Item</a><br />';
                
                var editorId = 'AgendaItemText_' + agendaItemCount; 
                var textarea = $('<textarea>'+text+'</textarea>').attr('id', editorId).attr('name', editorId).attr('class','AgendaItemText');

                agendaItemHolder.append(timeStamp);
                agendaItemHolder.append(removeAgendaItem);
                agendaItemHolder.append(textarea);

                $('.endOfAgendaText').append(agendaItemHolder);

                ClassicEditor
                    .create(document.getElementById(editorId))
                    .then(editor => {
                        console.log('Editor created:', editor);
                    })
                    .catch(error => {
                        console.error('Error creating editor:', error);
                    });
                agendaItemCount++;
            });
            $('#editVideoForm').submit(function(event) {
                // Loop through all CKEditor instances
                $('.ck-editor').each(function(index, element) {
                });
                return true;
            });
        });
        // function ShowContentRegion(contentToShow)
        // {
        //     $('.ContentArea').hide();

        //     $('.' + contentToShow).show();

        //     return false;
        // }   // end of ShowContentRegion()

        function removeKeyWord(id)
        {
            var buttonID = "#Keyword_" + id;
            $(buttonID).hide();

            var select = $('#keywords');
            select.find('option[value="' + id + '"]').prop('selected', false);

            return false;
        }   // end of removeKeyWord()

        function AddAgendaItem()
        {
            // var inputControl = '<input type="hidden" id="trix-content" value="hi hi hi " />';
            // var trixEditor = '<trix-editor input="trix-content"></trix-editor>';
            // document.getElementById("input").innerHTML = inputControl;
            // setTimeout(function(){
            //     console.log(inputControl);
            //     console.log(trixEditor);
                
            //     document.getElementById("editor").innerHTML = trixEditor;
            // }, 1000);

            // var item = $('.endOfAgendaText');

            // var selectedValue = $('#AgendaItemType').val();
            // var text = $('#at_' + selectedValue).html();

            // var input = $('<input type="hidden" id="AgendaItemText_' + agendaItemCount + '"  name="AgendaItemText_' + agendaItemCount + '" value="">'); // Creating a new text box element

            // //var textBox = $('<trix-editor id="trix-editor-' + agendaItemCount + '" class="trix-content"></trix-editor>'); // Creating a new text box element
            
            // var textBox = $('<textarea class="form-control" rows="10" name="AgendaItemText_' + agendaItemCount + '" >' + text + '</textarea>'); // Creating a new text box element

            // 
            // item.before(input); // Inserting the text box after the selected item
            // item.before(textBox); // Inserting the text box after the selected item
            
            // var editorElement = document.getElementById('trix-editor-' + agendaItemCount);

            //agendaItemCount++;

            //return false;
        }   // end of AddAgendaItem()

        
    </script>
@endsection
