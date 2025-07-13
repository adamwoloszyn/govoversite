<?php

namespace Database\Seeders;

use App\Models\VideoProcessingStates;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoProcessingStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videoProcessingStates = [
            [
                'id'                =>  1,
                'parent_state_id'   =>  0,
                'order_number'      =>  1,
                'short_description' =>  'Admin Uploaded Video'
            ],
            [
                'id'                =>  2,
                'parent_state_id'   =>  1,
                'order_number'      =>  2,
                'short_description' =>  'Created SubDirectory in AWS'
            ],
            [
                'id'                =>  3,
                'parent_state_id'   =>  2,
                'order_number'      =>  3,
                'short_description' =>  'Uploading to AWS'
            ],
            [
                'id'                =>  4,
                'parent_state_id'   =>  3,
                'order_number'      =>  4,
                'short_description' =>  'Uploaded to AWS'
            ],
            [
                'id'                =>  5,
                'parent_state_id'   =>  4,
                'order_number'      =>  5,
                'short_description' =>  'Compressing Video in AWS'
            ],
            [
                'id'                =>  6,
                'parent_state_id'   =>  5,
                'order_number'      =>  6,
                'short_description' =>  'Compressed Video in AWS'
            ],
            [
                'id'                =>  7,
                'parent_state_id'   =>  6,
                'order_number'      =>  7,
                'short_description' =>  'Uploaded to Sonix'
            ],
            [
                'id'                =>  8,
                'parent_state_id'   =>  7,
                'order_number'      =>  8,
                'short_description' =>  'Sonix Transcription Done'
            ],
            [
                'id'                =>  9,
                'parent_state_id'   =>  8,
                'order_number'      =>  9,
                'short_description' =>  'Downloaded Sonix Transcription'
            ],
            [
                'id'                =>  10,
                'parent_state_id'   =>  9,
                'order_number'      =>  10,
                'short_description' =>  'Uploaded transcript to AWS'
            ],
            [
                'id'                =>  11,
                'parent_state_id'   =>  10,
                'order_number'      =>  11,
                'short_description' =>  'Transcription Parsed'
            ],
            [
                'id'                =>  12,
                'parent_state_id'   =>  11,
                'order_number'      =>  12,
                'short_description' =>  'Published'
            ],
            [
                'id'                =>  13,
                'parent_state_id'   =>  12,
                'order_number'      =>  13,
                'short_description' =>  'Emails Sent'
            ],
        ];

        foreach($videoProcessingStates as $videoProcessingState)
        {
            VideoProcessingStates::create($videoProcessingState);
        }
    }   // end of run()
}
