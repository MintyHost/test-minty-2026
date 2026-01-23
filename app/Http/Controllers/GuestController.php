<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function store(StoreGuestRequest $request)
    {
        $validated = $request->validated();

        $guest = Guest::create($validated);

        return response()->json($guest, 201);
    }
}
