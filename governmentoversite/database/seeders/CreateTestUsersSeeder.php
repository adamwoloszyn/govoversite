<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;

use App\Models\User;

class CreateTestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Create three users 
     *      Guest, which is the same as anonymous
     *      Subscriber, 
     *      Admin
     */
    public function run(): void
    {
        $users = [
            [
                'customer' => 1,
                'name'=>'Guest',
                'email'=>'User@domain.com',
                'password'=>bcrypt('testpw'),
                'role'=>1,
                'checkout_session_id'=>''
            ],
            [
                'customer' => 2,
                'name'=>'Subscriber',
                'email'=>'Subscriber@domain.com',
                'password'=>bcrypt('testpw'),
                'role'=>2,
                'checkout_session_id'=>''
            ],
            [
                'customer' => 3,
                'name'=>'Subscribed',
                'email'=>'Subscribed@domain.com',
                'password'=>bcrypt('testpw'),
                'role'=>2,
                'checkout_session_id'=>''
            ],
            [
                'customer' => 4,
                'name'=>'Admin',
                'email'=>'Admin@domain.com',
                'password'=>bcrypt('testpw'),
                'role'=>3,
                'checkout_session_id'=>''
            ],
        ];

        foreach($users as $user)
        {
            User::create($user);
        }
    }   // end of run()
}   // end of CreateTestUsersSeeder class
