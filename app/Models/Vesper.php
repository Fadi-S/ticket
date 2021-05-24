<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent,
    EventDateHasNotPassed,
    HaveVesperTickets,
    IsDeaconReservation,
    MustHaveFullName,
    MustHaveNationalID,
    NotAlreadyReserved,
    QualifiesForException,
    ReservedByAdmin,
    UserIsActive};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vesper extends Event implements EventContract
{
    use HasFactory;

    protected $attributes = ['type_id' => 4];
    public static int $type = 4;
    public bool $hasDeacons = true;
    public int $deaconNumber = 20;

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', self::$type)
        );
    }

    static public function maxReservations(): int
    {
        return config('settings.vesper.max_reservations_per_period');
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
            QualifiesForException::class,
            IsDeaconReservation::class,
            HaveVesperTickets::class,
        ];
    }
}
