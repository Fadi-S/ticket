<?php

use App\Models\Ticket;
use App\Models\User\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.created',
    fn(User $user) => $user->can('users.view')
);