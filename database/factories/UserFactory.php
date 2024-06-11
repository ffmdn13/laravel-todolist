<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users>
 */
class UserFactory extends Factory
{
    /**
     * Model name that will be seeded by this factory
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'nickname' => $this->faker->userName(),
            'password' => Hash::make('password123'),
            'personalization' => json_encode([
                'theme' => 'light',
                'locale' => 'ID',
                'time-format' => '24-hr',
                'sorting' => 'A-Z',
            ]),
            'profile' => 'default.jpg',
            'date_created' => now(),
        ];
    }

    public function nullUsername()
    {
        return $this->state(function () {
            return rand(1, 5) === 3 ? ['nickname' => null] : [];
        });
    }
}
