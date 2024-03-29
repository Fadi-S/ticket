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

    public function disable(User $admin, User $model=null)
    {
        return $admin->can('users.disable') ? true : null;
    }

    public function create(User $admin)
    {
        return $admin->can('users.create') || (config('settings.allow_users_to_create_accounts') && config('settings.allow_users_to_make_accounts_for_others'))
            ? true
            : null;
    }

    public function update(User $admin, User $model)
    {
        if($admin->isUser()) {
            if($model->isActive()) {
                return null;
            }

            return !$model->isActive() && ($model->isSignedIn() || $model->creator_id === $admin->id) ? true : null;
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

    public function viewStatistics(User $admin)
    {
        return $admin->can("viewStatistics") ? true : null;
    }

    public function activate(User $admin)
    {
        return $admin->can("users.activate") ? true : null;
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
