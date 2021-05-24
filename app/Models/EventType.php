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

    public function conditions()
    {
        return $this->belongsToMany(Condition::class, 'condition_type', 'condition_id', 'type_id')
            ->withPivot(['church_id', 'order']);
    }

    public function churches()
    {
        return $this->belongsToMany(Church::class, 'condition_type', 'church_id', 'type_id')
            ->withPivot(['condition_id', 'order']);
    }

    public function getConditions($church_id=1)
    {
        return $this->conditions()
            ->wherePivot('church_id', '=', $church_id)
            ->orderBy('priority')
            ->orderBy('order')
            ->pluck('path')
            ->toArray();
    }

    public function setConditions($conditions, $church_id=1)
    {
        $this->conditions()
            ->wherePivot('church_id', '=', $church_id)
            ->detach();

        $order = 1;

        foreach ($conditions as $condition)
        {
            $this->conditions()
                ->attach($condition->id, [
                    'order' => $order,
                    'church_id' => $church_id,
                ]);

            $order++;
        }
    }
}
