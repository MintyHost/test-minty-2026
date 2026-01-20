<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MintyTestController extends Controller
{
    public function getBookings()
    {
        try {
            // Fake sleep to simulate a long loading time
            sleep(2);

            $bookings = Booking::select('id', 'checkin_at', 'checkout_at')->get();

            return response()->json($bookings);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
