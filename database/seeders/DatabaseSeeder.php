<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Department;

use App\Models\Project;
use App\Models\Task;
use App\Models\Subtask;
use App\Models\Checklist;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo phòng ban
        Department::factory()->count(5)->create();

        // Tạo người dùng
        User::factory()->count(20)->create();

        // Tạo dự án
        Project::factory()->count(10)->create();

        // Tạo công việc
        Task::factory()->count(50)->create()->each(function ($task) {
            // Gán người thực hiện
            $task->assignees()->attach(
                User::inRandomOrder()->take(rand(1, 5))->pluck('id'),
                ['role' => rand(0, 1) ? 'primary' : 'member']
            );

            // Tạo công việc con
            Subtask::factory()->count(rand(1, 3))->create(['task_id' => $task->id]);

            // Tạo checklist
            Checklist::factory()->count(rand(2, 5))->create(['task_id' => $task->id]);
        });
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Product::factory()->count(50)->create();
    }
}
