<?php

namespace App\Models\User;

use App\Helpers\CanReserveInEvents;
use App\Helpers\DataFromNationalID;
use App\Helpers\HasFriends;
use App\Models\EmailBlacklist;
use App\Traits\Slugable;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasLocalePreference
{
    use Notifiable, SoftDeletes, HasApiTokens, HasRoles, CausesActivity, Searchable,
        UserAttributes, LogsActivity, Slugable, HasFactory, HasFriends, CanReserveInEvents;

    public static int $minPassword = 6;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;
    protected static $logAttributesToIgnore = ['password'];

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'gender' => 'boolean'];
    protected $fillable = ['name', 'arabic_name', 'email', 'password', 'username', 'picture', 'gender', 'phone', 'national_id', 'creator_id', 'activated_at', 'location_id'];
    protected $dates = [
        'activated_at',
        'expiration'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        static::$separator = ".";
        static::$slug = "username";
    }

    public function getIdentifierAttribute()
    {
        return $this->national_id
            ?? $this->phone
            ?? $this->email
            ?? $this->id;
    }

    public function isDisabled() : bool
    {
        return $this->disabled_at !== null;
    }

    public function isAdmin() : bool
    {
        return $this->hasRole('super-admin');
    }

    public function isDeacon() : bool
    {
        return $this->hasAnyRole('deacon', 'deacon-admin');
    }

    public function isUser() : bool
    {
        return $this->hasAnyRole('user', 'deacon', 'kashafa') || $this->roles->isEmpty();
    }

    public function isGuest()
    {
        return !! $this->expiration;
    }

    public function isSignedIn() : bool
    {
        return $this->id == \Auth::id();
    }

    public function NID()
    {
        return DataFromNationalID::create($this->national_id);
    }

    public function routeNotificationForMail($notification = null)
    {
        if (! EmailBlacklist::isBlacklisted($this->email)) {
            return $this->email;
        }

        return null;
    }

    public function preferredLocale()
    {
        return $this->locale ?? config('app.locale');
    }

    public function verify()
    {
        $this->verified_at = now();
        $this->save();
    }

    public function isActive()
    {
        return !! $this->activated_at;
    }
}
