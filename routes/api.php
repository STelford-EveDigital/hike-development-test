<?php

use App\Http\Controllers\Api\AppointmentBookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/post-vehicle-registration', [AppointmentBookingController::class, 'postVehicleRegistration'])->name('api.post.vehicle-registration');
Route::get('/booked-dates', [AppointmentBookingController::class, 'getBookedDates'])->name('api.get.booked-dates');
Route::post('/timeslots', [AppointmentBookingController::class, 'postTimeslots'])->name('api.post.timeslots');
Route::post('/submit-booking', [AppointmentBookingController::class, 'postSubmitBooking'])->name('api.post.submit-booking');