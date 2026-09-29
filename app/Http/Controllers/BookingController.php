<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Booking::with(['trip', 'seat', 'passenger'])->get(),
        ]);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->create($request->validated());

        return response()->json([
            'message' => 'Booking created successfully',
            'data' => $booking,
        ], 201);
    }

    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking): JsonResponse
    {
        $booking = $this->bookingService->updateStatus(
            $booking,
            $request->enum('status', BookingStatus::class),
        );

        return response()->json([
            'message' => 'Booking status updated successfully',
            'data' => $booking,
        ]);
    }
}
