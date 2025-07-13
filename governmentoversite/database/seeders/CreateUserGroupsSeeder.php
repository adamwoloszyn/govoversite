<?php

namespace Database\Seeders;

use App\Models\UserGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\UserGroups;

class CreateUserGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userGroups = [
            [
                'name'=>'Guest',
            ],
            [
                'name'=>'Subscriber',
            ],
            [
                'name'=>'Admin',
            ],
        ];

        foreach($userGroups as $userGroup)
        {
            UserGroup::create($userGroup);
        }
    }   // end of run()
}   // end of CreateUserGroupsSeeder class
