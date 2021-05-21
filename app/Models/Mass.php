<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent,
    EventDateHasNotPassed,
    HaveEventTickets,
    HaveMassTickets,
    IsDeaconReservation,
    MustHaveFullName,
    MustHaveNationalID,
    NotAlreadyReserved,
    QualifiesForException,
    ReservationStillOpen,
    ReservedByAdmin};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mass extends Event implements EventContract
{
    use HasFactory;

    public static int $type = 1;
    public bool $hasDeacons = true;

    protected $attributes = ['type_id' => 1];

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', self::$type)
        );
    }

    static public function maxReservations(): int
    {
        return config('settings.mass.max_reservations_per_period');
    }
}
