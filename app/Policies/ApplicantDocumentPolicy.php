<?php

namespace App\Policies;

use App\Models\ApplicantDocument;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicantDocumentPolicy
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
    public function view(User $user, ApplicantDocument $document): bool
    {
        return $user->id === $document->user_id
            || $user->hasAnyRole(['hr', 'admin']);
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
    public function update(User $user, ApplicantDocument $applicantDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ApplicantDocument $document): bool
    {
        return $user->id === $document->user_id
            || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ApplicantDocument $applicantDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ApplicantDocument $applicantDocument): bool
    {
        return false;
    }
}
