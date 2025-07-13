<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;
use App\Services\SonixAI;

use Exception;

class SonixUploadCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting Sonix.AI Upload CRON";
        parent::DebugOutput($message);

        $sonixService = new SonixAI();

        // videos that need to be uploaded to Sonix
        $VideosToWorkOn = VideoProcessingStates::GetCompressedAWS();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput("Number Of Videos to upload to Sonix: " . $videosToProcess->count());

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " " . $currentVideo->title . " " . $currentVideo->videofilelocalpath;
                $currentVideo->UpdateAuditLog("Attempt to upload into Sonix.AI");

                parent::DebugOutput($currentVideoTitleBeingWorkedOn);

                // upload to Sonix.AI
                //$result = $sonixService->UploadFile($currentVideo->videofilelocalpath);
                parent::DebugOutput($currentVideo->CompressedVideoStreamURL());
                $result = $sonixService->UploadFileVIAURL($currentVideo->CompressedVideoStreamURL());
                
                parent::DebugOutput($result);

                $currentVideo->UpdateAuditLog("Successfully uploaded to Sonix.AI");

                // change state to uploaded to Sonix
                $currentVideo->VideoProcessingState()->associate($nextState);
                $currentVideo->sonix_ai_media_id = $result;
                $currentVideo->UpdateAuditLog("State change to Sonix.AI Successfully uploaded");

                $currentVideo->save();
            }
            catch(Exception $e)
            {
                parent::DebugOutput($e);

                $currentVideo->UpdateAuditLog($e);
            }

        }   // end of foreach processing videos to upload to Sonix

        parent::DebugOutput('end of SonixUploadCRONController __invoke()');
    }   // end of __invoke()
}   // end of SonixUploadCRONController class