<?php

namespace App\Policies;

use App\Models\Attempt;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttemptPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attempt $attempt): bool
    {
        return $user->id === $attempt->user_id || $user->hasAnyRole(['hr', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attempt $attempt): bool
    {
        return $user->id === $attempt->user_id;
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attempt $attempt): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attempt $attempt): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attempt $attempt): bool
    {
        return false;
    }


    public function grade(User $user, Attempt $attempt): bool
    {
        return $user->can('manage-assessments');
    }
}
