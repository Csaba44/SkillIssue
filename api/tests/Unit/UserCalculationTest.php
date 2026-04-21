<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\GameMatch;
use App\GameResultEnum;
use App\Models\MatchQuestion;
use App\Models\PracticeSession;
use App\Models\PracticeSessionQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_win_rate_calculation()
    {
        $user = User::factory()->create();

        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::WIN]);
        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::WIN]);
        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::LOSE]);
        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::PENDING]);

        // 2 győzelem / 3 érvényes = 66.67%
        $this->assertEquals(66.67, $user->win_rate);
    }

    public function test_win_streak_calculation()
    {
        $user = User::factory()->create();

        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::LOSE, 'created_at' => now()->subDays(5)]);

        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::WIN, 'created_at' => now()->subDays(3)]);
        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::WIN, 'created_at' => now()->subDays(2)]);
        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::WIN, 'created_at' => now()->subDays(1)]);

        $this->assertEquals(3, $user->win_streak);
    }

    public function test_played_matches_count_excludes_invalid_results()
    {
        $user = User::factory()->create();

        GameMatch::factory()->for($user)->count(2)->create(['match_result' => GameResultEnum::WIN]);
        GameMatch::factory()->for($user)->create(['match_result' => GameResultEnum::CANCELLED]);

        $this->assertEquals(2, $user->played_matches_count);
    }

    public function test_accuracy_calculation()
    {
        $user = User::factory()->create();
        $match = GameMatch::factory()->for($user)->create();
        $session = PracticeSession::factory()->for($user)->create();

        $answer1 = \App\Models\Answer::factory()->create();
        $answer2 = \App\Models\Answer::factory()->create();
        $answer3 = \App\Models\Answer::factory()->create();

        MatchQuestion::factory()->create([
            'game_match_id' => $match->id,
            'user_answer_id' => $answer1->id,
            'correct_answer_id' => $answer1->id
        ]);

        MatchQuestion::factory()->create([
            'game_match_id' => $match->id,
            'user_answer_id' => $answer2->id,
            'correct_answer_id' => $answer1->id
        ]);

        \App\Models\PracticeSessionQuestion::factory()->create([
            'practice_session_id' => $session->id,
            'user_answer_id' => $answer3->id,
            'correct_answer_id' => $answer3->id
        ]);

        $this->assertEquals(66.67, $user->accuracy);
    }
}
