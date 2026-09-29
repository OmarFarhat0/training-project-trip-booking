<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TripService $tripService): JsonResponse
    {
        return response()->json([
            'data' => $tripService->listWithAvailability(),
        ]);
    }

    /**
     * List seats for a trip with availability.
     */
    public function seats(Trip $trip, TripService $tripService): JsonResponse
    {
        return response()->json([
            'data' => $tripService->seatsForTrip($trip),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        $trip = Trip::create($request->validated());

        return response()->json([
            'message' => 'Trip created successfully',
            'data' => $trip,
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        //
    }
}
