<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use App\Models\Videos;
use App\Models\VideoCategories;

use Exception;

class VideoController extends Controller
{
    public function __construct()
    {
        // only allow logged in users to access
        //$this->middleware('auth', ['except' => [] ]); // this allows you to allow certain pages not requiring auth/auth
        $this->middleware('auth');
    }   // end of constructor

    public $entriesPerPage = 10;

    public function index()
    {
        $items = Videos::where('is_enabled', true)
        ->paginate($this->entriesPerPage);

        //dd($items->first()->VideoCategory);

        return view( "Video.index" )->with('items', $items);
    }   // end of index()

    /**
     * Show the form for creating a new resource.
     * 
     * Must be an admin
     */
    public function create()
    {
        $user = Auth::user();

        if ( !$user->isAdmin())
        {
            abort(401, 'Your credentials do not allow you to access this resource.');
        }

        return view('Video.create');
    }   // end of create()

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // // $car = new Cars;
        // // $car->make = $request->input('make');
        // // $car->founded = $request->input('founded');
        // // $car->description = $request->input('description');
        // // $car->save();

        // $car = Cars::create(
        //     [
        //         'make' => $request->input('make'),
        //         'founded' => $request->input('founded'),
        //         'description' => $request->input('description')
        //     ]
        // );


        // // $car = Cars::make(
        // //     [
        // //         'make' => $request->input('make'),
        // //         'founded' => $request->input('founded'),
        // //         'description' => $request->input('description')
        // //     ]
        // // );
        // // $car->save();


        // return redirect('cars');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $car = Cars::find($id)->firstOrFail();



        // return view('cars.show')->with('car', $car);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $car = Cars::find($id);

        // return view('cars.edit')->with('car', $car);
    }

    public function view(string $slug)
    {
        
        $allVideoCategories = VideoCategories::GetAllEnabled();

        $video = Videos::where('slug', $slug)->first();
        $transcriptRequest = Http::get('https://d1k8qdd7igci1.cloudfront.net/'.$video->aws_subdirectory.'/'.$video->transcriptfileAWSpath);
        $transcriptText = $transcriptRequest->body();
        $formattedContent = nl2br($transcriptText);

        $formattedContent = str_replace($video->videofileAWSpath, "", $formattedContent);

        $data = [
            'videoCategories' => $allVideoCategories,
            'video' => $video,
            'transcriptText' => $formattedContent
        ];

        return view('Video.view')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $car = Cars::where('id', $id)
        // ->update(
        //     [
        //         'make' => $request->input('make'),
        //         'founded' => $request->input('founded'),
        //         'description' => $request->input('description')
        //     ]
        // );

        // return redirect('cars');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $car = Cars::find($id)->first();
        
        // $car->delete();

        // return redirect('cars');
    }   // end of destroy()

            /**
     * Add a keyword
     */
    public function delete(Request $request)
    {
        $page = 1;

        try
        {
            $value = $request->input('videoIDDelete');
            $page = $request->input('currentPageNumberDelete');

            //dd($value);

            $entity = Videos::find($value);
        
            $entity->is_enabled=false;

            $entity->save();      
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }

        return redirect('Video?page=' . $page);
    }   // end of delete()

    /**
     * Display a listing of the resource based on a query parameter
     */
    public function search(Request $request)
    {
        $q = $request->input('q');

        $items = Videos::where('title','like', "%" . $q . "%")
            ->where('is_enabled', true)
            ->paginate($this->entriesPerPage);

        return view( "Video.index" )->with('items', $items);
    }   // end of search()
}   // end of VideoController class
