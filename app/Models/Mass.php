<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Mass extends Event
{
    protected $attributes = [
        'type_id' => 1,
    ];

    protected static function booted()
    {
        static::addGlobalScope('event_type',
            fn (Builder $builder) => $builder->where('type_id', 1)
        );
    }

}
