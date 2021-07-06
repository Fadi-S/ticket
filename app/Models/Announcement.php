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

    public static function getCurrentForUser($user=null)
    {
        $user ??= auth()->user();
        $announcements = self::getCurrent();

        return $announcements->filter(function ($ann) use($user) {
            if(! $ann->target) {
                return true;
            }

            $conditions = json_decode($ann->target, true);

            foreach ($conditions as $con) {
                $allow = true;

                foreach ($con as $key => $value) {
                    if(str_starts_with($key, 'not_')) {
                        continue;
                    }

                    $allow = ($user[$key] ?? null) == $value;

                    if(array_key_exists('not_' . $key, $con)) {
                        $allow = !$allow;
                    }

                    if(! $allow)
                        break;
                }

                if($allow)
                    return true;
            }

            return false;
        });
    }

    public function hasURL() : bool
    {
        return !! $this->url;
    }
}
