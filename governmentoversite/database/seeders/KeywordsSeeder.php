<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Keywords;

class KeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keywords = [
            [
                'keyword'=>'the',
            ],
            [
                'keyword'=>'quick',
            ],
            [
                'keyword'=>'brown',
            ],
            [
                'keyword'=>'fox',
            ],
            [
                'keyword'=>'jumped',
            ],
            [
                'keyword'=>'over',
            ],
            [
                'keyword'=>'lazy',
            ],
            [
                'keyword'=>'dog',
            ],
            [
                'keyword'=>'government',
            ],
        ];

        foreach($keywords as $keyword)
        {
            Keywords::create($keyword);
        }
    }
}
