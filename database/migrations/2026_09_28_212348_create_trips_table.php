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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('to_city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->dateTime('departure_time');
            $table->decimal('seat_price', 10, 2);
            $table->timestamps();
        });
    }
};
