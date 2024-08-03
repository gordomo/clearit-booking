<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClassroomService;
use App\Services\BookingService;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    protected $classroomService;
    protected $bookingService;

    public function __construct(ClassroomService $classroomService, BookingService $bookingService)
    {
        $this->classroomService = $classroomService;
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        $classes = $this->classroomService->getAllClasses();
        return $this->sendResponse(['success' => true, 'data' => $classes, 'code' => 200]);
    }

    public function userBooksList(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make(
            $input,
            [
                'user' => 'required|email',
            ]);

        if ( $validator->fails() ) {
            return $this->sendResponse(['success' => false, 'message' => $validator->errors(), 'code' => 422]);
        }
        
        $userBookList = $this->bookingService->getUserBookList($input['user']);
        return $this->sendResponse(['success' => true, 'data' => $userBookList, 'code' => 200]);
    }

    public function book(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make(
            $input,
            [
                'classroom_id' => 'required|exists:classrooms,id',
                'user' => 'required|email',
                'start_time' => 'required|date_format:Y-m-d H:i:s',
            ]);

        if ( $validator->fails() ) {
            return $this->sendResponse(['success' => false, 'message' => $validator->errors(), 'code' => 422]);
        }

        try {
            $bookingId = $this->bookingService->bookClass($request->classroom_id, $request->user, $request->start_time);
            return $this->sendResponse(['success' => true, 'data' => ['bookingId' => $bookingId], 'message' => 'Booking created successfully', 'code' => 201]);
        } catch (\Exception $e) {
            return $this->sendResponse(['success' => false, 'message' => $e->getMessage(), 'code' => 400]);
        }
    }

    public function cancel(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make(
            $input,
            [
                'user' => 'required|email',
            ]);

        if ( $validator->fails() ) {
            return $this->sendResponse(['success' => false, 'message' => $validator->errors(), 'code' => 422]);
        }

        try {
            $this->bookingService->cancelBooking($id, $input['user']);
            return $this->sendResponse(['success' => true, 'message' => 'Booking canceled successfully', 'code' => 200]);
        } catch (\Exception $e) {
            return $this->sendResponse(['success' => false, 'message' => $e->getMessage(), 'code' => 400]);
        }
    }
}
