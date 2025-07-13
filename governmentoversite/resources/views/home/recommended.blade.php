@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3 filters d-none d-md-block">
                <div class="accordion accordion-flush" id="accordionFlushExample">
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
                                                <li><a class="btnCategoryFilter" data-category="{{$subcategory->id}}" href="">{{ $subcategory->short_description }}</a></li>
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
                        <a class="nav-link " href="/" >Recent Videos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/recommended" data-bs-toggle="tab" >Recommended Videos</a>
                    </li>
                </ul>
                <div class="filter d-block d-md-none float-end">
                        <img src="{{ asset('images/icon-filter.png') }}" alt="" width="24" class="" />
                    </div>
                <div class="text-end col-md-5 float-end searchbox">
                    <form action="{{ route('recommended') }}" method="get" enctype="multipart/form-data">
                        <input type="text" id='queryString' name='queryString' class="border p-1 rounded-1 w-50 display-inline-block" placeholder="Keywords" aria-label="Keywords" aria-describedby="basic-addon2">
                        <input class="btn btn-outline-info" type="submit" title="Search">
                    </form>
                </div>

                <div class="tab-content">
                   <div class="tab-pane active" id="recommendedvideos"><!-- opening recommended videos -->
                        <div class="row mb-4 mt-4"> 
                            @foreach($videos as $video)
                                <div class="col-lg-4 mb-4">
                                    <div class="videoListing pb-2 rounded border border-medium">
                                        <h6 class="pt-3 pb-3 p-2 text-dark bg-light m-0">{{$video->VideoCategory->short_description}}</h6>
                                        <a class="text-decoration-none text-dark" href="{{ route('Video.view', ['slug' => $video->slug]) }}">
                                            @if($video->thumbnail)
                                                <img src="{{ asset($video->thumbnail) }}" alt="" class="w-100 mb-2" />
                                            @else
                                                <img src="{{ asset('images/sampler.png') }}" alt="" class="w-100 mb-2" />
                                            @endif
                                        </a>
                                        <h5 class="p-2 text-dark">{{ $video->title }}</h5>
                                        <h6 class="p-2 text-dark">{{$video->when_was_video_created}}</h6>
                                        <p class="p-2 mb-2">{{$video->description}} <span class="text-secondary small">(Description)</span></p>
                                        <p class="p-2 m-0 border-bottom border-medium collapsible-button collapsed" data-bs-toggle="collapse" data-bs-target="#agendacollapse_video1">Agenda Item: XX</p>
                                            <div class="collapse p-2" id="agendacollapse_video1">
                                                Agenda text will come here
                                            </div>
                                        <p class="p-2 m-0 border-bottom border-medium">@foreach ($video->AssocatedKeywords() as $keyword)
                                            {{ $keyword->keyword}}
                                            @endforeach</p>
                                        <p class="p-2 m-0">{{$video->speakers}}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12">
                            {{ $videos->appends($_GET)->links() }}
                        </div>
                    </div>
                 <!-- closing receommended videos -->
                </div>
                    

    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Video Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Carroll County NH Commission</h5>
                <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil reiciendis optio cumque, ducimus quidem deserunt reprehenderit autem tempora quod molestiae sequi id doloribus? Nemo perferendis cumque nesciunt alias cum natus!
                </p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Optio saepe fugiat deserunt iure, animi enim veritatis aspernatur eius ratione cupiditate recusandae dolorum dolores voluptate quia quasi voluptatum illo possimus. Tenetur!</p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa quidem deleniti, eaque molestias aperiam nam magni corrupti sunt quos delectus autem, temporibus atque optio hic cumque quo magnam! Obcaecati, hic!

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Video Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>New Hampshire Fish and Game Commission Meetings</h5>
                <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil reiciendis optio cumque, ducimus quidem deserunt reprehenderit autem tempora quod molestiae sequi id doloribus? Nemo perferendis cumque nesciunt alias cum natus!
                </p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Optio saepe fugiat deserunt iure, animi enim veritatis aspernatur eius ratione cupiditate recusandae dolorum dolores voluptate quia quasi voluptatum illo possimus. Tenetur!</p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa quidem deleniti, eaque molestias aperiam nam magni corrupti sunt quos delectus autem, temporibus atque optio hic cumque quo magnam! Obcaecati, hic!

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="exampleModalLong3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Video Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Lakes Region Porcupines Meetings</h5>
                <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil reiciendis optio cumque, ducimus quidem deserunt reprehenderit autem tempora quod molestiae sequi id doloribus? Nemo perferendis cumque nesciunt alias cum natus!
                </p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Optio saepe fugiat deserunt iure, animi enim veritatis aspernatur eius ratione cupiditate recusandae dolorum dolores voluptate quia quasi voluptatum illo possimus. Tenetur!</p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa quidem deleniti, eaque molestias aperiam nam magni corrupti sunt quos delectus autem, temporibus atque optio hic cumque quo magnam! Obcaecati, hic!

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="exampleModalLong4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Keyword Subscription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Keywords</h5>
                <div>
                    <ul>
                        <li>Veterans tax credit</li>
                        <li>Veterans</li>
                        <li>Union Meadows Hike</li>
                    </ul>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>        


<script language="javascript">
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

        // accordionItem.find('.accordion-button').addClass('collapsed');
        // accordionItem.find('.accordion-collapse').removeClass('show');
    }else{
        var targetButton = $('a.btnCategoryFilter:first');
        // Find the parent accordion item
        var accordionItem = targetButton.closest('.accordion-item');

        // Expand the parent accordion item
        // accordionItem.find('.accordion-button').removeClass('collapsed');
        // accordionItem.find('.accordion-collapse').addClass('show');
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

</script>

        
@endsection
