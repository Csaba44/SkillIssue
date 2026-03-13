<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSystemSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [];

        for ($level = 1; $level <= 100; $level++) {

            $xp = 5 * ($level ** 2);
            $xp = ceil($xp / 10) * 10;

            if ($level == 1) {
                $levels[] = [
                    "level" => $level,
                    "min_xp" => -1
                ];
                continue;
            }

            $levels[] = [
                "level" => $level,
                "min_xp" => $xp
            ];
        }
        foreach ($levels as $level) {
            DB::table('levels')->updateOrInsert(
                ['level' => $level['level']],
                ['min_xp' => $level['min_xp'], 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $ranks = [
            ['name' => 'Copper V',   'min_elo' => 0],
            ['name' => 'Copper IV',  'min_elo' => 100],
            ['name' => 'Copper III', 'min_elo' => 200],
            ['name' => 'Copper II',  'min_elo' => 300],
            ['name' => 'Copper I',   'min_elo' => 400],

            ['name' => 'Bronze V',   'min_elo' => 500],
            ['name' => 'Bronze IV',  'min_elo' => 600],
            ['name' => 'Bronze III', 'min_elo' => 700],
            ['name' => 'Bronze II',  'min_elo' => 800],
            ['name' => 'Bronze I',   'min_elo' => 900],

            ['name' => 'Silver V',   'min_elo' => 1000],
            ['name' => 'Silver IV',  'min_elo' => 1150],
            ['name' => 'Silver III', 'min_elo' => 1300],
            ['name' => 'Silver II',  'min_elo' => 1450],
            ['name' => 'Silver I',   'min_elo' => 1600],

            ['name' => 'Gold V',     'min_elo' => 1750],
            ['name' => 'Gold IV',    'min_elo' => 1950],
            ['name' => 'Gold III',   'min_elo' => 2150],
            ['name' => 'Gold II',    'min_elo' => 2350],
            ['name' => 'Gold I',     'min_elo' => 2550],

            ['name' => 'Platinum V',   'min_elo' => 2750],
            ['name' => 'Platinum IV',  'min_elo' => 3000],
            ['name' => 'Platinum III', 'min_elo' => 3250],
            ['name' => 'Platinum II',  'min_elo' => 3500],
            ['name' => 'Platinum I',   'min_elo' => 3750],

            ['name' => 'Champion',   'min_elo' => 5000],
        ];

        foreach ($ranks as $rank) {
            DB::table('ranks')->updateOrInsert(
                ['name' => $rank['name']],
                ['min_elo' => $rank['min_elo'], 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $badges = [
            ['name' => 'Első győzelem',  'description' => 'Első nyert meccs'],
            ['name' => 'Quiz mester',    'description' => '10 helyes válasz sorozatban'],
            ['name' => 'Bronz ligás',    'description' => 'Bronz rang elérése'],
            ['name' => 'Gyakorló',       'description' => '50 gyakorló kérdés'],
            ['name' => 'Versengő',       'description' => '10 PvP meccs'],
        ];

        foreach ($badges as $badge) {
            DB::table('badges')->updateOrInsert(
                ['name' => $badge['name']],
                ['description' => $badge['description'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
