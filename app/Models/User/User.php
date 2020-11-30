<?php

namespace App\Models\User;

use App\Models\Reservation;
use App\Traits\CanReserveEvents;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens, HasRoles, CausesActivity,
        UserAttributes, LogsActivity, Slugable, CanReserveEvents, HasFactory;

    protected static $logFillable = true;

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];
    protected $with = ['reservations'];
    protected $fillable = ['name', 'email', 'password', 'username', 'picture'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        static::$separator = ".";
        static::$slug = "username";
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
