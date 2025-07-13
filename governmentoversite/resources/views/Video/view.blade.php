@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3" id="playerMenu">
              <div class="accordion accordion-flush  d-none d-md-block" id="accordionFlushExample">
                @foreach ($videoCategories as $category)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading-{{ $category->id }}">
                            @if ($loop->first)
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-{{ $category->id }}" aria-expanded="false" aria-controls="flush-collapse-{{ $category->id }}">
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
                                            <li><a class="btnCategoryFilter" data-category="{{$subcategory->id}}" href="/?category={{$subcategory->id}}">{{ $subcategory->long_description }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif  
                    </div>
                @endforeach
              </div>
            </div>

            <div class="col-md-9" id="playerDetails">
                <div class="videoDetails">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{$video->title}}</h4>
                            <h5>Category:  {{$video->VideoCategory->long_description}}</h5>
                        </div>
                        {{-- <div class="col-md-4 text-end  d-none d-md-block">
                            <form action="{{ url('/Video/search') }}" method="post" id="search">
                                <input type="text" id="q" name="q" class="p-2 pe-3 ps-3 w-50 rounded-3 border border-1" placeholder="Search videos by title, keyword, date">
                                <button type="submit" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 btn-block mr-2" onclick="">Search</button>
                            </form>
                        </div> --}}
                    </div>
                    <div class="videoPlayerFake"></div>
                    <div class="videoPlayer mt-4 mb-4">
                      <video
                        playsinline
                        id="myVideo"
                        class="video-js"
                        controls
                        preload="auto"
                        width="640"
                        height="360"
                        @if($video->thumbnail)
                          poster="{{ asset($video->thumbnail) }}"
                        @else
                          poster="{{ asset('images/sampler.png') }}"
                        @endif
                        data-setup='{"techOrder": ["html5"], "timeFormat": "H:mm:ss"}'
                      >
                      <source src="https://d1k8qdd7igci1.cloudfront.net/{{$video->aws_subdirectory}}/{{$video->videofileAWSpath}}" type="video/mp4" />
                        <p class="vjs-no-js">
                        To view this video please enable JavaScript, and consider upgrading to a
                        web browser that
                        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                        </p>
                      </video>
                      <br /><br />
                      <input type="range" id="speedControl" min="0.5" max="2.0" step="0.1" value="1.0">
                      <span id="speedIndicator">1.0x</span>
                      <button id="btnPlay" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 btn-block mr-2">Play</button> 
                      <button id="btnPause" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 btn-block mr-2">Pause</button>
                      {{-- <button id="btnSeek" class="btn-outline-info">Seek to: </button><input type="text" id="txtTime" value="" /> --}}                      
                    </div>
                </div>
                <div class="videoDescription mt-4 mb-4">
                    <table class="table">
                        <tr>
                            <td><strong>Keywords</strong></td>
                            <td>
                              @foreach ($video->AssocatedKeywords() as $keyword)
                              {{ ucfirst($keyword->keyword) }}@if (!$loop->last); @endif
                              @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Speakers</strong></td>
                            <td>{{$video->speakers}}</td>
                        </tr>
                    </table>
                </div>

                <ul id="information_tabs" class="nav nav-tabs mb-4">
                      {{-- <li class="nav-item">
                        <a class="nav-link active" href="#" data-bs-toggle="tab" data-bs-target="#agendasummary">Agenda Summary</a>
                      </li> --}}
                      <li class="nav-item">
                        <a class="nav-link active" href="#" data-bs-toggle="tab" data-bs-target="#agendadetails">Agenda Details</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="tab" data-bs-target="#transcipt">Transcript</a>
                      </li>
              
                </ul>

                <div class="tab-content" id="myTabContent">
                  {{-- <div class="tab-pane fade show active" id="agendasummary" role="tabpanel">
                    <a href="/print/{{ $video->slug }}" target="_blank">Print Agenda</a><br />
                    {!! $video->agendaSummary !!}
                  </div><!-- closing agenda summary --> --}}
                  <div class="tab-pane fade show active" id="agendadetails" role="tabpanel">
                    @foreach ($video->AgendaItemTimeStamps() as $timestamp)
                        <button class="p-2 pe-3 ps-3 btn btn-outline-info w-30 btn-block mr-2 btnSeek" data-id="{{$timestamp->id}}">{{ $timestamp->video_jump_point}}</button><label style="display:none;" id="txtTime_{{$timestamp->id}}">{{ $timestamp->video_jump_point}}</label>
                        {!! $timestamp->comment !!}
                        <br />
                    @endforeach
                    
                  </div> <!-- closing agenda details -->
                  
                  <div class="tab-pane fade" id="transcipt" role="tabpanel">
                      {!! $transcriptText !!}
                  </div> <!-- closing transcript -->
                </div> <!-- closing all tabs -->
                </div>
            </div>
    </div>
    <link href="https://vjs.zencdn.net/8.0.4/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/8.0.4/video.min.js"></script>
    <style>
        #playerDetails {
            overflow-x: hidden;
        }
        .videoPlayerFake{
            display: none;
            width: 640px;
            height: 390px;
        }

        /* #playerDetails.active {
        width: 100%;
        }
        #playerMenu.active {
        opacity: 0;
        height: 260px;
        } */
        .videoPlayerFake.active{
            display: block;
        }
        .videoPlayer.video-fixed {
            height: 390px;
            position: fixed;
            top: 10px;
            left: 20px;
            width: 330px;
        }

        .videoPlayer.video-fixed .myVideo-dimensions {
            width: 330px;
            height: 220px;
        }
        #information_tabs.active {
            position: fixed;
            top: 0px;
            background: #fff;
            width: 100%;
        }
        /* Target iPad (Portrait) */
        
        /* Target Most Mobile Phones (Portrait) */
        @media screen and (min-width: 320px) {
            
        }

        /* Target Most Mobile Phones (Landscape) */
        @media screen and (max-width: 480px) {
            /* Your CSS rules for Most Mobile Phones (Landscape) */
            body, div.tab-pane, div.tab-content {
                overflow-x: hidden;
            }
            div.tab-content{
                overflow-y: hidden;
            }
            .videoPlayer {
                z-index:999999;
                margin: 0!important;
                padding: 20px!important;
                width: 100%!important;
                background: #fff!important;
                height: auto!important;
                left:0px!important;
                top:0px!important;
            }
            .myVideo-dimensions {
                width: 100%;
            }
            #information_tabs.active {
                z-index:99999;
                position: fixed;
                top: 320px;
                background: #fff;
                width: 100%;
                left: 0px;
                padding:10px;
            }
        }

        @media screen and (min-width: 768px) and (max-width: 1023px) {
            div.tab-content{
                overflow-y: hidden;
            }
            .videoPlayer.video-fixed {
                z-index:999999;
                height: auto;
                position: fixed;
                top: 30px;
                left: 20px;
                width: 340px;
                background: #fff;
            }
            #information_tabs.active {
                z-index:99999;
                position: fixed;
                top:px;
                background: #fff;
                width: 100%;
                left: 0px;
                padding:10px;
            }
        }

        /* Target iPad (Landscape) */
        @media screen and (min-width: 1024px) {
            /* Your CSS rules for iPad (Landscape) */
        }

    </style>
		<script>
      
    //   var videoPlayer = document.getElementById('myVideo');
       $(document).ready(function() {
        var videoPlayer = videojs('myVideo');

        var infoTabOffset = $("#information_tabs").offset().top;
        console.log(infoTabOffset);

        $(window).scroll(function() {
          if ($(window).scrollTop() > 550) {
            $(".videoPlayer").addClass("video-fixed");
            $(".videoPlayerFake").addClass("active");
            // $("#playerDetails").addClass("active");
            // $("#playerMenu").addClass("active");            
          } else {
             $(".videoPlayer").removeClass("video-fixed");
             $(".videoPlayerFake").removeClass("active");
            // $("#playerDetails").removeClass("active");
            // $("#playerMenu").removeClass("active");
          }
          if ($(window).scrollTop() > infoTabOffset) {
            $("#information_tabs").addClass("active");       
          } else {
             $("#information_tabs").removeClass("active");
          }
        });

        function setSameHeight() {
            const boxes = $('#myTabContent div.tab-pane');
            let maxHeight = 0;

            boxes.each(function() {
                maxHeight = Math.max(maxHeight, $(this).outerHeight());
            });

            boxes.css('height', maxHeight + 'px');
        }

        setSameHeight();

        $(document).on("click", "#btnPlay",function(){
            console.log("play");
            videoPlayer.play();
        });
        $(document).on("click", "#btnPause",function(){
            console.log("pause");
            videoPlayer.pause();
        });

        $('#speedControl').on("input", function() {
	        console.log("time: " + speedControl.value);
            speedIndicator.textContent = speedControl.value + "x";
            videoPlayer.playbackRate(parseFloat(speedControl.value));            
        });
        $(document).on("click", ".btnSeek",function(){
            var id = $(this).attr('data-id');
            var time = $('#txtTime_' + $(this).attr('data-id')).text();

            var timeString = time//;"00:00:12";
            var timeParts = timeString.split(':');
            var seconds = parseInt(timeParts[0]) * 3600 + parseInt(timeParts[1]) * 60 + parseInt(timeParts[2]);

            console.log(time);
            console.log(timeParts);
            console.log(seconds);
            videoPlayer.currentTime(seconds);
            //videoPlayer.currentTime = seconds;
            return false;
        });
            $('#btnSeek').click(function(e){
                var time = $("#txtTime").val();
                console.log(time);
                var timeString = time//;"00:00:12";
                var timeParts = timeString.split(':');
                var seconds = parseInt(timeParts[0]) * 3600 + parseInt(timeParts[1]) * 60 + parseInt(timeParts[2]);

                console.log(time);
                console.log(timeParts);
                console.log(seconds);
                videoPlayer.currentTime(seconds);
                //videoPlayer.currentTime = seconds;
                return false;
            });


        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('offset')){
            var part = urlParams.get("part");
            var offset = decodeURIComponent(urlParams.get("offset"));
            var autoplay = urlParams.get("autoplay");

            var encodedTime = offset;
            var decodedTime = decodeURIComponent(encodedTime);
            var timeParts = decodedTime.split(":");
            var hours = parseInt(timeParts[0], 10);
            var minutes = parseInt(timeParts[1], 10);
            var seconds = parseInt(timeParts[2], 10);
            var totalSeconds = (hours * 3600) + (minutes * 60) + seconds;

            setTimeout(function() {
            if(autoplay == "1"){
                $('#btnPlay').click();
            }
            }, 2000);

            
            videoPlayer.currentTime(totalSeconds);
            console.log("part: " + part);
            console.log("offset: " + offset);
            console.log("autoplay: " + autoplay);
            console.log(timeParts);
        }
       });
			
    </script>
@endsection
