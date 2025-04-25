<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subtask>
 */
class SubtaskFactory extends Factory
{
    protected $model = \App\Models\Subtask::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            // 'file_urls' => json_encode([
            //     'https://res.cloudinary.com/demo/image/upload/sample_' . $this->faker->uuid . '.jpg',
            // ]),
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
        ];
    }
}
