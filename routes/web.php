<?php

use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/bookings/{booking}/guests', function (Booking $booking) {
    return Inertia::render('BookingGuest', [
        'bookingId' => $booking->id,
    ]);
})->name('booking.guests');
