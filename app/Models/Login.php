<?php

namespace App\Models;

use App\Models\User\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $dates = [
        'time'
    ];

    public static function saveCurrentSession()
    {
        if(!Auth::check())
            return;

        $sessionId = request()->getSession()->getId();

        $user = Auth::user();

        $cacheKey = 'users.last_login.' . auth()->id();
        $lastLogin = Carbon::parse(\Cache::get($cacheKey) ?? '1970-01-01');

        if($lastLogin->greaterThan(now()->subHour()))
            return;

        $client = [
            'agent' => request()->header('User-Agent'),
        ];

        if(!isset($_SERVER['REMOTE_ADDR'])) {
            return ;
        }

        \Cache::set($cacheKey, now()->format('Y-m-d H:i:s'));

        self::create([
            'user_id' => $user->id,
            'time' => now(),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'session_id' => $sessionId,
            'device' => json_encode($client)
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
