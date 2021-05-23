<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent, EventDateHasNotPassed, HaveKiahkTickets, MustHaveNationalID, NotAlreadyReserved, QualifiesForException, ReservedByAdmin, UserIsActive};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kiahk extends Event implements EventContract
{
    use HasFactory;

    protected $attributes = ['type_id' => 2];
    public static int $type = 2;

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
            UserIsActive::class,
            MustHaveNationalID::class,
            EventDateHasNotPassed::class,
            NotAlreadyReserved::class,
            ReservedByAdmin::class,
            EnoughSpaceInEvent::class,
            QualifiesForException::class,
            HaveKiahkTickets::class,
        ];
    }
}
