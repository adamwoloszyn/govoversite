<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Helpers\ViewRouter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Videos;
use App\Models\VideoCategories;
use App\Models\User;
use App\Models\Video_AgendaItemTimeStamps;
use App\Models\Video_Keywords;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    private $numEntries = 3;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    { 
        $vr = new ViewRouter();

        $allVideoCategories = VideoCategories::GetAllEnabled();
        if($request->queryString != "")
        {
            $videoIDs = Video_AgendaItemTimeStamps::where('comment', 'LIKE', '%' . $request->queryString . '%')->pluck('video_id');

            if($request->category != "")
            {
                $videos = Videos::whereIn('id', $videoIDs )
                    ->where('video_category_id', $request->category)
                    ->orderBy('when_was_video_created', 'desc')
                    ->paginate($this->numEntries);
            }
            else
            {
                $videos = Videos::whereIn('id', $videoIDs )
                    ->orderBy('when_was_video_created', 'desc')
                    ->paginate($this->numEntries);
            }
        }
        else if($request->category != "")
        {
            $videos = Videos::where('is_enabled', '=', true)
                ->where('video_category_id', $request->category)
                ->orderBy('when_was_video_created', 'desc')
                ->paginate($this->numEntries);
        }
        else
        {
            $videos = Videos::where('is_enabled', '=', true)
                ->orderBy('when_was_video_created', 'desc')
                ->paginate($this->numEntries);
        }
        $viewName = $vr->determinePageView("index");

        // determine if a subscriber role has a valid subscription
        if ($vr->IsASubscriber())
        {
            $currentUser = Auth::user();
            $user = User::find($currentUser->id);
            if ( !$user->HasAValidSubscription())
            {
                //$viewName = "home.homeSubscriberNeedToPay";
                return Redirect::to(route('UserSettings'));
            }        
        }
        $data = [
            'videoCategories' => $allVideoCategories,
            'videos' => $videos,
            'success' => session('success')
        ];
        return view( $viewName )->with($data);
    }   // end of index()

    public function recommended(Request $request) 
    {
        $vr = new ViewRouter();
        $videos = "";

        // Log::info("1");

        $currentUser = User::find(Auth::id());
        $keyWordIDs = $currentUser->Keywords()->pluck('id');

        // Log::info($keyWordIDs);

        $videoIDWithKeywords = Video_Keywords::whereIn( 'keyword_id', $keyWordIDs)
            ->where('is_enabled','=', 1)
            ->pluck('video_id')
            ->unique(); 

        // Log::info($videoIDWithKeywords);

        if($request->queryString != "")
        {
            // Log::info("1 - querystring");
            $videoIDs = Video_AgendaItemTimeStamps::whereIn('video_id', $videoIDWithKeywords )
                ->where('comment', 'LIKE', '%' . $request->queryString . '%')
                ->pluck('video_id')->unique();
        }
        else
        {
            // Log::info("1 - no querystring");
            $videoIDs = Video_AgendaItemTimeStamps::whereIn('video_id', $videoIDWithKeywords )
                ->pluck('video_id')->unique();
        }

        $allVideoCategories = VideoCategories::GetAllEnabled();

        if($request->category != "")
        {
            // Log::info("2");
            $videos = Videos::whereIn('id', $videoIDs )
                ->where('is_enabled', '=', 1)
                ->where('video_category_id', $request->category)
                ->orderBy('when_was_video_created', 'desc')
                ->paginate($this->numEntries);
        }
        else
        {
            // Log::info("3");
            $videos = Videos::whereIn('id', $videoIDs )
                ->where('is_enabled', '=', 1)
                ->orderBy('when_was_video_created', 'desc')
                ->paginate($this->numEntries); 
        }

        // determine if a subscriber role has a valid subscription
        if ($vr->IsASubscriber())
        {
            $currentUser = Auth::user();
            $user = User::find($currentUser->id);
            if ( !$user->HasAValidSubscription())
            {
                //$viewName = "home.homeSubscriberNeedToPay";
                return Redirect::to(route('UserSettings'));
            }
        }
        $data = [
            'videoCategories' => $allVideoCategories,
            'videos' => $videos,
        ];
        return view( 'home.recommended' )->with($data);
    }   // end of recommended()

    public function contact()
    {
        return view('home.contact');
    }   // end of contact()

    public function donate()
    {
        return view('home.donate');
    }   // end of donate()

}   // end of HomeController class