<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent, EventDateHasNotPassed, HaveVesperTickets, MustHaveNationalID, NotAlreadyReserved, QualifiesForException, ReservedByAdmin};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vesper extends Event implements EventContract
{
    use HasFactory;

    protected $attributes = ['type_id' => 4];
    public static int $type = 4;
    public bool $hasDeacons = false;

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
        return 0;
    }

    static public function allowsException(): bool
    {
        return false;
    }

    static public function conditions()
    {
        return [
            MustHaveNationalID::class,
            EventDateHasNotPassed::class,
            NotAlreadyReserved::class,
            ReservedByAdmin::class,
            EnoughSpaceInEvent::class,
            QualifiesForException::class,
            HaveVesperTickets::class,
        ];
    }
}
