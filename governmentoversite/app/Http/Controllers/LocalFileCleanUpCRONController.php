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

use Exception;

class LocalFileCleanUpCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $runTest = false;
        $message = "Starting Local File Clean Up CRON";
        parent::DebugOutput($message);

        $directory = public_path('working\\transcripts');
        $message = "Examine Transcripts in " . $directory;
        parent::DebugOutput($message);
        $fileNames = scandir($directory);

        foreach ($fileNames as $fileName) {
            if ($fileName !== '.' && $fileName !== '..' && str_starts_with($fileName, 'transcript-')) {
                $fileName = $directory . '\\' . $fileName;
                parent::DebugOutput('Candidate file name: ' . $fileName);

                $result = Videos::where('transcriptfilelocalpath', 'like', str_replace('\\', "\\\\", $fileName ) )->get();
                //parent::DebugOutput($result);
                $performDelete = false;
                if ( $result->count() > 0 )
                {
                    $performDelete = $result->every(function ($item) {
                        return $item['video_processing_state_id'] == VideoProcessingStates::GetEmailsSent()->id;
                    });
                }
                else
                {
                    // if no video refers to this file, delete file
                    $performDelete = true;
                }

                if ( $performDelete )
                {
                    parent::DebugOutput('Delete this file name: ' . $fileName);
                    unlink($fileName);
                }
                else
                {
                    parent::DebugOutput('Do NOT delete this file name: ' . $fileName);
                }
            }
        }

        $directory = public_path('working\\videos');
        $message = "Examine Videos in " . $directory;
        parent::DebugOutput($message);
        $fileNames = scandir($directory);

        foreach ($fileNames as $fileName) {
            if ($fileName !== '.' && $fileName !== '..' && str_starts_with($fileName, 'video-')) {
                $fileName = $directory . '\\' . $fileName;
                parent::DebugOutput('Candidate file name: ' . $fileName);

                $result = Videos::where('videofilelocalpath', 'like', str_replace('\\', "\\\\", $fileName ) )->get();
                //parent::DebugOutput($result);
                $performDelete = false;
                if ( $result->count() > 0 )
                {
                    $performDelete = $result->every(function ($item) {
                        return $item['video_processing_state_id'] == VideoProcessingStates::GetEmailsSent()->id;
                    });
                }
                else
                {
                    // if no video refers to this file, delete file
                    $performDelete = true;
                }

                if ( $performDelete )
                {
                    parent::DebugOutput('Delete this file name: ' . $fileName);
                    unlink($fileName);
                }
                else
                {
                    parent::DebugOutput('Do NOT delete this file name: ' . $fileName);
                }
            }
        }

        parent::DebugOutput('end of LocalFileCleanUpCRONController __invoke()');
    }   // end of __invoke()
}   // end of LocalFileCleanUpCRONController class