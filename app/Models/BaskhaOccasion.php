<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent,
    EventDateHasNotPassed,
    HaveBaskhaOccasionTickets,
    HaveBaskhaTickets,
    IsDeaconReservation,
    NotAlreadyReserved,
    QualifiesForException,
    ReservedByAdmin};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaskhaOccasion extends Event implements EventContract
{
    use HasFactory;

    protected $attributes = ['type_id' => 5];

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', 5)
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
            EventDateHasNotPassed::class,
            NotAlreadyReserved::class,
            IsDeaconReservation::class,
            ReservedByAdmin::class,
            EnoughSpaceInEvent::class,
            QualifiesForException::class,
            HaveBaskhaOccasionTickets::class,
        ];
    }
}
