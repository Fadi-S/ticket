<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent,
    EventDateHasNotPassed,
    HaveBaskhaOccasionTickets,
    HaveBaskhaTickets,
    IsDeaconReservation,
    NotAlreadyReserved,
    QualifiesForException,
    ReservedByAdmin,
    UserIsActive};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaskhaOccasion extends Event implements EventContract
{
    use HasFactory;

    protected $attributes = ['type_id' => 5];
    public static int $type = 5;

    public int $deaconNumber = 0;

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', self::$type)
        );
    }

    static public function maxReservations(): int
    {
        return 1;
    }

    static public function hoursForException(): int
    {
        return 16;
    }

    static public function allowsException(): bool
    {
        return true;
    }

    static public function conditions()
    {
        return [
            UserIsActive::class,
            EventDateHasNotPassed::class,
            NotAlreadyReserved::class,
            ReservedByAdmin::class,
            EnoughSpaceInEvent::class,
            IsDeaconReservation::class,
            QualifiesForException::class,
            HaveBaskhaOccasionTickets::class,
        ];
    }
}
