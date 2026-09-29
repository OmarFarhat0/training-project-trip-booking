<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained('seats')->cascadeOnDelete();
            $table->foreignId('passenger_id')->constrained('passengers')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
};
