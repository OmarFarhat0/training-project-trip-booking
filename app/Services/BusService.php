<?php

namespace App\Services;

use App\Models\Bus;
use Illuminate\Support\Facades\DB;

class BusService
{
    public function create(array $data): Bus
    {
        return DB::transaction(function () use ($data) {
            $bus = Bus::create([
                'plate_number' => $data['plate_number'],
                'seats_count' => $data['seats_count'],
            ]);

            $seats = [];

            for ($number = 1; $number <= $bus->seats_count; $number++) {
                $seats[] = [
                    'seat_number' => (string) $number,
                ];
            }

            $bus->seats()->createMany($seats);

            return $bus;
        });
    }
}
