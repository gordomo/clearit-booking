<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ClassroomService
{
    public function getAllClasses()
    {
        return DB::select('SELECT * FROM classrooms');
    }
}
