<?php

namespace App\Models;

use App\Reservations\Conditions\{
    EnoughSpaceInEvent,
    HaveMassTickets,
    NotAlreadyReserved,
    QualifiesForException,
    ReservedByAdmin
};
use App\Reservations\EventContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mass extends Event implements EventContract
{
    use HasFactory;

    protected $attributes = [
        'type_id' => 1,
    ];

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', 1)
        );
    }

    public function maxReservations(): int
    {
        return config('settings.max_reservations_per_month');
    }

    public function hoursForException(): int
    {
        return config('settings.hours_to_allow_for_exception');
    }

    public function allowsException(): bool
    {
        return config('settings.allow_for_exceptions');
    }

    public function conditions()
    {
        return [
            NotAlreadyReserved::class,
            ReservedByAdmin::class,
            EnoughSpaceInEvent::class,
            QualifiesForException::class,
            HaveMassTickets::class,
        ];
    }
}
