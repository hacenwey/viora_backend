<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Administration',
                'email'          => 'admin@viora.mr',
                'password'       => bcrypt('zQj0HZSS2WsfeO7k'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
