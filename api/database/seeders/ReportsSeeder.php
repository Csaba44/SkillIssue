<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();
        $questions = DB::table('questions')->get();
        $matches = DB::table('game_matches')->get();

        // Question reports
        $questionReports = [
            [
                'user_id' => 2,
                'question_id' => $questions->random()->id,
                'comment' => 'Szerintem ez a kérdés félreérthető'
            ],
            [
                'user_id' => 3,
                'question_id' => $questions->random()->id,
                'comment' => 'A helyes válasz nem stimmel'
            ]
        ];

        foreach ($questionReports as $report) {
            $report['created_at'] = $report['updated_at'] = now();
            DB::table('question_reports')->insert($report);
        }

        // User reports (cheating suspicion)
        $userReports = [
            [
                'user_id' => 1,
                'game_match_id' => $matches->random()->id,
                'round_number' => 2,
                'comment' => 'Túl gyorsan válaszolt, gyanús'
            ],
            [
                'user_id' => 2,
                'game_match_id' => $matches->first()->id,
                'round_number' => 1,
                'comment' => 'Lehetséges csalás'
            ]
        ];

        foreach ($userReports as $report) {
            $report['created_at'] = $report['updated_at'] = now();
            DB::table('user_reports')->insert($report);
        }
    }
}
