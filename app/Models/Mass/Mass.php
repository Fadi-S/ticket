<?php

namespace App\Models\Mass;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Mass extends Model
{
    use SoftDeletes, LogsActivity, MassAttributes;

    protected $table = "events";
    protected $fillable = ["time", "number_of_places", "description"];
    protected $dates = ['time'];
    protected $attributes = [
        'type_id' => 1,
    ];
    protected static $logFillable = true;

    protected static function booted()
    {
        static::addGlobalScope('event_type', function (Builder $builder) {

            $builder->where('type_id', "=", 1);

        });
    }

}
