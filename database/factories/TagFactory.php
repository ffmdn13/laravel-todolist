<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tags>
 */
class TagFactory extends Factory
{
    /**
     * Model name that will be seeded by this factory
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, 5),
            'name' => $this->faker->word(),
            'color' => $this->faker->safeColorName(),
            'task_count' => $this->faker->randomDigit() * rand(1, 3)
        ];
    }
}
