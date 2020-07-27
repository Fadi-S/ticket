<?php

namespace App\Models\Event;

use App\Models\Event\EventRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use SoftDeletes, LogsActivity, EventRelationships;

    protected $table = "events";
    protected $fillable = ["start", "end", "number_of_places", "description", "type_id"];
    protected $dates = ['start', 'end'];
    protected static $logFillable = true;
}
