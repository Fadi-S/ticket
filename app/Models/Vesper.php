<?php

namespace App\Models;

use App\Reservations\Conditions\{EnoughSpaceInEvent, EventDateHasNotPassed, HaveVesperTickets, MustHaveFullName, MustHaveNationalID, NotAlreadyReserved, QualifiesForException, ReservedByAdmin};
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
            HaveVesperTickets::class,
        ];
    }
}
