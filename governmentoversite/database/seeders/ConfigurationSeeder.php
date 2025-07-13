<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Configuration;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Configurations = [
            [
                'key'=>'Sample',
                'value'=>"Value",
                'environment'=>"*",
            ],
        ];

        foreach($Configurations as $Configuration)
        {
            Configuration::create($Configuration);
        }
    }
}
