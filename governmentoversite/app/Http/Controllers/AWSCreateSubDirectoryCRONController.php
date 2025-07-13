<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

use App\Models\VideoProcessingStates;

// AWS S3 Stuff
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\MediaConvert\MediaConvertClient;
use Aws\Exception\AwsException;
use Exception;
use App\Jobs\AWSVideoUpload;

class AWSCreateSubDirectoryCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting AWS Create Subdirectory CRON";
        parent::DebugOutput($message);

        // Videos to create subdirectories
        $VideosToWorkOn = VideoProcessingStates::GetAdminUploadedVideo();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput("Number Of Videos to create subdirectories in AWS: " . $videosToProcess->count());

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try
            {
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " [" . $currentVideo->title . "] " . $currentVideo->videofilelocalpath;
                $currentVideo->UpdateAuditLog("Attempt to create subdiretory in AWS S3");

                $s3 = new S3Client([
                    'version' => 'latest',
                    'region' => env('AWS_DEFAULT_REGION'),
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY')
                    ]
                ]);

                $dateTime = Carbon::now();          // Create a DateTime object representing the current date/time
                $format = 'Y_m_d_H_i_s';            // The desired format for the date/time string
                $subdirectoryKey = 'video_meta_data_' . str_pad($currentVideo->id, 6, '0', STR_PAD_LEFT) . '_' . $dateTime->format($format);

                Log::info($subdirectoryKey);

                // create sub directory
                $s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $subdirectoryKey . '/',
                ]);

                // remember name of subdirectory created
                $currentVideo->aws_subdirectory = $subdirectoryKey;

                // change state to next workflow state
                $currentVideo->VideoProcessingState()->associate($nextState);
                $currentVideo->save();

                $currentVideo->UpdateAuditLog("State change successful");
            }
            catch(Exception $e)
            {
                if ($e instanceof AwsException) 
                {
                    parent::DebugErrorOutput("Problem create SubDirectory for video $currentVideoTitleBeingWorkedOn into AWS");
                }

                parent::DebugErrorOutput($e);

                $currentVideo->UpdateAuditLog($e);
            }

        }   // end of foreach processing videos to upload to AWS
        if(count($videosToProcess) > 0){
            //dispatch the long running job for uploading videos 
            AWSVideoUpload::dispatch()
            ->onConnection('database')
            ->onQueue('adam');
        }
        parent::DebugOutput('end of AWS Create SubDirectory CRON Controller __invoke()');
    }   // end of __invoke()
}   // end of AWSCreateSubDirectoryCRONController class