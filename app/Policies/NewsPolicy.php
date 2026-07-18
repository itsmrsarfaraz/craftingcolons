<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, News $news): bool
    {
        return $news->isPublished() || ($user && ($user->can('manage-announcements') || $user->can('publish-articles')));
    }

    public function create(User $user): bool
    {
        return $user->can('manage-announcements') || $user->can('publish-articles');
    }

    public function update(User $user, News $news): bool
    {
        return $user->id === $news->author_id || $user->hasRole('admin');
    }
}