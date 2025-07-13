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

class AWSVideoCompressionCRONController extends CRONJobBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $message = "Starting AWS Video Compression Job Submission CRON";
        parent::DebugOutput($message);

        // videos that need to be compressed in AWS
        $VideosToWorkOn = VideoProcessingStates::GetUploadedToAWS();

        $nextState = VideoProcessingStates::GetNextState($VideosToWorkOn);

        $videosToProcess = $VideosToWorkOn->Videos()->get();
        parent::DebugOutput("Number Of Videos to compress in AWS: " . $videosToProcess->count());

        $currentVideoTitleBeingWorkedOn = "";

        foreach ($videosToProcess as $currentVideo) {
            try{
                $currentVideoTitleBeingWorkedOn =  "id:" . $currentVideo->id . " [" . $currentVideo->title . "] " . $currentVideo->videofilelocalpath;
                $currentVideo->UpdateAuditLog("Attempt to compress video into AWS S3");

                parent::DebugOutput($currentVideoTitleBeingWorkedOn);

                /** START OF CONVERSION */
                /** 
                 * HERE ARE SOME USEFUL LINKS:
                 * Main Folder - I grab from this since you are uploading here: https://s3.console.aws.amazon.com/s3/buckets/gov-over-videos?region=us-east-2&tab=objects#
                 * Output folder - this is where videos end up after conversion: https://s3.console.aws.amazon.com/s3/buckets/gov-over-videos?region=us-east-2&prefix=output/&showversions=false
                 * MediaConvert dashboard - this is where you can see the job and where it is at in the process. You should get "IN PROGRESS" and then "COMPLETE" if things go correctly, which they really should.
                 * I am referencing your file that you just uploaded to S3 here so I don't have to download/upload or use the local file.
                 */
                //$objectKey = $currentVideo->aws_subdirectory . '/' . $currentVideo->videofileAWSpath;
                $accountId = env('AWS_ACCOUNT_ID');
                
                // Step 1: Set up AWS Elemental MediaConvert client
                $mediaConvertClient = new MediaConvertClient([
                    'version' => 'latest',
                    'region' => env('AWS_DEFAULT_REGION'),
                    'endpoint' => env('AWS_MEDIA_CONVERT_ENDPOINT'),
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY')
                    ]
                ]);

                try {
                    $jobSettings = [
                        'Role' => "arn:aws:iam::$accountId:role/AWSMediaConvertRole",
                        'Settings' => [
                            'Inputs' => [
                                [
                                    'AudioSelectors' => [
                                        'Audio Selector 1' => [
                                            'Offset' => 0,
                                            'DefaultSelection' => 'DEFAULT',
                                            'ProgramSelection' => 1,
                                        ],
                                    ],
                                    'FileInput' => 's3://' . env('AWS_BUCKET') . '/' . $currentVideo->aws_subdirectory . '/' . $currentVideo->videofileAWSpath,
                                ],
                            ],
                            'OutputGroups' => [
                                [
                                    'OutputGroupSettings' => [
                                        'Type' => 'FILE_GROUP_SETTINGS',
                                        'FileGroupSettings' => [
                                            'Destination' => 's3://' . env('AWS_BUCKET') . '/' . $currentVideo->aws_subdirectory . '/' . 'compressed_' . pathinfo($currentVideo->videofileAWSpath, PATHINFO_FILENAME),
                                        ],
                                    ],
                                    'Outputs' => [
                                        [
                                            'VideoDescription' => [
                                                'CodecSettings' => [
                                                    'Codec' => 'H_264',
                                                    'H264Settings' => [
                                                        'Bitrate' => 1500000,
                                                        'Height' => 720,
                                                        'Width' => 1280,
                                                        'RateControlMode' => 'CBR',
                                                        'CodecProfile' => 'MAIN',
                                                    ],
                                                ],
                                            ],
                                            'AudioDescriptions' => [
                                                [
                                                    'AudioTypeControl' => 'FOLLOW_INPUT',
                                                    'CodecSettings' => [
                                                        'Codec' => 'AAC',
                                                        'AacSettings' => [
                                                            'Bitrate' => 320000,
                                                            'SampleRate' => 48000,
                                                            'CodingMode' => 'CODING_MODE_2_0',
                                                        ],
                                                    ],
                                                    'AudioSelectorName' => 'Default', // Provide the correct audio selector name
                                                    'AudioDescriptionName' => 'Default Audio', // Provide the correct audio description name
                                                    'SelectorGroup' => 1,
                                                ],
                                            ],
                                            'ContainerSettings' => [
                                                'Container' => 'MP4',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ];

                    $response = $mediaConvertClient->createJob($jobSettings);
                    $jobId = $response['Job']['Id'];
                    $currentVideo->compressedvideofileAWSpath = 'compressed_' . $currentVideo->videofileAWSpath;

                    // save job id to allow for status checks to occur
                    $currentVideo->compressionjobid = $jobId;
                    $message = "AWS Compression Job Submission ID" . $jobId;
                    $currentVideo->UpdateAuditLog($message);
                    parent::DebugOutput($message);
                    
                    $currentVideo->UpdateAuditLog("Successfully created compressed job id: " . $jobId);

                    // change state 
                    $currentVideo->VideoProcessingState()->associate($nextState);
                    $currentVideo->save();

                    $currentVideo->UpdateAuditLog("State change successful");
                }
                catch(Exception $e)
                {
                    if ($e instanceof AwsException) 
                    {
                        parent::DebugErrorOutput($e);
                        parent::DebugErrorOutput("Problem creating MediaConvert job for video $currentVideoTitleBeingWorkedOn into AWS");
                    }

                    $currentVideo->UpdateAuditLog($e);
                }
                /** END OF CONVERSION */
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

        parent::DebugOutput('end of AWS Video Compression job submission CRON Controller __invoke()');
    }   // end of __invoke()
}   // end of AWSVideoCompressionCRONController class