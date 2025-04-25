<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = \App\Models\Task::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = $this->faker->dateTimeBetween($startDate, '+1 month');

        return [
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'start_date' => $startDate,
            'due_date' => $dueDate,
            'estimated_time' => rand(1, 10) . ' days, ' . rand(1, 8) . ' hours',
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'priority' => $this->faker->randomElement(['normal', 'urgent']),
            'progress' => rand(0, 100),
            'file_urls' => json_encode([
                'https://res.cloudinary.com/demo/image/upload/sample_' . $this->faker->uuid . '.jpg',
                'https://res.cloudinary.com/demo/image/upload/sample_' . $this->faker->uuid . '.pdf',
            ]),
            'creator_id' => User::factory(),
            'project_id' => Project::factory(),
        ];
    }
}
