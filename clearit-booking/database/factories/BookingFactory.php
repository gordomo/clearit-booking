<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'classroom_id' => Classroom::factory(),
            'user' => $this->faker->name,
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(1)->addHours(2),
        ];
    }
}

