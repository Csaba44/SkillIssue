<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'answer' => $this->faker->sentence(),
            'is_correct' => false,
        ];
    }

    /**
     * Answer::factory()->correct()->create();
     */
    public function correct(): self
    {
        return $this->state(fn(array $attributes) => [
            'is_correct' => true,
        ]);
    }
}
