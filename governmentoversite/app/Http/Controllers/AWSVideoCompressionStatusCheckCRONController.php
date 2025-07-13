<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;

// AWS S3 Stuff
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\MediaConvert\MediaConvertClient;
use Aws\Exception\AwsException;
use Exception;

class AWSVideoCompressionStatusCheckCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting AWS Video Compression Job Status Check CRON";
        parent::DebugOutput($message);

        $jobStatus = "";

        // videos that need to be compressed in AWS
        $VideosToWorkOn = VideoProcessingStates::GetCompressingAWS();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput("Number Of Video Compression Jobs to test: " . $videosToProcess->count());

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " [" . $currentVideo->title . "] " . $currentVideo->compressionjobid;
                $currentVideo->UpdateAuditLog("Check compression status in AWS S3");

                parent::DebugOutput($currentVideoTitleBeingWorkedOn);

                $jobId = $currentVideo->compressionjobid;
                $region = env('AWS_DEFAULT_REGION');
                $accountId = env('AWS_ACCOUNT_ID');
        
                $jobArn = "arn:aws:mediaconvert:$region:$accountId:jobs/$jobId";

                // Your AWS credentials and region
                $credentials = [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY')
                ];
                
                // Create the MediaConvert client
                $client = new MediaConvertClient([
                    'version' => 'latest',
                    'region' => $region,
                    'endpoint' => env('AWS_MEDIA_CONVERT_ENDPOINT'),
                    'credentials' => $credentials,
                ]);

                try {
                    // Get the job status by calling the GetJob operation
                    $result = $client->getJob([
                        'Id' => $jobArn,
                    ]);

                    $jobStatus = $result['Job']['Status'];

                    $message = "Check job #: " . $jobId . " Status: " . $jobStatus;

                    $currentVideo->UpdateAuditLog($message);
                    parent::DebugOutput($message);
                }
                catch(Exception $e)
                {
                    if ($e instanceof AwsException) 
                    {
                        parent::DebugErrorOutput("Problem check job status for video $currentVideoTitleBeingWorkedOn into AWS");
                    }

                    $currentVideo->UpdateAuditLog($e);
                }

                if ( $jobStatus == "COMPLETE")
                {
                    // change state 
                    $currentVideo->VideoProcessingState()->associate($nextState);
                    $currentVideo->save();

                    $currentVideo->UpdateAuditLog("State change successful");
                }
            }
            catch(Exception $e)
            {
                if ($e instanceof AwsException) {
                    parent::DebugErrorOutput("Problem uploading video $currentVideoTitleBeingWorkedOn into AWS");
                }

                parent::DebugErrorOutput($e);

                $currentVideo->UpdateAuditLog($e);
            }
        
        }   // end of foreach processing videos

        parent::DebugOutput('end of AWS Video Compression Job Status Check CRON Controller __invoke()');
    }   // end of __invoke()
}   // end of AWSVideoCompressionStatusCheckCRONController class