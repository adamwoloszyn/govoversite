<?php

namespace App\Http\Controllers;

use Aws\MediaConvert\MediaConvertClient;
use Aws\Exception\AwsException;

class MediaConvertCronController extends Controller
{
    public function convertTo720p()
    {
        /** START OF CONVERSION */
        /** 
         * HERE ARE SOME USEFUL LINKS:
         * You can fire off this job from this URL: http://127.0.0.1:8000/api/convert-video
         * Main Folder - I grab from this since you are uploading here: https://s3.console.aws.amazon.com/s3/buckets/gov-over-videos?region=us-east-2&tab=objects#
         * Output folder - this is where videos end up after conversion: https://s3.console.aws.amazon.com/s3/buckets/gov-over-videos?region=us-east-2&prefix=output/&showversions=false
         * MediaConvert dashboard - this is where you can see the job and where it is at in the process. You should get "IN PROGRESS" and then "COMPLETE" if things go correctly, which they really should.
         * I am hard referencing the TSM video in $objectKey here. 
         */
        $objectKey = 'TSM_6-29-23.mp4';
        
        // Step 1: Set up AWS Elemental MediaConvert client
        $mediaConvertClient = new MediaConvertClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'endpoint' => 'https://wa11sy9gb.mediaconvert.us-east-2.amazonaws.com',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        try {
            $jobSettings = [
                'Role' => 'arn:aws:iam::867390358133:role/AWSMediaConvertRole',
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
                            'FileInput' => 's3://' . env('AWS_BUCKET') . '/' . $objectKey,
                        ],
                    ],
                    'OutputGroups' => [
                        [
                            'OutputGroupSettings' => [
                                'Type' => 'FILE_GROUP_SETTINGS',
                                'FileGroupSettings' => [
                                    'Destination' => 's3://' . env('AWS_BUCKET') . '/output/',
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

            print_r($jobSettings);

            $response = $mediaConvertClient->createJob($jobSettings);
            $jobId = $response['Job']['Id'];

            print_r($response);
            //we'd probably want to 
        }
        catch(Exception $e)
        {
            if ($e instanceof AwsException) {
                //parent::DebugErrorOutput("Problem uploading video $currentVideoTitleBeingWorkedOn into AWS");
            }

            //parent::DebugErrorOutput($e);

            $currentVideo->UpdateAuditLog($e);
        }
         //parent::DebugOutput('end of AWSVideoUploadCRONController __invoke()');
        /** END OF CONVERSION */
       
    }   // end of convertTo720p()
}   // end of MediaConvertCronController class
