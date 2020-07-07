<?php

namespace App\Models\Mass;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Mass extends Model
{
    use SoftDeletes;
    protected $table = "events";

    protected static function booted()
    {
        static::addGlobalScope('event_type', function (Builder $builder) {

            $builder->where('type_id', "=" ,1);

        });
    }

}
