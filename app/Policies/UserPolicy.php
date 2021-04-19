<?php

namespace App\Policies;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $admin)
    {
        return $admin->can("users.view") ? true : null;
    }

    public function view(User $admin, User $model)
    {
        if($admin->isUser()) {
            return $model->isSignedIn() ? true : null;
        }

        return $admin->can("users.view") ? true : null;
    }

    public function create(User $admin)
    {
        return true;
    }

    public function update(User $admin, User $model)
    {
        if($admin->isUser()) {
            return $model->isSignedIn() ? true : null;
        }

        return $admin->can("users.edit") ? true : null;
    }

    public function delete(User $admin, User $model)
    {
        return $admin->can("users.delete") ? true : null;
    }

    public function restore(User $admin, User $model)
    {
        return $admin->can("users.delete") && $admin->can("activityLog") ? true : null;
    }

    public function forceDelete(User $admin, User $model)
    {
        return $admin->can("users.forceDelete") ? true : null;
    }

    public function viewAgentDetails(User $admin)
    {
        return $admin->can("viewAgentDetails") ? true : null;
    }
}
