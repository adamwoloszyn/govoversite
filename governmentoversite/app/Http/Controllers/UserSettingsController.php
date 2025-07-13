<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserSubscription;
use App\Models\VideoCategories;
use App\Models\Keywords;

use App\Http\Controllers\Helpers\ViewRouter;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        // only allow logged in users to access
        //$this->middleware('auth', ['except' => [] ]); // this allows you to allow certain pages not requiring auth/auth
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $vr = new ViewRouter();
        $allVideoCategories = VideoCategories::GetAllEnabled();
        $user = User::findOrFail($request->user()->id);
        $user_subscription = UserSubscription::where('user_id', $user->id)->first();

        $user_keywords = $user->KeywordPaired;
        $allKeywords = Keywords::GetAllEnabledKeywords(-1)->toArray();

        $revisedSystemKeywords = [];

        foreach ($allKeywords as $keyword) {
            $existsInUserKeywords = $user_keywords->contains('keyword', $keyword['keyword']);
        
            if (!$existsInUserKeywords) {
                $revisedSystemKeywords[] = $keyword;
            }
        }
        $keywordCollection = Keywords::hydrate($revisedSystemKeywords);

        // echo("<pre>");
        //  print_r($user_keywords);
        //  print_r($allKeywords);
        //  echo("</pre>");
        //  exit;

        $data = [
            'user' => $user,
            'user_subscription' => $user_subscription,
            'videoCategories' => $allVideoCategories,
            'user_keywords' => $user_keywords,
            'system_keywords' => $keywordCollection

        ];
        return view( $vr->determinePageView("userSettings"), $data );
    }
}
