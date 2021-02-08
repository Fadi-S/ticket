<?php

namespace App\Models\User;

use App\Models\Friendship;
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
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'gender' => 'boolean'];
    protected $fillable = ['name', 'arabic_name', 'email', 'password', 'username', 'picture', 'gender', 'phone', 'national_id'];

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

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function tickets() : Ticket
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

        /*if($this->email) {
            \Mail::raw("Hello {$this->name}, \n\nYou have a reservation in "
                . $ticket->event->start->format('l, jS \o\f F') . "'s " . $ticket->event->type->name,

                fn($message) => $message->to($this->email)
                    ->subject($ticket->event->type->name . ' Reservation Invoice')
            );
        }*/

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

    public function addFriend($user, $forceConfirm=false)
    {
        if($this->isFriendsWith($user))
            return;

        $friendship = Friendship::create([
            'sender_id' => $this->id,
        ]);

        $user->friendships()->save($friendship);
        $this->friendships()->save($friendship);

        if($forceConfirm)
            $this->confirmFriend($user);
    }

    public function confirmFriend($user)
    {
        $this->friendships()
            ->whereHas('users', fn($query) => $query->where('id', $user->id))
            ->update(['confirmed_at' => now()]);
    }

    public function friends($withUnconfirmed=false)
    {
        $friendships = $this->friendships()
            ->when(!$withUnconfirmed,
                fn($query) => $query->where('friendships.confirmed_at', '<>', null)
            )->pluck('id');

        return User::where('users.id', '<>', $this->id)
            ->join('friendship_user', 'users.id', '=', 'friendship_user.user_id')
            ->join('friendships', 'friendship_user.friendship_id', '=', 'friendships.id')
            ->whereIn('friendships.id', $friendships)
            ->select('users.*');
    }

    public function friendships()
    {
        return $this->belongsToMany(Friendship::class, 'friendship_user', 'user_id', 'friendship_id');
    }

    public function isFriendsWith($user) : bool
    {
        return $this->friends(true)
            ->where('users.id', $user->id)
            ->exists();
    }

    public function isAdmin() : bool
    {
        return $this->hasRole('super-admin');
    }

    public function isUser() : bool
    {
        return $this->hasRole('user') ? true : $this->roles->isEmpty();
    }

    public function isSignedIn() : bool
    {
        return $this->id == \Auth::id();
    }
}
