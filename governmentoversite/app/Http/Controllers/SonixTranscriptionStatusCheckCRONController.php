<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;
use App\Services\SonixAI;

use Exception;

class SonixTranscriptionStatusCheckCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting Sonix.AI Transcription Status Check CRON";
        parent::DebugOutput($message);

        $sonixService = new SonixAI();

        // videos that need to be uploaded to Sonix
        $VideosToWorkOn = VideoProcessingStates::GetUploadedToSonix();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput( "Number Of Videos to check.AI Sonix Transcription status: " . $videosToProcess->count() );

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " " . $currentVideo->title . " " . $currentVideo->videofilelocalpath;
                $currentVideo->UpdateAuditLog( "Attempting to pull transcription status from Sonix.AI" );

                parent::DebugOutput($currentVideoTitleBeingWorkedOn);

                // pull transctiption status from Sonix.AI
                $result = $sonixService->IsTranscriptionCompleted($currentVideo->sonix_ai_media_id);

                $currentVideo->UpdateAuditLog("Successfully retrieved transcription status from Sonix.AI");

                if ( $result )
                {
                    parent::DebugOutput("Transcription done");

                    // change state to uploaded to Sonix
                    $currentVideo->VideoProcessingState()->associate($nextState);

                    $currentVideo->UpdateAuditLog("Sonix.AI Transcription Done");
                }
                else
                {
                    parent::DebugOutput("Transcription NOT done");

                    $currentVideo->UpdateAuditLog("Sonix.AI Transcription NOT Done");
                }

                $currentVideo->save();
            }
            catch(Exception $e)
            {
                parent::DebugOutput($e);

                $currentVideo->UpdateAuditLog($e);
            }

        }   // end of foreach processing videos to upload to Sonix

        parent::DebugOutput('end of SonixTranscriptionStatusCheckCRONController __invoke()');
    }   // end of __invoke()
}   // end of SonixTranscriptionStatusCheckCRONController class