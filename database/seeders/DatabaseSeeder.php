<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Lists;
use App\Models\Notebook;
use App\Models\Tag;
use App\Models\TaskNote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'johndoe@gmail.com',
            'nickname' => 'JohnDoe8231',
            'password' => bcrypt('password123'),
            'profile' => 'default.jpg',
            'personalization' => json_encode([
                'apperance' => [
                    'theme' => ['light', 'dark'][rand(0, 1)]
                ],
                'datetime' => [
                    'time_format' => ['24hr', '12hr'][rand(0, 1)],
                    'default_date' => ['today', 'tomorrow', 'day_after_tomorrow'][rand(0, 1)],
                    'timezone' => 'UTC'
                ],
                'notification' => [
                    'alert_time' => '09:00',
                    'notify_missed_task' => 0,
                ],
            ]),
            'date_created' => now()
        ]);

        User::create([
            'email' => 'janedoe@gmail.com',
            'nickname' => 'JaneDoe',
            'password' => bcrypt('password123'),
            'profile' => 'default.jpg',
            'personalization' => json_encode([
                'theme' => 'light',
                'time_format' => '24hr'
            ]),
            'date_created' => now()
        ]);

        // List::factory()->count(10)->create();
        // Tag::factory()->count(5)->create();
        TaskNote::factory()->count(80)
            ->dateTimestampToNull()
            ->makeRandomShortcutedForNote()
            ->generateTodayDateTask()
            ->insertTaskIntoList()
            ->insertTaskIntoTag()
            ->insertNoteIntoNotebook()
            ->markTaskAsComplete()
            ->markNoteAsSoftDeleted()
            ->create();

        Lists::create([
            'title' => 'Coding',
            'user_id' => 1
        ]);

        Tag::create([
            'title' => 'Vlog',
            'color' => 'purple',
            'user_id' => 1
        ]);

        Notebook::create([
            'title' => 'Daily Notebook',
            'user_id' => 1
        ]);
    }
}
