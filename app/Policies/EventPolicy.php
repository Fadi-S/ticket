<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function index(User $admin)
    {
        return $admin->can("tickets.view") ? true : null;
    }

    public function create(User $admin)
    {
        return $admin->can("events.create") ? true : null;
    }

    public function store(User $admin)
    {
        return $admin->can("events.create") ? true : null;
    }

    public function edit(User $admin, Event $model)
    {
        return $admin->can("events.edit") ? true : null;
    }

    public function update(User $admin, Event $model)
    {
        return $admin->can("events.edit") ? true : null;
    }

    public function delete(User $admin, Event $model)
    {
        return $admin->can("events.delete") ? true : null;
    }

    public function restore(User $admin, Event $model)
    {
        return $admin->can("events.delete") && $admin->can("activityLog") ? true : null;
    }

    public function forceDelete(User $admin, Event $model)
    {
        return $admin->can("events.forceDelete") ? true : null;
    }
}
