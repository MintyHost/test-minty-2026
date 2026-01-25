<?php

use App\Http\Controllers\MintyTestController;
use Illuminate\Support\Facades\Route;

Route::get('/bookings', [MintyTestController::class, 'getBookings']);
Route::get('/bookings/{booking}/guests', [MintyTestController::class, 'index']);
Route::post('/bookings/{booking}/guests', [MintyTestController::class,'store']);
Route::put('/bookings/{booking}/guests/{guest}', [MintyTestController::class,'update']);
Route::delete('/bookings/{booking}/guests/{guest}', [MintyTestController::class, 'destroy']);