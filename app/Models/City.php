<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
    ];

    public function departingTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'from_city_id');
    }

    public function arrivingTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'to_city_id');
    }
}
