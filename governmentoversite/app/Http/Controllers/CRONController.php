<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
 
class CRONController extends Controller
{
    private $file_path;
    private $file_contents;

    private function openTranscript($fileName)
    {
        $this->file_path = public_path($fileName)  or die("Unable to open file: [" . $fileName . "]");
    }

    /**
     * Handle the incoming request.
     */
        /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting Parser";
        Log::info($message);

        $this->openTranscript('working\transcripts\small.txt');
        
        $this->file_contents = file_get_contents($this->file_path);

        $this->Output();        
    }

    public function __invoke_(Request $request)
    {
        $message = "Starting Parser";
        Log::info($message);

        $this->file_path = public_path('working\transcripts\small.txtd')  or die("Unable to open file!");
        $this->file_contents = file_get_contents($this->file_path);

        $this->Output();        
    }

    protected function Output()
    {
        Log::info($this->file_path);
        Log::info($this->file_contents);
    }
}
