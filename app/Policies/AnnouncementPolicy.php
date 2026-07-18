<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // every authenticated user can see the announcements feed
    }

    public function create(User $user): bool
    {
        return $user->can('publish-articles') || $user->can('manage-announcements');
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->published_by || $user->hasRole('admin');
    }
}