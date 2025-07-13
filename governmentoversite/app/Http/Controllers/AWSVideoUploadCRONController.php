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

class AWSVideoUploadCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $progressListener = function ($dl, $dl2, $bytesUploaded, $fileSize) {
            echo("-------------------\n");
            echo($dl . "\n");
            echo($dl2 . "\n");
            echo($bytesUploaded . "\n");
            echo($fileSize . "\n");
            echo("-------------------\n");
            if($fileSize > 0){
                
                echo("Progress: " . (($fileSize / $bytesUploaded) * 100) . "%\n");
                //Log::info("Progress: " . (($bytesUploaded / $fileSize) * 100) . "%\n");
            }
        };

        $message = "Starting AWS Video Upload CRON";
        parent::DebugOutput($message);

        // videos that need to be uploaded to AWS
        $VideosToWorkOn = VideoProcessingStates::GetCreateSubDirectoryInAWS();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);
        
        $videosToProcess = $VideosToWorkOn->Videos()
            ->where('is_enabled', '=', true)
            ->get();
        parent::DebugOutput("Number Of Videos to upload to AWS: " . $videosToProcess->count());
        Log::Info("Number Of Videos to upload to AWS: " . $videosToProcess->count());

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            $oldStatus = $currentVideo->video_processing_state_id;
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " [" . $currentVideo->title . "] " . $currentVideo->videofilelocalpath;
                $currentVideo->UpdateAuditLog("Attempt to upload videos into AWS S3");

                // change state to uploading to AWS
                $currentVideo->VideoProcessingState()->associate($nextState);
                $currentVideo->save();

                Log::Info("set to 'uploading': " . $currentVideoTitleBeingWorkedOn);

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
                $pathInfo = pathinfo($currentVideo->videofilelocalpath);
                $fileName = $pathInfo['filename']; 
                $extension = $pathInfo['extension']; 
                $fileNameAndExtension = $fileName . "." . $extension;
                
                
			    
			    

                parent::DebugOutput("Starting S3 Upload");

				parent::DebugOutput("KEY: " . $currentVideo->aws_subdirectory . '/' . $fileNameAndExtension);
				parent::DebugOutput("SOURCE FILE: " . $currentVideo->videofilelocalpath);
                // upload file to AWS
/*
                $result = $s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),                              
                    'Key' =>  $currentVideo->aws_subdirectory . '/' . $fileNameAndExtension, // what to name file in S3
                    //'Body' => Storage::get( $currentVideo->videofilelocalpath ),    // local file
                    'SourceFile' => $currentVideo->videofilelocalpath,              // local file
                    //'ACL' => 'public-read',
                    '@http' => [
                        'progress' => $progressListener
                    ]
                ]);
*/
				$localFilePath = $currentVideo->videofilelocalpath;
				$s3Bucket = env('AWS_BUCKET');
				$s3Path = $currentVideo->aws_subdirectory . '/' . $fileNameAndExtension;

				$command = "aws s3 cp {$localFilePath} s3://{$s3Bucket}/{$s3Path}";
				exec($command, $output, $return_var);
				
				if ($return_var === 0) {
					// Success
					Log::Info("success!!!: ");
                    $currentVideo->UpdateAuditLog("Successfully uploaded video into AWS S3");
                    parent::DebugOutput("Successfully uploaded video into AWS S3");

                    $currentVideo->videofileAWSpath = $fileNameAndExtension;

                    // change state to uploaded to AWS
                    $nextState2 = VideoProcessingStates::GetNextState($nextState);
                    $currentVideo->VideoProcessingState()->associate($nextState2);
                    $currentVideo->save();
                    Log::Info("set to 'uploaded': " . $currentVideoTitleBeingWorkedOn);

                    $currentVideo->UpdateAuditLog("State change into AWS Successfully uploaded");
				} else {
					// Error
                    echo("failureeee: " . $command);
                    echo($return_var);
					Log::Info("failureeee: " . $command);
				}
                
                
            }
            catch(Exception $e)
            {
	            parent::DebugOutput("ERRORED OUT ON S3");
	            Log::Info("ERRORED OUT ON S3");
	            parent::DebugOutput($e);
                Log::Info($e);
                $currentVideo->video_processing_state_id = $oldStatus;
                $currentVideo->save();

                Log::Info("set to 'needs uploading': " . $currentVideoTitleBeingWorkedOn);
                
                if ($e instanceof AwsException) {
                    parent::DebugErrorOutput("Problem uploading video $currentVideoTitleBeingWorkedOn into AWS");
                }

                parent::DebugErrorOutput($e);

                $currentVideo->UpdateAuditLog($e);
            }

        }   // end of foreach processing videos to upload to AWS

        parent::DebugOutput('end of AWSVideoUploadCRONController __invoke()');
    }   // end of __invoke()
}   // end of AWSVideoUploadCRONController class