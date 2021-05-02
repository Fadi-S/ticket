<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['start', 'end'];

    public static function fromEvent(Event $event)
    {
        self::create([
            'type_id' => $event->type_id,
            'description' => $event->description,
            'day_of_week' => $event->start->dayOfWeek,
            'number_of_places' => $event->number_of_places,
            'overload' => $event->overload,
            'start' => $event->start->format('H:i'),
            'end' => $event->end->format('H:i'),
            'active' => true,
        ]);
    }

    public function scopeType($query, $type_id)
    {
        $query->where('type_id', '=', $type_id);
    }

    public function scopeActive($query, $active=true)
    {
        $query->where('active', '=', $active);
    }
}
