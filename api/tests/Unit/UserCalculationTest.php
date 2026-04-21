<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\GameMatch;
use App\GameResultEnum;
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
}