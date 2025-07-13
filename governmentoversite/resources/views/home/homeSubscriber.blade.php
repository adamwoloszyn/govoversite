@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3 d-none d-md-block filters">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    @foreach ($videoCategories as $category)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading-{{ $category->id }}">
                                @if ($loop->first)
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $category->id }}" aria-expanded="false" aria-controls="flush-collapse-{{ $category->id }}">
                                        {{ $category->long_description }}
                                    </button>
                                @else
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $category->id }}" aria-expanded="false" aria-controls="flush-collapse-{{ $category->id }}">
                                        {{ $category->long_description }}
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
                                                <li><a class="btnCategoryFilter" data-category="{{$subcategory->id}}" href="">{{ $subcategory->long_description }}</a></li>
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
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Recent Videos</a>
                    </li>
                </ul>
                <div class="filter d-block d-md-none float-end">
                        <img src="{{ asset('images/icon-filter.png') }}" alt="" width="24" class="" />
                    </div>
                <div class="text-end col-md-5 float-end searchbox">
                    <form action="{{ route('home') }}" method="get" enctype="multipart/form-data">
                        <input type="text" id='queryString' name='queryString' class="border p-1 rounded-1 w-50 display-inline-block" placeholder="Keywords" aria-label="Keywords" aria-describedby="basic-addon2">
                        <input class="btn btn-outline-info" type="submit" title="Search">
                    </form>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="recentvideos"><!-- opening recent videos -->
                        <div class="row mb-4 mt-4"> 
                            @if(count($videos) > 0)
                                @foreach($videos as $video)
                                    <div class="col-lg-4 mb-4">
                                        <div class="videoListing pb-2 rounded border border-medium">
                                            <a class="text-decoration-none text-dark" href="{{ route('Video.view', ['slug' => $video->slug]) }}">
                                                @if($video->thumbnail)
                                                    <img src="{{ asset($video->thumbnail) }}" alt="" class="w-100 mb-2" />
                                                @else
                                                    <img src="{{ asset('images/sampler.png') }}" alt="" class="w-100 mb-2" />
                                                @endif
                                            </a>
                                            <h5 class="p-2 text-dark">{{ $video->title }}</h5>
                                            
<!--
                                            <p class="p-2 m-0">
                                                @foreach ($video->AssocatedKeywords() as $keyword)
                                                {{ ucfirst($keyword->keyword) }}@if (!$loop->last); @endif
                                                @endforeach</p>
-->
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>There are no videos that match your criteria. <a href="/">Click here</a> to clear your filter.</p>
                            @endif
                        </div>
                        <div class="col-md-12">
                            {{ $videos->appends($_GET)->links() }}
                        </div>
                    </div>
                     <!-- closing recent videos -->
                </div>
            </div>
            
        </div>
    </div>
    
    @if ($success)
    <div class="modal-overlay">
        <div class="modal" style="display:block;" id="exampleModalLong1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Thank you for Subscribing!</h5>
                    </div>
                    <div class="modal-body">
                        <h5>Browse and View Recent Government Meetings</h5>
                        <p>
                            Browse through the ever-expanding video and audio library of government meetings. You can search for specific meetings, filter meeting results by category, or by chronological order. These features are accessible through the main video page.
                        </p>
                        <h5>Agenda Summary and Transcripts</h5>
                        <p>
                            While you are watching a government meeting, you can view the items discussed via the agenda summary or follow along with a full transcript. The agenda summary allows you to jump to the moment a topic is mentioned during the conversation by clicking on the relevant timestamp.
                        </p>
                        <h5>Keywords and Topic Notifications</h5>
                        <p>
                            Choose what you want to hear from GovernmentOversite. In your profile page, you can select keywords and phrases and be notified via email or text as soon as they are mentioned in a government meeting or submit a specific keyword and we will have the system listen for it.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Adjust the opacity as needed */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal {
            padding: 20px;
            border-radius: 5px;
        }

        /* Add more styling as needed */

    </style>
        
    @endif  
    <script language="javascript">

        $(document).ready(function() {
            $(document).ready(function() {
                $('.modal-footer button').click(function() {
                    $('.modal-overlay').hide();
                });

                $('.filter').click(function(){
                    $(".filters").toggleClass("d-none");
                })
            });
            
            let url = window.location.href;    
            let params = new URLSearchParams(url.split('?')[1]);
            let oldCategory = params.get('category');

            if(oldCategory != "" && oldCategory != null){
                var targetButton = $('a.btnCategoryFilter[data-category="'+oldCategory+'"]');
                $('.accordion-button').addClass('collapsed');
                $('.accordion-collapse').removeClass('show');

                // Find the parent accordion item
                var accordionItem = targetButton.closest('.accordion-item');

                // Expand the parent accordion item
                accordionItem.find('.accordion-button').removeClass('collapsed');
                accordionItem.find('.accordion-collapse').addClass('show');
            }else{
                var targetButton = $('a.btnCategoryFilter:first');
                // Find the parent accordion item
                var accordionItem = targetButton.closest('.accordion-item');

                // Expand the parent accordion item
                accordionItem.find('.accordion-button').addClass('collapsed');
                accordionItem.find('.accordion-collapse').removeClass('show');
            }

            let qQueryParameter = params.get('queryString');
            if(qQueryParameter != "" && qQueryParameter != null)
            {
                $("#queryString").val(qQueryParameter);
            }
            
            $(document).on('click', 'a.btnCategoryFilter', function(e) {
                e.preventDefault();
                let baseUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
                var newCategory = $(this).attr('data-category');                
                var urlParams = new URLSearchParams(window.location.search);

                if(oldCategory != newCategory)
                {
                    if (url.indexOf('?') > -1)
                    {
                        qs = urlParams.get('queryString');

                        if ( qs == null )
                        {
                            url = baseUrl + '?category=' + newCategory;
                        }
                        else
                        {
                            url = baseUrl + '?category=' + newCategory + "&queryString=" + qs;
                        }
                    }
                    else 
                    {
                        url += '?category='+newCategory
                    }

                    window.location.href = url;
                }
            });
        });
    </script>
    
@endsection
