<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@habibi-stay.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'is_admin' => true,
            ]
        );

        // Create demo host
        User::firstOrCreate(
            ['email' => 'host@habibi-stay.com'],
            [
                'name' => 'Demo Host',
                'password' => bcrypt('host123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Create demo guest
        User::firstOrCreate(
            ['email' => 'guest@habibi-stay.com'],
            [
                'name' => 'Demo Guest',
                'password' => bcrypt('guest123'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
    }
}
