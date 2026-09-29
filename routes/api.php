<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cities', [CityController::class, 'index']);
Route::post('/cities', [CityController::class, 'store']);

Route::get('/buses', [BusController::class, 'index']);
Route::post('/buses', [BusController::class, 'store']);

Route::get('/trips', [TripController::class, 'index']);
Route::get('/trips/{trip}/seats', [TripController::class, 'seats']);
Route::post('/trips', [TripController::class, 'store']);

Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus']);
