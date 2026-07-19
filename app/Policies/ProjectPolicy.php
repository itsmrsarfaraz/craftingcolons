<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Project $project): bool
    {
        return $project->isPublished() || ($user && ($user->can('publish-articles') || $user->hasRole('admin')));
    }

    public function create(User $user): bool
    {
        return $user->can('publish-articles') || $user->hasRole('admin');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->author_id || $user->hasRole('admin');
    }
}