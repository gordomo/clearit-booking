<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertClassroomsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('classrooms')->insert([
            [
                'name' => 'Math Classroom',
                'days' => json_encode(['Monday', 'Tuesday', 'Wednesday']),
                'start_time' => '09:00:00',
                'end_time' => '19:00:00',
                'capacity' => 10,
                'duration' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Art Classroom',
                'days' => json_encode(['Monday', 'Thursday', 'Saturday']),
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'capacity' => 15,
                'duration' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Science Classroom',
                'days' => json_encode(['Tuesday', 'Friday', 'Saturday']),
                'start_time' => '15:00:00',
                'end_time' => '22:00:00',
                'capacity' => 7,
                'duration' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Geography Classroom',
                'days' => json_encode(['Thursday', 'Friday']),
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'capacity' => 15,
                'duration' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Computer Science Classroom',
                'days' => json_encode(['Monday', 'Friday']),
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'capacity' => 23,
                'duration' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'History Classroom',
                'days' => json_encode(['Tuesday', 'Wednesday']),
                'start_time' => '10:00:00',
                'end_time' => '19:00:00',
                'capacity' => 11,
                'duration' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('classrooms')->truncate();
    }
}
