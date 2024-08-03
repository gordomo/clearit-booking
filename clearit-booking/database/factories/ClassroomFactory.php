<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word . ' Classroom',
            'days' => json_encode(['Monday', 'Tuesday', 'Wednesday']),
            'start_time' => '09:00:00',
            'end_time' => '19:00:00',
            'capacity' => 10,
            'duration' => 2,
        ];
    }
}

