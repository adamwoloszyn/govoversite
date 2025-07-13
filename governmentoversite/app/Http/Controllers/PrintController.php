<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Videos;

class PrintController extends Controller
{
    //
    public function index(string $slug)
    {
        $videoSlug = $slug;
        $video = Videos::where('slug', $slug)->first();

        $data = [
            'agendaSummary' => $video->agendaSummary
        ];

        return view('print.index', $data);
    }

}
