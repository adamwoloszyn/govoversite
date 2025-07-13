<?php

namespace Database\Seeders;

use App\Models\VideoCategories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateVideoCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videoCategories = [
            [
                'long_description'  => "Candidate Forums",
                'short_description' => strtolower(str_replace(' ', '-', "Candidate Forums"))
            ],
                [
                    'parent_id' => 1,
                    'long_description'  => "Brookfield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Brookfield NH"))
                ],
                [
                    'parent_id' => 1,
                    'long_description'  => "Effingham NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Effingham NH"))
                ],
                [
                    'parent_id' => 1,
                    'long_description'  => "Ossipee NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Ossipee NH"))
                ],
                [
                    'parent_id' => 1,
                    'long_description'  => "Tamworth NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Tamworth NH"))
                ],
                [
                    'parent_id' => 1,
                    'long_description'  => "Wakefield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Wakefield NH"))
                ],
            [
                'long_description'  => "Commission Meetings",
                'short_description' => strtolower(str_replace(' ', '-', "Commission Meetings"))
            ],
                [
                    'parent_id' => 7,
                    'long_description'  => "Belknap County NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Belknap County NH"))
                ],
                [
                    'parent_id' => 7,
                    'long_description'  => "Brookfield NH Conservation",
                    'short_description' => strtolower(str_replace(' ', '-', "Brookfield NH Conservation"))
                ],
                [
                    'parent_id' => 7,
                    'long_description'  => "Brookfield NH Heritage",
                    'short_description' => strtolower(str_replace(' ', '-', "Brookfield NH Heritage"))
                ],
                [
                    'parent_id' => 7,
                    'long_description'  => "Carroll County NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Carroll County NH"))
                ],
                [
                    'parent_id' => 7,
                    'long_description'  => "Lakes Region Planning",
                    'short_description' => strtolower(str_replace(' ', '-', "Lakes Region Planning"))
                ],
                [
                    'parent_id' => 7,
                    'long_description'  => "Strafford Regional Planning",
                    'short_description' => strtolower(str_replace(' ', '-', "Strafford Regional Planning"))
                ],
            [
                'long_description'  => "Delegation Meetings",
                'short_description' => strtolower(str_replace(' ', '-', "Delegation Meetings"))
            ],
                [
                    'parent_id' => 14,
                    'long_description'  => "Belknap County NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Belknap County NH"))
                ],
                [
                    'parent_id' => 14,
                    'long_description'  => "Belknap County NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Belknap County NH"))
                ],
            [
                'long_description'  => "School Board Meetings",
                'short_description' => strtolower(str_replace(' ', '-', "School Board Meetings"))
            ],
                [
                    'parent_id' => 17,
                    'long_description'  => "Governor Wentworth Regional School District",
                    'short_description' => strtolower(str_replace(' ', '-', "Governor Wentworth Regional School District"))
                ],
            [
                'long_description'  => "Selectmen Meetings",
                'short_description' => strtolower(str_replace(' ', '-', "Selectmen Meetings"))
            ],
                [
                    'parent_id' => 19,
                    'long_description'  => "Brookfield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Brookfield NH"))
                ],
                [
                    'parent_id' => 19,
                    'long_description'  => "Effingham NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Effingham NH"))
                ],
                [
                    'parent_id' => 19,
                    'long_description'  => "Ossipee NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Ossipee NH"))
                ],
                [
                    'parent_id' => 19,
                    'long_description'  => "Tamworth NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Tamworth NH"))
                ],
                [
                    'parent_id' => 19,
                    'long_description'  => "Wakefield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Wakefield NH"))
                ],
            [
                'long_description'  => "Town Meetings",
                'short_description' => strtolower(str_replace(' ', '-', "Town Meetings"))
            ],
                [
                    'parent_id' => 25,
                    'long_description'  => "Brookfield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Brookfield NH"))
                ],
                [
                    'parent_id' => 25,
                    'long_description'  => "Effingham NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Effingham NH"))
                ],
                [
                    'parent_id' => 25,
                    'long_description'  => "Ossipee NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Ossipee NH"))
                ],
                [
                    'parent_id' => 25,
                    'long_description'  => "Tamworth NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Tamworth NH"))
                ],
                [
                    'parent_id' => 25,
                    'long_description'  => "Wakefield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Wakefield NH"))
                ],
            [
                'long_description'  => "Zoning Meetings",
                'short_description' => strtolower(str_replace(' ', '-', "Zoning Meetings"))
            ],
                [
                    'parent_id' => 31,
                    'long_description'  => "Brookfield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Brookfield NH"))
                ],
                [
                    'parent_id' => 31,
                    'long_description'  => "Effingham NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Effingham NH"))
                ],
                [
                    'parent_id' => 31,
                    'long_description'  => "Ossipee NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Ossipee NH"))
                ],
                [
                    'parent_id' => 31,
                    'long_description'  => "Tamworth NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Tamworth NH"))
                ],
                [
                    'parent_id' => 31,
                    'long_description'  => "Wakefield NH",
                    'short_description' => strtolower(str_replace(' ', '-', "Wakefield NH"))
                ]
        ];
        

        foreach($videoCategories as $videoCategories)
        {
            VideoCategories::create($videoCategories);
        }
    }   // end of run()
}   // end of CreateVideoCategoriesSeeder class