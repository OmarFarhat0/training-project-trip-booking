<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passenger extends Model
{
    protected $fillable = [
        'name',
        'phone',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
