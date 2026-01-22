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
    return Guest::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
           return $guest;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
           $guest->update($request->all());
           return $guest;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      $guest->delete();
         return response()->noContent();
    }
}
