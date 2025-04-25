<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = \App\Models\Department::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word . ' Department',
        ];
    }
}
