<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MatchesAndPracticeSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();
        $questions = DB::table('questions')->get();

        // Create sample matches (1v1 PvP)
        $matches = [
            [
                'user_id' => 1, // Teszt Elek
                'opponent_id' => 2, // Anna
                'elo_before' => 1100,
                'xp_before' => 400,
                'elo_after' => 1200,
                'xp_after' => 450,
                'match_uuid' => Str::uuid(),
            ],
            [
                'user_id' => 2, // Anna
                'opponent_id' => 3, // Béla
                'elo_before' => 900,
                'xp_before' => 200,
                'elo_after' => 950,
                'xp_after' => 250,
                'match_uuid' => Str::uuid(),
            ],
            [
                'user_id' => 1, // Teszt Elek
                'opponent_id' => 3, // Béla
                'elo_before' => 1250,
                'xp_before' => 1100,
                'elo_after' => 1400,
                'xp_after' => 1200,
                'match_uuid' => Str::uuid(),
            ]
        ];

        foreach ($matches as $matchData) {
            $matchData['created_at'] = $matchData['updated_at'] = now();
            $matchId = DB::table('matches')->insertGetId($matchData);

            // Add match questions (3 rounds per match)
            for ($round = 1; $round <= 3; $round++) {
                $question = $questions->random();
                $answers = DB::table('answers')->where('question_id', $question->id)->get();
                $correctAnswer = $answers->where('is_correct', 1)->first();
                $userAnswer = $answers->random(); // Fixed: Simple random selection

                DB::table('match_question')->insert([
                    'match_id' => $matchId,
                    'question_id' => $question->id,
                    'user_answer_id' => $userAnswer->id,
                    'correct_answer_id' => $correctAnswer->id,
                    'round_number' => $round,
                    'user_guess_time_ms' => rand(5000, 15000),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Create practice sessions
        $practiceSessions = [
            [
                'user_id' => 1,
                'rounds' => 10,
                'xp_before' => 300,
                'xp_after' => 400,
            ],
            [
                'user_id' => 2,
                'rounds' => 5,
                'xp_before' => 150,
                'xp_after' => 200,
            ]
        ];

        foreach ($practiceSessions as $sessionData) {
            $sessionData['created_at'] = $sessionData['updated_at'] = now();
            $sessionId = DB::table('practice_sessions')->insertGetId($sessionData);

            // Add practice session questions
            for ($round = 1; $round <= $sessionData['rounds']; $round++) {
                $question = $questions->random();
                $answers = DB::table('answers')->where('question_id', $question->id)->get();
                $correctAnswer = $answers->where('is_correct', 1)->first();

                // Fixed: Simple random answer selection with 70% correct rate simulation
                $userAnswer = $answers->random();

                DB::table('practice_session_questions')->insert([
                    'practice_session_id' => $sessionId,
                    'question_id' => $question->id,
                    'user_answer_id' => $userAnswer->id,
                    'correct_answer_id' => $correctAnswer->id,
                    'round_number' => $round,
                    'user_guess_time_ms' => rand(3000, 12000),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
