<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;
use App\Services\SonixAI;

use Exception;

class SonixTranscriptionDownLoadCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting Sonix.AI Transcription Download CRON";
        parent::DebugOutput($message);

        $sonixService = new SonixAI();

        // transcriptions to download
        $VideosToWorkOn = VideoProcessingStates::GetSonixTranscriptionDone();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput( "Number Of Transcriptions to download from Sonix.AI: " . $videosToProcess->count() );

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " " . $currentVideo->title . " " . $currentVideo->videofilelocalpath;
                $currentVideo->UpdateAuditLog( "Attempting to download transcription status from Sonix.AI" );

                parent::DebugOutput($currentVideoTitleBeingWorkedOn);

                // create local file name
                $pathInfo = pathinfo($currentVideo->videofilelocalpath);
                //$fileName = $pathInfo['filename']; 
                $fileName = str_replace("video-", "transcript-", $pathInfo['filename']);
                $fileNameAndExtension = public_path( 'working\\transcripts\\' ) . $fileName . ".txt";
                parent::DebugOutput("Trying to download Transctipion file to: " . $fileNameAndExtension);

                // Download transciption from Sonix.AI
                $result = $sonixService->DownloadTranscription($currentVideo->sonix_ai_media_id, $fileNameAndExtension);

                $currentVideo->transcriptfilelocalpath = $fileNameAndExtension;

                $currentVideo->UpdateAuditLog("Successfully downloaded transcription from Sonix.AI");

                if ( $result )
                {
                    parent::DebugOutput("Transcription download done");

                    // change state to uploaded to Sonix
                    $currentVideo->VideoProcessingState()->associate($nextState);

                    $currentVideo->UpdateAuditLog("Sonix.AI Transcription Downloaded");
                }
                else
                {
                    parent::DebugOutput("Transcription NOT downloaded");

                    $currentVideo->UpdateAuditLog("Sonix.AI Transcription NOT Downloaded");
                }

                $currentVideo->save();
            }
            catch(Exception $e)
            {
                parent::DebugOutput($e);

                $currentVideo->UpdateAuditLog($e);
                $currentVideo->save();
            }

        }   // end of foreach processing videos to upload to Sonix

        parent::DebugOutput('end of SonixTranscriptionDownLoadCRONController __invoke()');
    }   // end of __invoke()
}   // end of SonixTranscriptionDownLoadCRONController class