@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row mt-4">
      <div class="col-md-3">
         <div class="accordion accordion-flush d-none d-md-block filters" id="accordionFlushExample">
            <div class="accordion-item">
               <h2 class="accordion-header" id="flush-headingOne">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Channels
                  </button>
               </h2>
               <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <ul class="videoList">
                      @foreach($channels as $singleChannel)
                        <li><a href="{{ route('livestream.view', ['slug'=>$singleChannel->slug])}}">{{$singleChannel->name}}</a></li>
                      @endforeach
                    </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-9">
         <div class="videoDetails">
            <div class="row">
               <div class="col-md-6">
                  <h4>LIVESTREAM</h4>
                  <div class="filter d-block d-md-none float-end">
                  <img src="{{ asset('images/icon-filter.png') }}" alt="" width="24" class="" />
               </div>
                  <h5>Channel: {{$channel->name}}</h5>
               </div>
               <div class="col-md-6 text-end d-none d-md-block">
               </div>
               
            </div>
            <div class="videoPlayer mt-4 mb-4">
              <div id="test-player">
                <a href="https://www.boxcast.com/view/#test-video">
                  Watch Test Video on BoxCast: a smarter way to stream.
                </a>
              </div>
              <script src="https://js.boxcast.com/v3.min.js"></script>
              <script>
                boxcast('#test-player').loadChannel('{{$channel->channel_id}}', {
                  autoplay: false,
                  showTitle: true,
                  showDescription: true
                });
              </script>
            </div>
         </div>
         <div class="mt-4 mb-4" style="position:relative;height:500px;">
          <iframe src="https://js.boxcast.com/3.24.3/viewer.html?file=https%3A%2F%2Fuploads.boxcast.com%2Ffksh0nr0pcynei5un04t%2F2023-06%2Fnegpholozwh3imbsgt7t%2FTSM_Agenda_6_29_23_.pdf" frameborder="0" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%;"></iframe>
         </div>
         <!-- closing agenda summary -->
      </div>
   </div>
</div>

<script>
$(document).ready(function() {
                $('.filter').click(function(){
                    $(".filters").toggleClass("d-none");
                })
            });
</script>
@endsection
