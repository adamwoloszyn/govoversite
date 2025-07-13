<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class CRONJobBaseController extends Controller
{
    public function DebugOutput($message)
    {
        if (  env('CRON_JOB_DEBUG_ENABLED') )
        {
            Log::info($message);
        }
    }   // end of DebugOutput()

    public function DebugErrorOutput($message)
    {
        if (  env('CRON_JOB_DEBUG_ENABLED') )
        {
            Log::error($message);
        }
    }   // end of DebugErrorOutput()
}   // end of CRONJobBaseController class