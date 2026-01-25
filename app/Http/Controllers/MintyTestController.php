<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MintyTestController extends Controller
{
    public function getBookings()
    {
        try {
            // Fake sleep to simulate a long loading time
            sleep(2);

            $bookings = Booking::select('id', 'checkin_at', 'checkout_at', 'status')->get();

            return response()->json($bookings);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index(Booking $booking)
    {
        return response()->json($booking->guest);
    }

    public function store(Request $request, Booking $booking)
    {
        if($booking->guest) {
            return response()->json(['error' => 'Guest already exists for this booking'], 400);
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'document_number' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
        ]);

        $guest = Guest::create($data);
        $booking->guest()->associate($guest);
        $booking->save();
        return response()->json($guest, 201);
    }

    public function update(Request $request, Booking $booking, Guest $guest)
    {
        if((int)$booking->guest_id !== (int)$guest->id) {
            return response()->json(['error' => 'Guest not found for this booking'], 404);
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'document_number' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
        ]);

        $guest = $booking->guest;
        if (!$guest) {
            return response()->json(['error' => 'Guest not found'], 404);
        }

        $guest->update($data);
        return response()->json($guest->fresh());
    }

    public function destroy(Booking $booking, Guest $guest)
    {
        if ((int)$booking->guest_id !== (int)$guest->id) {
            return response()->json(['error' => 'Guest not found for this booking'], 404);
        }

        $booking->guest()->dissociate();
        $booking->save();

        $guest->delete();

        return response()->json(null, 204);
    }
}
