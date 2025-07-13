<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Channels;

class ChannelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channels = [
            [
                'name' => 'Brookfield NH Planning',
                'slug' => 'brookfield-nh-planning',
                'channel_id' => 'tpx7zkarhzgw2ksygsxa',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ],
            [
                'name' => 'Brookfield NH Selectmen',
                'slug' => 'brookfield-nh-selectmen',
                'channel_id' => 'pjx58uauvnobdlzdkydg',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ],
            [
                'name' => 'Caroll County NH Commission',
                'slug' => 'caroll-county-nh-comission',
                'channel_id' => 'trv5nxwdbygw2fnmdggy',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ],
            [
                'name' => 'Caroll County NH Delegation',
                'slug' => 'caroll-county-nh-delegation',
                'channel_id' => 'jpt5jh4lpbgqoluheqph',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ],
            [
                'name' => 'Ossipee NH Selectmen',
                'slug' => 'ossipee-nh-selectmen',
                'channel_id' => 'ptjkn0szyfd9vtjyskk8',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ],
            [
                'name' => 'Tamworth NH Planning',
                'slug' => 'tamworth-nh-planning',
                'channel_id' => 'uabihjh1sbatofrgmna2',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ],
            [
                'name' => 'Tamworth NH Selectmen',
                'slug' => 'tamworth-nh-selectmen',
                'channel_id' => 'velmwbxluhieh8oboyqk',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ],
            [
                'name' => 'Wakefield NH Selectmen',
                'slug' => 'wakefield-nh-selectmen',
                'channel_id' => 'ernly61mofx4lhwtiwfv',
                'description' => 'DESCRIPTION',
                'pdf_url' => 'https://uploads.boxcast.com/fksh0nr0pcynei5un04t/2023-06/negpholozwh3imbsgt7t/TSM_Agenda_6_29_23_.pdf'
            ]
        ];

        foreach ($channels as $channel) {
            Channels::create($channel);
        }
    }
}
