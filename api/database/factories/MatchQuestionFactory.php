<?php

namespace Database\Factories;

use App\Models\MatchQuestion;
use App\Models\GameMatch;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatchQuestionFactory extends Factory
{
    protected $model = MatchQuestion::class;

    public function definition(): array
    {
        return [
            'game_match_id' => GameMatch::factory(),
            'question_id' => Question::factory(),
            'correct_answer_id' => Answer::factory(),
            'user_answer_id' => null,
            'round_number' => $this->faker->numberBetween(1, 5),
            'round_expires_at' => now()->addSeconds(30),
            
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function answered(int $answerId): self
    {
        return $this->state(fn (array $attributes) => [
            'user_answer_id' => $answerId,
        ]);
    }
}