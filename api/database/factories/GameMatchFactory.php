<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\User;
use App\GameResultEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; 

class GameMatchFactory extends Factory
{
    protected $model = GameMatch::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'opponent_id' => User::factory(),
            
            'elo_before' => $this->faker->numberBetween(800, 2000),
            'xp_before' => $this->faker->numberBetween(0, 5000),
            'elo_after' => $this->faker->numberBetween(800, 2000),
            'xp_after' => $this->faker->numberBetween(0, 5000),
            
            'match_result' => $this->faker->randomElement([
                GameResultEnum::WIN,
                GameResultEnum::LOSE,
            ]),
            
            'expected_winrate' => $this->faker->randomFloat(2, 0, 1),
            'match_uuid' => (string) Str::uuid(),
            
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}