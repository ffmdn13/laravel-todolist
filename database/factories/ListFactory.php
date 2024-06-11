<?php

namespace Database\Factories;

use App\Models\Lists;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lists>
 */
class ListFactory extends Factory
{
    /**
     * Model name that will be seeded by this factory
     */
    protected $model = Lists::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => random_int(1, 5),
            'name' => $this->faker->word,
            'color' => $this->faker->safeColorName(),
            'task_count' => $this->faker->randomDigit() * rand(1, 3),
        ];
    }
}
