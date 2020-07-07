<?php

namespace App\Policies;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user) {}

    public function view(User $user, User $model)
    {
        return $user->can("users.view") ? true : null;
    }

    public function create(User $user)
    {
        return $user->can("users.create") ? true : null;
    }

    public function store(User $user)
    {
        return $user->can("users.create") ? true : null;
    }

    public function edit(User $user, User $model)
    {
        return $user->can("users.edit") ? true : null;
    }

    public function update(User $user, User $model)
    {
        return $user->can("users.edit") ? true : null;
    }

    public function delete(User $user, User $model)
    {
        return $user->can("users.delete") ? true : null;
    }

    public function restore(User $user, User $model)
    {
        return $user->can("users.delete") && $user->can("activityLog") ? true : null;
    }

    public function forceDelete(User $user, User $model)
    {
        return $user->can("users.forceDelete") ? true : null;
    }
}
