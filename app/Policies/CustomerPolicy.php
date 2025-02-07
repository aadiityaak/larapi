<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow admin users to view any customer
        return $user->is_admin === 1;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Customer $customer): bool
    {
        // Allow admin users to view any customer
        // Allow regular users to view their own customers
        return $user->is_admin === 1 || $user->id === $customer->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow admin users to create customers
        return $user->is_admin === 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Customer $customer): bool
    {
        // Allow admin users to update any customer
        // Allow regular users to update their own customers
        return $user->is_admin === 1 || $user->id === $customer->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Customer $customer): bool
    {
        // Allow admin users to delete any customer
        // You may also want to allow the customer’s owner to delete their own record
        return $user->is_admin === 1 || $user->id === $customer->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Customer $customer): bool
    {
        // Typically allow only admins to restore customers
        return $user->is_admin === 1;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        // Typically allow only admins to permanently delete customers
        return $user->is_admin === 1;
    }
}
