<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersAndProgressSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Teszt Elek',
                'email' => 'teszt.elek@gmail.com',
                'password' => Hash::make('password123'),
                'elo' => 1200,
                'xp' => 450,
            ],
            [
                'name' => 'Kovács Anna',
                'email' => 'anna.kovacs@gmail.com',
                'password' => Hash::make('password123'),
                'elo' => 950,
                'xp' => 250,
            ],
            [
                'name' => 'Nagy Béla',
                'email' => 'bela.nagy@gmail.com',
                'password' => Hash::make('password123'),
                'elo' => 1400,
                'xp' => 1200,
            ],
        ];

        foreach ($users as $userData) {

            // USER
            DB::table('users')->updateOrInsert(
                [
                    'email' => $userData['email'],
                ],
                [
                    'name'         => $userData['name'],
                    'password'     => $userData['password'],
                    'elo'          => $userData['elo'],
                    'xp'           => $userData['xp'],
                    'updated_at'   => now(),
                    'created_at'   => now(),
                ]
            );

            $userId = DB::table('users')
                ->where('email', $userData['email'])
                ->value('id');

            // BADGES LOGIC
            $badgeIds = match (true) {
                $userData['xp'] >= 1000        => [1, 2, 3, 4, 5],
                $userData['xp'] >= 300         => [1, 2, 4],
                default                        => [1],
            };

            // ASSIGN BADGES (PIVOT)
            foreach ($badgeIds as $badgeId) {
                DB::table('badge_user')->updateOrInsert(
                    [
                        'user_id'  => $userId,
                        'badge_id' => $badgeId,
                    ],
                    [
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
