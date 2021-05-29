<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'url';
    }

    public function getLocaleNameAttribute($name)
    {
        return app()->getLocale() === 'ar' ? $this->arabic_name : $name;
    }

    public function getLocalePluralNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->plural_name : $this->name;
    }

    public function scopeShown($query, $show=true)
    {
        $query->where('show', $show);
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'period_type', 'period_id', 'type_id')
            ->withPivot('max_reservations');
    }

    public function conditions()
    {
        return $this->belongsToMany(Condition::class, 'condition_type', 'type_id', 'condition_id')
            ->withPivot(['church_id', 'order']);
    }

    public function toggleCondition(Condition $condition, $church_id=1)
    {
        $conditions = $this->conditions()
            ->wherePivot('church_id', '=', $church_id)
            ->get();

        if($conditions->where('id', $condition['id'])->isNotEmpty()) {
            $conditions = $conditions->reject(fn($cond) => $cond['id'] === $condition['id']);
        }else{
            $conditions = $conditions->push($condition);
        }

        $this->setConditions($conditions->toArray());

        \Cache::forget('conditions.' . $this->id . ".$church_id");
    }

    public function churches()
    {
        return $this->belongsToMany(Church::class, 'condition_type', 'type_id', 'church_id')
            ->withPivot(['condition_id', 'order']);
    }

    public function getColorAttribute($color)
    {
        if($color)
            return $color;

        $colors = collect([
            '#5658e8',
            '#37ad28',
            '#72727d',
            '#b00d02',
            '#323236',
            '#62b9d1',
        ]);

        return $colors->get($this->id -1, '#62b9d1');
    }

    public function getColorNamesAttribute()
    {
        $adjustColor = function ($hex, $steps) {
            $steps = max(-255, min(255, $steps));

            // Normalize into a six character long hex string
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $hex = str_repeat(substr($hex,0,1), 2) .
                    str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
            }

            // Split into three parts: R, G and B
            $color_parts = str_split($hex, 2);
            $return = '#';

            foreach ($color_parts as $color) {
                $color   = hexdec($color); // Convert to decimal
                $color   = max(0,min(255,$color + $steps)); // Adjust color
                $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
            }

            return $return;
        };


        return [
            'border' => $adjustColor($this->color, 60),
            'background' => $adjustColor($this->color, 60),
            'text' => $adjustColor($this->color, -150),
        ];
    }

    public function getConditions($church_id=1)
    {
        return \Cache::remember(
            'conditions.' . $this->id . ".$church_id",
            now()->addDay(),
            fn() => $this->conditions()
            ->wherePivot('church_id', '=', $church_id)
            ->orderBy('priority')
            ->orderBy('order')
            ->pluck('path')
            ->toArray()
        );
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
                ->attach($condition['id'], [
                    'order' => $order,
                    'church_id' => $church_id,
                ]);

            $order++;
        }
    }
}
