<?php

namespace Database\Factories;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BanFactory extends Factory
{
    protected $model = Ban::class;

    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'release_date' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'reason'       => $this->faker->sentence(),
        ];
    }

    public function expired(): self
    {
        return $this->state(fn(array $attributes) => [
            'release_date' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    public function longTerm(): self
    {
        return $this->state(fn(array $attributes) => [
            'release_date' => $this->faker->dateTimeBetween('+1 year', '+5 years'),
        ]);
    }
}
