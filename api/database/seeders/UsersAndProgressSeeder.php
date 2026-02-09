<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersAndProgressSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users with different progress
        $users = [
            [
                'name' => 'Teszt Elek',
                'email' => 'teszt.elek@gmail.com',
                'password' => Hash::make('password123'),
                'elo' => 1200,
                'xp' => 450,
                'streak_count' => 3,
                'last_login' => now(),
            ],
            [
                'name' => 'Kovács Anna',
                'email' => 'anna.kovacs@gmail.com',
                'password' => Hash::make('password123'),
                'elo' => 950,
                'xp' => 250,
                'streak_count' => 7,
                'last_login' => now(),
            ],
            [
                'name' => 'Nagy Béla',
                'email' => 'bela.nagy@gmail.com',
                'password' => Hash::make('password123'),
                'elo' => 1400,
                'xp' => 1200,
                'streak_count' => 1,
                'last_login' => now(),
            ]
        ];

        foreach ($users as $userData) {
            $userData['created_at'] = $userData['updated_at'] = now();
            $userId = DB::table('users')->insertGetId($userData);

            // Assign badges to users
            $badges = match (true) {
                $userData['xp'] >= 1000 => [1, 2, 3, 4, 5],
                $userData['xp'] >= 300 => [1, 2, 4],
                $userData['streak_count'] >= 5 => [1, 2],
                default => [1]
            };

            foreach ($badges as $badgeId) {
                DB::table('badge_user')->insert([
                    'user_id' => $userId,
                    'badge_id' => $badgeId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
