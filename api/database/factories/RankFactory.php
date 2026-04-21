<?php

namespace Database\Factories;

use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;

class RankFactory extends Factory
{
    protected $model = Rank::class;

    public function definition(): array
    {
        return [
            'name'    => $this->faker->unique()->randomElement(['Bronz', 'Ezüst', 'Arany', 'Platina', 'Gyémánt', 'Mester']),
            'min_elo' => $this->faker->unique()->numberBetween(0, 3000),
        ];
    }
}
