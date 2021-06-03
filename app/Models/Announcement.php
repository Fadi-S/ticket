<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['start', 'end'];

    public function scopeShown($query)
    {
        $query->where('start', '<=', now())->where('end', '>=', now());
    }

    public static function getCurrent()
    {
        return \Cache::remember('announcements', now()->addHour(),
            fn() => self::shown()->get()
        );
    }

    public function hasURL() : bool
    {
        return !! $this->url;
    }
}
