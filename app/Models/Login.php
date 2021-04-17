<?php

namespace App\Models;

use App\Models\User\User;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public static function saveCurrentSession()
    {
        if(!Auth::check())
            return;

        $sessionId = request()->getSession()->getId();

        if(self::where('session_id', $sessionId)->exists())
            return;

        $client = [
            'agent' => request()->header('User-Agent'),
        ];

        self::create([
            'user_id' => Auth::id(),
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
