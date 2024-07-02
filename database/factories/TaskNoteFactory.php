<?php

namespace Database\Factories;

use App\Models\TaskNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskNotes>
 */
class TaskNoteFactory extends Factory
{
    /**s
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
            'user_id' => 1,
            'title' => $this->faker->sentence(rand(1, 4)),
            'due_date' => strtotime($this->faker->date),
            'priority' => rand(0, 3),
            'type' => ['task', 'note'][rand(0, 1)]
        ];
    }

    public function dateTimestampToNull()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'task') {
                return [
                    'due_date' => rand(1, 5) === 2 ? null : $attributes['due_date']
                ];
            }

            return [];
        });
    }

    public function makeRandomShortcutedForNote()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'note') {
                return [
                    'is_shortcut' => 1
                ];
            }

            return [];
        });
    }

    public function generateTodayDateTask()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'task') {
                return ['due_date' => rand(1, 2) === 2 ? time() : null];
            }

            return [];
        });
    }

    public function insertTaskIntoList()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'task') {
                return ['list_id' => rand(1, 2) === 2 ? 1 : null];
            }

            return [];
        });
    }

    public function insertTaskIntoTag()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'task') {
                return ['tag_id' => rand(1, 2) === 2 ? 1 : null];
            }

            return [];
        });
    }

    public function insertNoteIntoNotebook()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'note') {
                return ['notebook_id' => rand(1, 2) === 2 ? 1 : null];
            }

            return [];
        });
    }

    public function markTaskAsComplete()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'task') {
                return ['is_complete' => rand(1, 3) === 2 ? 1 : 0];
            }

            return [];
        });
    }

    public function markNoteAsSoftDeleted()
    {
        return $this->state(function (array $attributes) {
            if ($attributes['type'] === 'note') {
                return ['deleted_at' => rand(1, 2) === 1 ? now() : null];
            }

            return [];
        });
    }
}
