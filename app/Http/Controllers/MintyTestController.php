<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MintyTestController extends Controller
{
    public function getBookings(Request $request)
    {
        try {
            // Fake sleep to simulate a long loading time
            sleep(2);

            $query = Booking::with('guest');

            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->whereHas('guest', function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                });
            }

            $query->orderBy('checkin_at', 'desc');

            return $query->paginate(9);

        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
