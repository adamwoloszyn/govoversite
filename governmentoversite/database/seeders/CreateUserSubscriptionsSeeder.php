<?php

namespace Database\Seeders;

use App\Models\UserSubscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CreateUserSubscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::now();
        $start_date = $today->modify('-1 month')->format('Y-m-d');
        $today = Carbon::now();
        $end_date = $today->modify('+1 month')->format('Y-m-d');

        $userSubscriptions = [
            [
                'user_id' =>  3,
                'invoice' => 2,
                'subscription' => 3,
                'start_date'  =>  $start_date,
                'end_date'  =>  $end_date,
            ],
        ];

        foreach($userSubscriptions as $userSubscription)
        {
            UserSubscription::create($userSubscription);
        }
    }   // end of run()
}   // end of CreateUserSubscriptionsSeeder class