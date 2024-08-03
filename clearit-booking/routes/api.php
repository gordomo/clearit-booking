<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/classes', [ClassroomController::class, 'index']);
Route::get('/classes/user', [ClassroomController::class, 'userBooksList']);
Route::post('/book', [ClassroomController::class, 'book']);
Route::delete('/cancel/{id}', [ClassroomController::class, 'cancel']);