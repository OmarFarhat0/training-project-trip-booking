<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Seat;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function create(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $trip = Trip::query()->findOrFail($data['trip_id']);

            if ($trip->departure_time->isPast()) {
                throw ValidationException::withMessages([
                    'trip_id' => 'This trip has already departed.',
                ]);
            }

            $seat = Seat::query()
                ->where('bus_id', $trip->bus_id)
                ->where('seat_number', $data['seat_number'])
                ->lockForUpdate()
                ->first();

            if ($seat === null) {
                throw ValidationException::withMessages([
                    'seat_number' => 'The seat does not exist on this trip\'s bus.',
                ]);
            }

            $alreadyBooked = Booking::query()
                ->active()
                ->where('trip_id', $trip->id)
                ->where('seat_id', $seat->id)
                ->exists();

            if ($alreadyBooked) {
                throw ValidationException::withMessages([
                    'seat_number' => 'The seat is already booked.',
                ]);
            }

            $passenger = Passenger::query()->firstOrCreate(
                ['phone' => $data['phone']],
                ['name' => $data['name']],
            );

            return Booking::create([
                'trip_id' => $trip->id,
                'seat_id' => $seat->id,
                'passenger_id' => $passenger->id,
                'status' => BookingStatus::Pending,
            ]);
        });
    }

    public function updateStatus(Booking $booking, BookingStatus $status): Booking
    {
        if ($booking->isCancelled()) {
            throw ValidationException::withMessages([
                'status' => 'A cancelled booking cannot change status.',
            ]);
        }

        if ($status === BookingStatus::Pending) {
            throw ValidationException::withMessages([
                'status' => 'Cannot change a booking back to pending.',
            ]);
        }

        if ($status === BookingStatus::Confirmed && ! $booking->isPending()) {
            throw ValidationException::withMessages([
                'status' => 'Only pending bookings can be confirmed.',
            ]);
        }

        $booking->update(['status' => $status]);

        return $booking->fresh(['trip', 'seat', 'passenger']);
    }
}
