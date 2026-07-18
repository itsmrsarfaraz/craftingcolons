<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Article $article): bool
    {
        return $article->isPublished() || ($user && $user->can('publish-articles'));
    }

    public function create(User $user): bool
    {
        return $user->can('publish-articles');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->id === $article->author_id || $user->hasRole('admin');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->id === $article->author_id || $user->hasRole('admin');
    }
}