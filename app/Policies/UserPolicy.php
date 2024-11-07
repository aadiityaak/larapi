<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $authUser, User $user)
    {
        // Memeriksa apakah pengguna yang sedang login adalah pengguna yang sama
        // atau jika pengguna tersebut adalah admin
        return $authUser->id === $user->id || $authUser->is_admin === 1;
    }

    public function delete(User $authUser, User $user)
    {
        // Memeriksa apakah pengguna yang sedang login adalah pengguna yang sama
        // atau jika pengguna tersebut adalah admin
        return $authUser->id === $user->id || $authUser->is_admin === 1;
    }
}
