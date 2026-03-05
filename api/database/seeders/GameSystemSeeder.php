<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GameSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Create levels (XP ranges)
        $levels = [
            ['level' => 1, 'min_xp' => 0],
            ['level' => 2, 'min_xp' => 100],
            ['level' => 3, 'min_xp' => 300],
            ['level' => 4, 'min_xp' => 600],
            ['level' => 5, 'min_xp' => 1000],
        ];

        foreach ($levels as $level) {
            DB::table('levels')->updateOrInsert(
                [
                    'level' => $level['level'],
                ],
                [
                    'min_xp' => $level['min_xp'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // Create ranks (like Rainbow Six Siege)
        $ranks = [
            ['name' => 'Copper V', 'min_elo' => 0],
            ['name' => 'Copper IV', 'min_elo' => 200],
            ['name' => 'Copper III', 'min_elo' => 400],
            ['name' => 'Copper II', 'min_elo' => 600],
            ['name' => 'Copper I', 'min_elo' => 800],
            ['name' => 'Bronze V', 'min_elo' => 1000],
            ['name' => 'Bronze IV', 'min_elo' => 1200],
        ];

        foreach ($ranks as $rank) {
            DB::table('ranks')->updateOrInsert(
                [
                    'name' => $rank['name'],
                ],
                [
                    'min_elo' => $rank['min_elo'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // Create badges (achievements)
        $badges = [
            ['name' => 'Első győzelem', 'description' => 'Első nyert meccs'],
            ['name' => 'Quiz mester', 'description' => '10 helyes válasz sorozatban'],
            ['name' => 'Bronz ligás', 'description' => 'Bronz rang elérése'],
            ['name' => 'Gyakorló', 'description' => '50 gyakorló kérdés'],
            ['name' => 'Versengő', 'description' => '10 PvP meccs'],
        ];

        foreach ($badges as $badge) {
            DB::table('badges')->updateOrInsert(
                [
                    'name' => $badge['name'],
                ],
                [
                    'description' => $badge['description'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
