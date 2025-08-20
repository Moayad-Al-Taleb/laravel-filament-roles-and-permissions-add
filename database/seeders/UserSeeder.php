<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admins
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'password' => 'password',
                'type' => 'admin',
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'password' => 'password',
                'type' => 'admin',
            ],

            // Site Users
            [
                'name' => 'User One',
                'email' => 'user1@example.com',
                'password' => 'password',
                'type' => 'user',
            ],
            [
                'name' => 'User Two',
                'email' => 'user2@example.com',
                'password' => 'password',
                'type' => 'user',
            ],

            // Clients
            [
                'name' => 'Client One',
                'email' => 'client1@example.com',
                'password' => 'password',
                'type' => 'client',
            ],
            [
                'name' => 'Client Two',
                'email' => 'client2@example.com',
                'password' => 'password',
                'type' => 'client',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data + [
                    'email_verified_at' => now(),
                    'remember_token'    => Str::random(10),
                ]
            );
        }
    }
}
