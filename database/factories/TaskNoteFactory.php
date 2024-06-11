<?php

namespace Database\Factories;

use App\Models\TaskNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskNotes>
 */
class TaskNoteFactory extends Factory
{
    /**
     * Model name that will be seeded by this factory
     */
    protected $model = TaskNote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'list_id' => rand(1, 10),
            'tag_id' => rand(1, 5),
            'title' => $this->faker->words(random_int(1, 6), true),
            'body' => $this->faker->paragraphs(random_int(3, 6), true),
            'due_date' => $this->faker->dateTime('now'),
            'type' => function () {
                return ['task', 'note'][rand(0, 1)];
            },
            'complete' => rand(0, 1),
        ];
    }

    public function nullParagraph()
    {
        return $this->state(function () {
            return rand(1, 5) === 3 ? ['body' => null] : [];
        });
    }
}
