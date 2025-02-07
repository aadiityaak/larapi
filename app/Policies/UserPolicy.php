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
        return $authUser->id === $user->id || (int) $authUser->is_admin === 1;
    }

    public function delete(User $authUser, User $user)
    {
        // Memeriksa apakah pengguna yang sedang login adalah pengguna yang sama
        // atau jika pengguna tersebut adalah admin
        // dan jika user yang dihapus bukan admin
        $authUser->is_admin = (int) $authUser->is_admin;
        return ($authUser->id === $user->id || $authUser->is_admin === 1) && $user->is_admin !== 1;
    }
}
