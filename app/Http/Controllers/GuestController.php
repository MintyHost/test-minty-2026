<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index() {
    return Guest::all();

}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'name' => 'required|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
            ]);

    return Guest::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest) //al poner guest reconoce el modelo del que le hablamos
    {
           return $guest;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
           $guest->update($request->all());
           return $guest;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
      $guest->delete();
         return response()->noContent();
    }
}
