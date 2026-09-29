<?php

namespace App\Services;

use App\Models\Trip;
use Illuminate\Support\Collection;

class TripService
{
    public function listWithAvailability(): Collection
    {
        return Trip::query()
            ->with(['fromCity', 'toCity', 'bus'])
            ->withCount(['bookings as bookings_count' => function ($query): void {
                $query->active();
            }])
            ->get()
            ->each(function (Trip $trip): void {
                $trip->setAttribute(
                    'available_seats',
                    $trip->bus->seats_count - $trip->bookings_count,
                );
            });
    }

    public function seatsForTrip(Trip $trip): array
    {
        $trip->load(['bus.seats' => function ($query): void {
            $query->orderBy('id');
        }]);

        $bookedSeatIds = $trip->bookings()
            ->active()
            ->pluck('seat_id')
            ->flip();

        $seats = $trip->bus->seats->map(fn ($seat): array => [
            'seat_number' => $seat->seat_number,
            'available' => ! $bookedSeatIds->has($seat->id),
        ])->values()->all();

        return [
            'trip_id' => $trip->id,
            'seats' => $seats,
        ];
    }
}
