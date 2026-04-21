<?php

namespace Database\Factories;

use App\Models\PracticeSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PracticeSessionFactory extends Factory
{
    protected $model = PracticeSession::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'rounds' => 5,
            'xp_before' => 0,
            'xp_after' => 0,
        ];
    }
}