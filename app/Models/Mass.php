<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent,
    EventDateHasNotPassed,
    HaveMassTickets,
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

    static public function conditions()
    {
        return [
            //MustHaveNationalID::class,
            MustHaveFullName::class,
            EventDateHasNotPassed::class,
            NotAlreadyReserved::class,
            ReservedByAdmin::class,
            EnoughSpaceInEvent::class,
            QualifiesForException::class,
            HaveMassTickets::class,
        ];
    }
}
