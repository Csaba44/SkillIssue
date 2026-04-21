<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\PracticeSession;
use App\Models\PracticeSessionQuestion;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class PracticeSessionQuestionFactory extends Factory
{
    protected $model = PracticeSessionQuestion::class;

    public function definition(): array
    {
        return [
            'practice_session_id' => PracticeSession::factory(),
            'question_id'         => Question::factory(),
            'user_answer_id'      => Answer::factory(),
            'correct_answer_id'   => Answer::factory(),
            'round_number'        => $this->faker->numberBetween(1, 5),
            'user_guess_time_ms'  => $this->faker->numberBetween(500, 30000),
        ];
    }
    
    public function correct(): self
    {
        return $this->afterMaking(function (PracticeSessionQuestion $psq) {
            $psq->user_answer_id = $psq->correct_answer_id;
        });
    }
}
