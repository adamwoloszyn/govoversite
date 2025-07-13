<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;
use App\Models\Video_AgendaItemTimeStamps;
use App\Models\Videos;
// AWS S3 Stuff
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Exception;

class AWSTranscriptionUploadCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting AWS Transciption Upload CRON";
        parent::DebugOutput($message);

        // videos that need to be uploaded to AWS
        $VideosToWorkOn = VideoProcessingStates::GetSonixTranscriptionFileDownloaded();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput("Number Of Transcripts to upload to AWS: " . $videosToProcess->count());

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " [" . $currentVideo->title . "] " . $currentVideo->transcriptfilelocalpath;
                $currentVideo->UpdateAuditLog("Attempt to upload transcript into AWS S3");

                parent::DebugOutput($currentVideoTitleBeingWorkedOn);

                $s3 = new S3Client([
                    'version' => 'latest',
                    'region' => env('AWS_DEFAULT_REGION'),
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY')
                    ]
                ]);

                // extract file name and extension
                $pathInfo = pathinfo($currentVideo->transcriptfilelocalpath);
                $fileName = $pathInfo['filename']; 
                $extension = $pathInfo['extension']; 
                $fileNameAndExtension = $fileName . "." . $extension;

                // upload file to AWS
                $s3->putObject([
                    'Bucket'        => env('AWS_BUCKET'),                              
                    'Key'           => $currentVideo->aws_subdirectory  . '/' . $fileNameAndExtension,  // what to name file in S3
                    'SourceFile'    => $currentVideo->transcriptfilelocalpath,                          // local file
                ]);

                $currentVideo->UpdateAuditLog("Successfully uploaded transcript into AWS S3");

                $currentVideo->transcriptfileAWSpath = $fileNameAndExtension;

                // change state to uploaded to AWS
                $currentVideo->VideoProcessingState()->associate($nextState);
                $currentVideo->save();

                $currentVideo->UpdateAuditLog("State change into AWS Transcript Successfully uploaded");
            }
            catch(Exception $e)
            {
                if ($e instanceof AwsException) {
                    parent::DebugErrorOutput("Problem uploading Transcript $currentVideoTitleBeingWorkedOn into AWS");
                }

                parent::DebugErrorOutput($e);

                $currentVideo->UpdateAuditLog($e);
            }

        }   // end of foreach processing transcription to upload to AWS

        parent::DebugOutput('end of AWSTranscriptionUploadCRONController __invoke()');
    }   // end of __invoke()
}   // end of AWSTranscriptionUploadCRONController class