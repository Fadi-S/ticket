<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent, EventDateHasNotPassed, HaveBaskhaTickets, IsDeaconReservation, NotAlreadyReserved, QualifiesForException, ReservedByAdmin};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Baskha extends Event implements EventContract
{
    use HasFactory;

    public int $deaconNumber = 20;
    public static int $type = 3;
    public bool $hasDeacons = false;

    protected $attributes = ['type_id' => 3];

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', self::$type)
        );
    }

    static public function maxReservations(): int
    {
        return 2;
    }

    static public function hoursForException(): int
    {
        return 16;
    }

    static public function allowsException(): bool
    {
        return true;
    }
    
}
