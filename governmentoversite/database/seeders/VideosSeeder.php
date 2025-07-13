<?php

namespace Database\Seeders;

use App\Models\Videos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videoExamples = [
            [
                'title'                         =>  'Test Video 1',
                'description'                   =>  "Testing description 1",
                'video_category_id'             =>  3,
                'video_processing_state_id'     =>  1,
                'speakers'                      =>  'Speaker 1, Speaker 2',
                'videofilelocalpath'            =>  '',
                'videofileAWSpath'              => '',
                'transcriptfilelocalpath'       => '',
                'transcriptfileAWSpath'         => '',
                'sonix_ai_media_id'             => '',
                'audit_log'                     =>  '[2023-06-09 00:31:56] Admin Uploaded Video locally Video row created',
            ],
            [
                'title'                         =>  'Test Video 2',
                'description'                   =>  "Testing description 2",
                'video_category_id'             =>  3,
                'video_processing_state_id'     =>  1,
                'speakers'                      =>  'Speaker 1, Speaker 2',
                'videofilelocalpath'            =>  '',
                'videofileAWSpath'              => '',
                'transcriptfilelocalpath'       => '',
                'transcriptfileAWSpath'         => '',
                'sonix_ai_media_id'             => '',
                'audit_log'                     =>  '[2023-06-09 00:31:56] Admin Uploaded Video locally Video row created',
            ],
        ];

        foreach($videoExamples as $videoExample)
        {
            //Videos::create($videoExample);
        }
    }
}
