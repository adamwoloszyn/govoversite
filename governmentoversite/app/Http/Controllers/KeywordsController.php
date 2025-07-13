<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use \App\Models\Keywords;
use Exception;

class KeywordsController extends Controller
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
        $items = Keywords::GetAllEnabledKeywords($this->entriesPerPage);

        return view( "keywords.index" )->with('items', $items);
    }   // end of index()

    /**
     * Display a listing of the resource based on a query parameter
     */
    public function search(Request $request)
    {
        $q = $request->input('q');

        $items = Keywords::where('keyword','like', "%" . $q . "%")
            ->where('is_enabled', true)
            ->paginate($this->entriesPerPage);

        return view( "keywords.index" )->with('items', $items);
    }   // end of search()

    /**
     * Add a keyword
     */
    public function add(Request $request)
    {
        $page = 1;

        try
        {
            $value = $request->input('keywordAdd');
            $page = $request->input('currentPageNumberAdd');

            $model = Keywords::create([
                'keyword' => $value,
            ]);
            $model->save();        
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }

        return redirect('Keywords?page=' . $page);
    }   // end of add()

    /**
     * Remove a keyword
     */
    public function delete(Request $request)
    {
        $page = 1;

        try
        {
            $value = $request->input('keywordIDDelete');
            $page = $request->input('currentPageNumberDelete');

            //dd($value);

            $entity = Keywords::find($value);
        
            $entity->is_enabled=false;
            //dd($entity);
            $entity->save();      
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }

        return redirect('Keywords?page=' . $page);
    }   // end of delete()

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect('Keywords');
    }   // end of create()

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect('Keywords');
    }   // end of store()

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect('Keywords');
    }   // end of show()

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect('Keywords');
    }   // end of edit()

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $page = 1;

        try{
            $value = strtolower( $request->input('keywordEdit') );
            $id = $request->input('keywordIDEdit');
            $page = $request->input('currentPageNumberEdit');

            // Log::info($value);
            // Log::info($id);

            $keyword = Keywords::find($id);
            $keyword->keyword = $value;
            $keyword->save();
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }

        return redirect('Keywords?page=' . $page);
    }   // end of update()

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $entity = Keywords::find($id)->first();
        
        $entity->is_enabled=false;
        $entity->save();

        return redirect('Keywords');
    }   // end of destroy

}   // end of KeywordsController class