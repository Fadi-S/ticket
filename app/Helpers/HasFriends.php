<?php

namespace App\Helpers;

use App\Models\Friendship;
use App\Models\User\User;
use Illuminate\Support\Facades\Cache;

trait HasFriends {
    public function addFriend($user, $forceConfirm=false)
    {
        if($this->isFriendsWith($user))
            return;

        $friendship = Friendship::create([
            'sender_id' => $this->id,
        ]);

        $user->friendships()->save($friendship);
        $this->friendships()->attach($friendship);

        if($forceConfirm)
            $this->confirmFriend($user);
    }

    public function forceAddFriend($user)
    {
        $this->addFriend($user, true);
    }

    public function rejectFriend($user)
    {
        return $this->getFriendship($user)->delete();
    }

    public function getFriendship($user)
    {
        return $this->friendships()
            ->whereHas('users', fn($query) => $query->where('id', $user->id));
    }

    public function deleteFriend($user)
    {
        return $this->rejectFriend($user);
    }

    public function isWithinFriendsLimit()
    {
        $limit = config('settings.friends_limit');

        return ($limit < 0) || ($this->friendships()->count() < $limit);
    }

    public function confirmFriend($user)
    {
        $this->getFriendship($user)->update(['confirmed_at' => now()]);

        Cache::tags(["friends"])->forget("friends." . auth()->id());
    }

    public function scopeHasFriends($query)
    {
        $friends = Cache::tags(["friends"])
            ->remember("friends." . auth()->id(), now()->addHour(),
            fn() => auth()->user()->friends()->pluck('id')
        );

        $query->where(function ($query) use ($friends) {
            $query->whereIn('id', $friends)
                ->orWhere('id', auth()->id());
        });
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

    public function unconfirmedFriendRequests()
    {
        return $this->friendships()
            ->where('confirmed_at', '=', null)
            ->where('sender_id', '<>', $this->id);
    }

    public function friendships()
    {
        return $this->belongsToMany(Friendship::class, 'friendship_user', 'user_id', 'friendship_id');
    }

    public function isFriendsWith($user, $withUnconfirmed=true) : bool
    {
        return $this->friends($withUnconfirmed)
            ->where('users.id', $user->id)
            ->exists();
    }
}
