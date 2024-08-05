<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingService
{
    public function bookClass($classroomId, $user, $startTime)
    {
        // Obtener información del classrooms
        $classroom = DB::select('SELECT * FROM classrooms WHERE id = ?', [$classroomId]);
        if (empty($classroom)) {
            throw new \Exception('Classroom not found');
        }
        $classroom = $classroom[0];

        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $startTime);

        $now = Carbon::now();

        // Verificar que el start_time es en el futuro
        if ($startTime->lt($now)) {
            throw new \Exception('Start time must be in the future');
        }

        $dayOfWeek = $startTime->format('l');

        // Verificar que el día es válido para la clase
        $classDays = json_decode($classroom->days);
        if (!in_array($dayOfWeek, $classDays)) {
            throw new \Exception('The classroom is not available on this day - available days: ' . $classroom->days);
        }

        // Verificar que el start_time esté alineado con los intervalos de la clase
        $classStart = Carbon::parse($startTime->toDateString() . ' ' . $classroom->start_time);
        $classEnd = Carbon::parse($startTime->toDateString() . ' ' . $classroom->end_time);

        $validStartTimes = [];
        while ($classStart->lt($classEnd)) {
            $validStartTimes[] = $classStart->copy();
            $classStart->addHours($classroom->duration);
        }

        $isValidStartTime = false;
        foreach ($validStartTimes as $validStartTime) {
            if ($startTime->eq($validStartTime)) {
                $isValidStartTime = true;
                break;
            }
        }
        //TODO ver la forma de devolver los intervalos de inicio de clase
        if (!$isValidStartTime) {
            throw new \Exception('Invalid start time for the classroom schedule');
        }

        $endTime = $startTime->copy()->addHours($classroom->duration);

        // Verificar capacidad de la clase en el intervalo solicitado
        $currentBookings = DB::select('
            SELECT COUNT(*) as count FROM bookings 
            WHERE classroom_id = ?
              AND start_time = ?
        ', [$classroomId, $startTime]);

        if ($currentBookings[0]->count >= $classroom->capacity) {
            throw new \Exception('Classroom is full for this time slot');
        }

        // Verificar solapamientos con otras reservas del usuario
        $overlap = DB::select('
            SELECT * FROM bookings 
            WHERE user = ?
                AND (
                    (start_time < ? AND end_time > ?)
                    OR (start_time < ? AND end_time > ?)
                    OR (? < start_time AND ? > start_time)
                    OR (? < end_time AND ? > end_time)
                )
            ', [$user, $endTime, $startTime, $endTime, $startTime, $startTime, $endTime, $startTime, $endTime]);

        if (!empty($overlap)) {
        throw new \Exception('This time slot conflicts with another booking');
        }


        // Crear la reserva
        DB::insert('
            INSERT INTO bookings (classroom_id, user, start_time, end_time, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ', [$classroomId, $user, $startTime, $endTime, $now, $now]);

        $id = DB::select('
            SELECT id FROM bookings WHERE classroom_id = ? AND user = ? AND created_at = ?
        ', [$classroomId, $user, $now]);

        return $id[0];
    }

    public function cancelBooking($bookingId, $user)
    {
        // Obtener información de la reserva
        $booking = DB::select('SELECT * FROM bookings WHERE id = ? and user = ?', [$bookingId, $user]);
        if (empty($booking)) {
            throw new \Exception('Booking not found');
        }
        $booking = $booking[0];

        $now = Carbon::now();
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_time);

        // Verificar si la reserva se puede cancelar (mínimo 24 horas de antelación)
        $hoursUntilStart = $startTime->diffInHours($now);
        if ($hoursUntilStart < 24) {
            throw new \Exception('Cannot cancel booking less than 24 hours in advance');
        }

        // Eliminar la reserva
        DB::delete('DELETE FROM bookings WHERE id = ?', [$bookingId]);
    }

    public function getUserBookList($user)
    {
        return DB::select('SELECT * FROM bookings where user = ?', [$user]);
    }
}
