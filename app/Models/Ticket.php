<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['reserved_at'];
    protected $withCount = ['reservations'];

    public function scopeUser($query)
    {
        if(!auth()->user()->can('tickets.view')) {

            $query->where('reserved_by', \Auth::id())
                ->orWhereHas('reservations',
                    fn($query) => $query->where('user_id', \Auth::id())
                );

        }
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    public function of($user)
    {
        return $user->isAdmin() ?: $this->reservedBy->id == $user->id;
    }

    public function cancel()
    {
        $this->reservations()->delete();

        $this->delete();
    }
}
