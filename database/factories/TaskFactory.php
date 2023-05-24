<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Task',
            'description' => 'Task description',
            'board_id' => null,
            'user_id' => null,
            'time_spent' => 0,
            'time_estimated' => 0,
        ];
    }
}
