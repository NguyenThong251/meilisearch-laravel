<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checklist>
 */
class ChecklistFactory extends Factory
{
    protected $model = \App\Models\Checklist::class;

    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
            'is_completed' => $this->faker->boolean,
            'task_id' => Task::factory(),
            'subtask_id' => null,
        ];
    }
}
