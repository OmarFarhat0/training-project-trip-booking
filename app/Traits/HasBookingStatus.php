<?php

namespace App\Traits;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasBookingStatus
{
    public function isPending(): bool
    {
        return $this->status === BookingStatus::Pending;
    }

    public function isConfirmed(): bool
    {
        return $this->status === BookingStatus::Confirmed;
    }

    public function isCancelled(): bool
    {
        return $this->status === BookingStatus::Cancelled;
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            BookingStatus::Pending,
            BookingStatus::Confirmed,
        ]);
    }
}
