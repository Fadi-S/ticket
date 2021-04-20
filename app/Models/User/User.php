<?php

namespace App\Models\User;

use App\Helpers\CanReserveInEvents;
use App\Helpers\HasFriends;
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

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'gender' => 'boolean'];
    protected $fillable = ['name', 'arabic_name', 'email', 'password', 'username', 'picture', 'gender', 'phone', 'national_id', 'creator_id'];

    protected $with = ['roles.permissions'];

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
        return $this->hasAnyRole('user', 'deacon') || $this->roles->isEmpty();
    }

    public function isSignedIn() : bool
    {
        return $this->id == \Auth::id();
    }

    public function preferredLocale()
    {
        return $this->locale ?? config('app.locale');
    }
}
