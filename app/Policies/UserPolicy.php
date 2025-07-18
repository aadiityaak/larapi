<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // viewAny
    public function viewAny(User $user)
    {
        return $user->can('user:read');
    }

    public function view(User $user, User $model)
    {
        return $user->can('user:read');
    }

    public function create(User $user)
    {
        return $user->can('user:create');
    }

    public function update(User $user, User $model)
    {
        return $user->can('user:update');
    }

    public function delete(User $user, User $model)
    {
        return $user->can('user:delete');
    }
}
