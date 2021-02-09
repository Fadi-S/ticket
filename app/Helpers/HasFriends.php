<?php

namespace App\Helpers;

use App\Models\Friendship;
use App\Models\User\User;

trait HasFriends {
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
}