<?php

namespace App\Models\User;

use App\Models\Reservation;
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
        UserAttributes, LogsActivity, Slugable, HasFactory;

    protected static $logFillable = true;

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'gender' => 'boolean'];
    protected $fillable = ['name', 'email', 'password', 'username', 'picture', 'gender'];

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

    public function tickets()
    {
        return new Ticket($this);
    }

    public function reserveIn($ticket)
    {
        $output = $this->canReserveIn($ticket->event->specific());

        if(is_null($output)) {
            flash()->error("Something went wrong for $this->name");

            return false;
        }

        if($output->hasMessage())
            flash()->error($output->message());

        if($output->isDenied())
            return false;

        $reservation = new Reservation(
            array_merge([
                'ticket_id' => $ticket->id
            ], $output->body() ?? []));

        $this->reservations()->save($reservation);

        return $reservation;
    }

    public function canReserveIn($event)
    {
        $output = null;

        foreach ($event->conditions() as $condition)
        {
            $output = app($condition)->check($event, $this);

            if($output->shouldContinue())
                continue;

            break;
        }

        return $output;
    }

    public function isAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public function isUser()
    {
        return $this->hasRole('user');
    }

    public function isSignedIn()
    {
        return $this->id == \Auth::id();
    }
}
