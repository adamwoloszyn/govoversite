<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OldVideo;


class OldVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secondaryModel = new OldVideo();
        $secondaryModel->setConnection('secondary');
        $results = $secondaryModel->get();

        return $results;
        // Or you can use the DB facade to perform raw queries
        // $results = DB::connection('secondary')->select('SELECT * FROM table');

        // Further logic or response handling
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
