<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;
use App\Models\Videos;
use App\Models\Keywords;
use App\Models\Video_Keywords;
use App\Models\User;

use App\Parsing\KeywordTranscriptScanner;

use Exception;

class TranscriptionParseCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting Parsing Transciption CRON";
        parent::DebugOutput($message);

        // Processed transcriptions after being uploaded to AWS
        $VideosToWorkOn = VideoProcessingStates::GetTranscriptionUploadedIntoAWS();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput("Number Of Transcripts to Parse: " . $videosToProcess->count());

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " [" . $currentVideo->title . "] " . $currentVideo->transcriptfilelocalpath;
                $currentVideo->UpdateAuditLog( "Attempt to parse transcript" );

                //parent::DebugOutput($currentVideoTitleBeingWorkedOn);
                // remove currently associated keywords
                $currentAssociatedKeywords = $currentVideo->Video_Keywords();
                foreach( $currentAssociatedKeywords as $currentAssociatedKeyword )
                {
                    $currentAssociatedKeyword->is_enabled = 0;
                    $currentAssociatedKeyword->save();
                }

                $keywordProcesser = new KeywordTranscriptScanner();

                // find keywords in transcription file
                $associatedKeywordOccurrances = $keywordProcesser->ProcessFile($currentVideo->id, $currentVideo->transcriptfilelocalpath);

                // save found keywords entries
                foreach($associatedKeywordOccurrances as $ako)
                {
                    $ako->save();
                }

                // change state transcription parsed
                $currentVideo->VideoProcessingState()->associate($nextState);
                $currentVideo->save();

                $currentVideo->UpdateAuditLog("State change into Transcript Parsed Successfully");
            }
            catch(Exception $e)
            {
                log::error($e);

                $currentVideo->UpdateAuditLog($e);
            }

        }   // end of foreach parsing transcription        

        parent::DebugOutput('end of TranscriptionParseCRONController __invoke()');
    }   // end of __invoke()
}   // end of TranscriptionParseCRONController class