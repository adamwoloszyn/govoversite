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
                        @foreach($channels as $channel)
                           <li><a href="{{ route('livestream.view', ['slug'=>$channel->slug])}}">{{$channel->name}}</a></li>
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
                  <h4>LIVESTREAM CHANNELS</h4>
               </div>
               <div class="col-md-6 text-md-end">
                  <form action="{{ url('/Video/search') }}" method="post" id="search">
                     <input type="text" id="q" name="q" class="p-2 pe-3 ps-3 w-50 rounded-3 border border-1" placeholder="Search videos by title, keyword, date">
                     <button type="submit" class="p-2 pe-3 ps-3 btn btn-outline-info w-30 btn-block mr-2" onclick="">Search</button>
                  </form>
                  <div class="filter d-block d-md-none float-end">
                  <img src="{{ asset('images/icon-filter.png') }}" alt="" width="24" class="" />
               </div>
               </div>

               <div class="timeframe" id="div1"></div>
			   <div class="timeframe" id="div2"></div>
            </div>
            <div class="row mb-4 mt-4">
               @foreach($channels as $channel)
                  <div class="col-lg-4 mb-4">
                     <div class="videoListing pb-2 rounded border border-medium">
                        <h6 class="pt-3 pb-3 p-2 text-dark bg-light m-0">
	                        <p class="p-0 mb-0" id="channel_{{$channel->channel_id}}" style="display:block;">Status...offline</p>
                        </h6>
                        <a data-is-live="false" id="channel_{{$channel->channel_id}}_url" class="text-decoration-none text-dark" href="{{ route('livestream.view', ['slug'=>$channel->slug])}}">
                           @if($channel->thumbnail)
                              <img src="{{ asset($channel->thumbnail) }}" alt="" class="w-100 mb-2" />
                           @else
                              <img src="{{ asset('images/sampler.png') }}" alt="" class="w-100 mb-2" />
                           @endif
                        </a>
                        <h5 class="p-2 text-dark">{{$channel->name}}</h5>
                        <p class="p-2 mb-2">{!! nl2br(e($channel->description)) !!}</span></p>
                        
                     </div>
                  </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</div>

  <script>
    // Your Bearer Token
    const token = 'mPJstGWKoS6FZSPCTvs5O8PTKYVDRPsuPn3s0L_0VjU.z2HNMRaUxjGoUWXJSn6GhcgLtrSp2_OE_qKuc2LIIc0';

    // Array of channel IDs (Replace with your own data)
    const channelIds = ['jtv2vmcollmefeivqwyy', 'hpenqa2havvecrc63ei5']; // Add more IDs as needed

    function fetchDataAndProcess() {
      channelIds.forEach((channelId, index) => {
        // URL to make the GET request to, using the channelId
        const url = `https://rest.boxcast.com/account/channels/${channelId}/broadcasts`;

        $.ajax({
          type: 'GET',
          url: url,
          headers: {
            'Authorization': `Bearer ${token}`
          },
          success: function (broadcasts) {
            // Find the corresponding div by index and update its content
            const divId = `div${index + 1}`; // IDs are "div1", "div2", etc.
            const div = $(`#${divId}`);
            
            div.empty(); // Clear existing content
            
            var hasLive = false;
            broadcasts.forEach(broadcast => {
	            if(broadcast.timeframe == "current"){
		            hasLive = true;
	            }
				const id = broadcast.id;
				const timeframe = broadcast.timeframe;
				//div.append(`Channel: ${channelId}, <br>`);
				//div.append(`ID: ${id}, Timeframe: ${timeframe}<br><br>`);
            });
            console.log(channelId + " - has live? " + hasLive);
            
            var isChannelLive = $("#channel_"+channelId+"_url").attr('data-is-live');
            
            if(hasLive){
				$("#channel_"+channelId).text("Status: Is live!");
				$("#channel_"+channelId+"_url").attr('data-is-live', true);
				
				if(isChannelLive == "false"){
					var currentURL = $("#channel_"+channelId+"_url").attr("href");
					$("#channel_"+channelId+"_url").attr("href", currentURL+"?isLive");
				}
				
            }else{
	            $("#channel_"+channelId).text("Status: Is not live");
	            //need to remove the isLive from the url.
	            //TODO
            }
          },
          error: function (error) {
            console.error(`Error for channel ID ${channelId}:`, error);
          }
        });
      });
    }

    // Function to execute the API call and data processing every 5 seconds
    function checkAndUpdateData() {
      fetchDataAndProcess();
      setTimeout(checkAndUpdateData, 2000); // Set a 5-second timeout for the next call
    }

    // Initial call to start the loop
    checkAndUpdateData();
  </script>

<script>
$(document).ready(function() {
    $('.filter').click(function(){
        $(".filters").toggleClass("d-none");
    })
});
</script>

@endsection