<?php

namespace App\Models;

use App\Reservations\Conditions\{AllowAll,
    EnoughSpaceInEvent,
    EventDateHasNotPassed,
    HaveMassTickets,
    IsDeaconReservation,
    MustHaveFullName,
    MustHaveNationalID,
    NotAlreadyReserved,
    QualifiesForException,
    ReservationStillOpen,
    ReservedByAdmin,
    UserIsActive};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MassOpen extends Event implements EventContract
{
    use HasFactory;

    public static int $type = 6;
    public bool $hasDeacons = true;

    protected $attributes = ['type_id' => 6];

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', self::$type)
        );
    }

    static public function maxReservations(): int
    {
        return 7;
    }

    static public function conditions()
    {
        return [
            //MustHaveNationalID::class,
            UserIsActive::class,
            MustHaveFullName::class,
            EventDateHasNotPassed::class,
            NotAlreadyReserved::class,
            ReservedByAdmin::class,
            EnoughSpaceInEvent::class,
            IsDeaconReservation::class,
            AllowAll::class,
        ];
    }
}
