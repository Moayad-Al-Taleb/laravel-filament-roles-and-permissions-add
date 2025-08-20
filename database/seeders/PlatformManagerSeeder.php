<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlatformManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dev@admin.com'],
            [
                'name'              => 'dev',
                'password'          => '123456789',
                'type'              => 'admin',
                'email_verified_at' => now(),
                'remember_token'    => Str::random(10),
            ]
        );
    }
}
