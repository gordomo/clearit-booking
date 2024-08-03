<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Models\Classroom;

class BookingApiTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function it_shows_all_available_classes_with_timetable_and_availability()
    {
        $response = $this->get('/api/classes');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "success",
                    "data" => ['*' => [
                                'id',
                                'name',
                                'days',
                                'start_time',
                                'end_time',
                                'capacity',
                                'duration',
                            ]],
                    "message",
                    "code",
                 ]);
    }

    /** @test */
    public function it_books_a_class_successfully()
    {
        $classroom = Classroom::factory()->create();

        $startTime = Carbon::parse('next Monday')->setTime(9, 0, 0)->format('Y-m-d H:i:s');

        $response = $this->post('/api/book', [
            'classroom_id' => $classroom->id,
            'user' => 'test@example.com',
            'start_time' => $startTime
        ]);

        $response->assertStatus(201)
                    ->assertJsonStructure([
                        "success",
                        "data",
                        "message",
                        "code",
                    ]);

        $this->assertDatabaseHas('bookings', [
            'classroom_id' => $classroom->id,
            'user' => 'test@example.com',
            'start_time' => $startTime
        ]);
    }

    /** @test */
    public function it_prevents_booking_with_conflicting_schedule()
    {
        // Primero, hago el booking
        $classroom = Classroom::factory()->create();

        $startTime = Carbon::parse('next Monday')->setTime(9, 0, 0)->format('Y-m-d H:i:s');

        $this->post('/api/book', [
            'classroom_id' => $classroom->id,
            'user' => 'test@example.com',
            'start_time' => $startTime
        ]);

        // Despues intengo bookear en con conficlto
        $classroom2 = Classroom::factory()->create();
        $startTime = Carbon::parse('next Monday')->setTime(10, 0, 0)->format('Y-m-d H:i:s');

        $response2 = $this->post('/api/book', [
            'classroom_id' => $classroom2->id,
            'user' => 'test@example.com',
            'start_time' => $startTime
        ]);

        $response2->assertStatus(400)
                    ->assertJson([
                        "success" => false,
                        "message" => "Invalid start time for the classroom schedule"
                    ]);
    }


    /** @test */
    public function it_prevents_booking_in_a_full_class()
    {
        $classroom = Classroom::factory()->create();

        $startTime = Carbon::parse('next Monday')->setTime(9, 0, 0)->format('Y-m-d H:i:s');
        //lleno la sala
        for ($i = 0; $i < 10; $i++) {
            $this->post('/api/book', [
                'classroom_id' => $classroom->id,
                'user' => "test$i@example.com",
                'start_time' => $startTime
            ]);
        }

        $response = $this->post('/api/book', [
            'classroom_id' => $classroom->id,
            'user' => 'test10@example.com',
            'start_time' => $startTime
        ]);
                
        $response->assertStatus(400)
                 ->assertJson([
                    "success" => false,
                    "message" => "Classroom is full for this time slot"
                 ]);
    }

    
    /** @test */
    public function it_cancels_a_booking_successfully()
    {
        $classroom = Classroom::factory()->create();

        $startTime = Carbon::parse('next Monday')->setTime(9, 0, 0)->format('Y-m-d H:i:s');

        $response = $this->post('/api/book', [
            'classroom_id' => $classroom->id,
            'user' => 'test@example.com',
            'start_time' => $startTime
        ]);

        $this->assertDatabaseHas('bookings', [
            'classroom_id' => $classroom->id,
            'user' => 'test@example.com',
            'start_time' => $startTime
        ]);

        $bookingId = $response['data']['bookingId']['id'];

        $response2 = $this->delete("/api/cancel/$bookingId", [
            'user' => 'test@example.com',
        ]);
        
        
        $response2->assertStatus(200)
                 ->assertJson([
                        "success" => true,
                        "message" => "Booking canceled successfully",
                 ]);

        $this->assertDatabaseMissing('bookings', [
            'id' => $bookingId
        ]);
    }

}
