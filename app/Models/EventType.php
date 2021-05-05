<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $guarded = [];

    public function getNameAttribute($name)
    {
        return app()->getLocale() === 'ar' ? $this->arabic_name : $name;
    }

    public function getLocaleNameAttribute($name)
    {
        return app()->getLocale() === 'ar' ? $this->arabic_name : $name;
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'period_type', 'period_id', 'type_id')
            ->withPivot('max_reservations');
    }
}
