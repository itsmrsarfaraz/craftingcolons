<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Service $service): bool
    {
        return $service->isPublished() || ($user && ($user->can('publish-articles') || $user->hasRole('admin')));
    }

    public function create(User $user): bool
    {
        return $user->can('publish-articles') || $user->hasRole('admin');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->id === $service->author_id || $user->hasRole('admin');
    }
}