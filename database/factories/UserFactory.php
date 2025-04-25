<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName,
            'password' => Hash::make('password'),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'avatar' => 'https://picsum.photos/200/200?random=' . rand(1, 1000),
            'department_id' => Department::factory(),
        ];
    }
}
