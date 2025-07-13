<?php

namespace App\Http\Controllers;

use App\Models\Keywords;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;
use App\Models\Video_AgendaItemTimeStamps;
use App\Models\Videos;
use App\Models\User;

// AWS S3 Stuff
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

use App\Parsing\KeywordTranscriptScanner;

use Exception;

class PlayGroundCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $runTest = false;
        $message = "Starting PlayGround CRON";
        parent::DebugOutput($message);

        if ( $runTest )
        {
            $parser = new KeywordTranscriptScanner();

            $associatedKeywordOccurrances = $parser->ProcessFile( 1, 'C:\LDG\GovernmentOversight\GOPrototype\public\working\transcripts\transcript-2023_07_03__02_19_50-1688350790-681594630.txt' );

            // save found keywords entries
            foreach($associatedKeywordOccurrances as $ako)
            {
                $keyword = Keywords::find($ako->keyword_id);
                parent::DebugOutput('keyword: ' . $keyword->keyword);    
            }
            // $user = User::find(3);
            // parent::DebugOutput('ID: ' . $user->id);
            // parent::DebugOutput('Name: ' . $user->name);
            // parent::DebugOutput('EMail: ' . $user->email);

            // foreach ($user->Notifications as $Notification) {
            //     parent::DebugOutput($Notification->id);
            //     parent::DebugOutput($Notification->emailbody);
            // } 

            // foreach(User::all() as $currentUser)
            // {
            //     parent::DebugOutput('Was User notified?: ' . ($currentUser->HasUserBeenNotifiedAboutThisVideo(1) ? "yes " : "no ") . $currentUser->name . " of video " . 1 );
            // }

            //  $keyword = Keywords::find(6);
            //  $user->Keywords()->attach($keyword);
            //$user->Keywords()->detach($keyword);

            // foreach ($user->Keywords as $keyword) {
            //     parent::DebugOutput($keyword->keyword);
            // }            

            // $keyword = Keywords::find(1);
            // parent::DebugOutput('ID: ' . $keyword->id);
            // parent::DebugOutput('Keyword: ' . $keyword->keyword);

            // foreach ($keyword->Users as $loopUser) {
            //     parent::DebugOutput($loopUser->name);
            // }

            // $vTS = $testVideo->AgendaItemTimeStamps()->get();
            // parent::DebugOutput($vTS->count());

            // foreach ($vTS as $v) {
            //     parent::DebugOutput($v->comment);
            // }

            // $testVideo->AgendaItemTimeStamps()->create(
            //     [
            //         'comment' => "add programmatically"
            //     ]
            // );
        }

        parent::DebugOutput('end of PlayGroundCRONController __invoke()');
    }   // end of __invoke()
}   // end of PlayGroundCRONController class