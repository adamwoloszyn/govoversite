<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

use \App\Models\User;
use Exception;

class UsersController extends Controller
{
    // Apply auth middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    public $entriesPerPage = 10;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = User::where('is_enabled', true)
            ->paginate($this->entriesPerPage);

            //dd($items);

        return view( "User.index" )->with('items', $items);
    }   // end of index()

    public function updateProfilePicture(Request $request)
    {
        $user = User::findOrFail($request->user()->id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('images/' . $imageName);
    
            // Store the image file
            $image = Image::make($image)->resize(200, 200); // Resize the image if needed
            $image->save($imagePath);
            $user->image = 'images/' . $imageName;
        }
    
        // Save the user
        $user->save();

        // Redirect or return response as needed
        return redirect()->route('UserSettings');
    }
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail($request->user()->id);

        //detach everyhting from user so we can grab from the forms
        foreach ($user->KeywordPaired as $keyword) {
            $user->KeywordPaired()->detach($keyword);
        }
        $preExistingUserKeywords = [];
         if(!is_null($request->keywords_subscribed_to) && count($request->keywords_subscribed_to) > 0){
            foreach ($request->keywords_subscribed_to as $keyword) {
                // loop through the form's pre-existing keywords and attach to current user.
                array_push($preExistingUserKeywords, $keyword);
            }
        }
        $newUserKeywords = [];
        if(!is_null($request->keywords_to_add) && count($request->keywords_to_add) > 0){
            foreach ($request->keywords_to_add as $keyword) {
                // loop through the form's selected keywords and attach to current user.
                array_push($newUserKeywords, $keyword);
            }
        }

        $allKeywordsToAttach = array_merge($preExistingUserKeywords, $newUserKeywords);

        //Finally attach all keywords to User
        foreach ($allKeywordsToAttach as $keyword) {
            $user->KeywordPaired()->attach($keyword);
        }
        
        // Redirect or return response as needed
        return redirect()->route('UserSettings');
    }
    /**
     * Delete a user
     */
    public function delete(Request $request)
    {
        $page = 1;

        try
        {
            $value = $request->input('userIDDelete');
            $page = $request->input('currentPageNumberDelete');

            //dd($value);

            $entity = User::find($value);
        
            $entity->is_enabled=false;
            //dd($entity);
            $entity->save();      
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }

        return redirect('Users?page=' . $page);
    }   // end of delete()

    /**
     * Display a listing of the resource based on a query parameter
     */
    public function search(Request $request)
    {
        $q = $request->input('q');

        $items = User::where('name','like', "%" . $q . "%")
            ->where('is_enabled', true)
            ->paginate($this->entriesPerPage);

        return view( "User.index" )->with('items', $items);
    }   // end of search()

    /*
    ** Unsubscribe current user
    */
    public function unsubscribe()
    {
        $currentUser = Auth::user();
        $user = User::find($currentUser->id);

        $subscriptions = $user->ValidSubscriptions();
        foreach( $subscriptions as $subscription)
        {
            $subscription->is_enabled=false;
            $subscription->save();
        }

        return redirect()->route('home');
    }
}
