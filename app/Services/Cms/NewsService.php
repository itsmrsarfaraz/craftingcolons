<?php

namespace App\Services\Cms;

use App\Models\News;
use App\Models\User;

class NewsService
{
    public function create(User $author, array $data, string $slug): News
    {
        $news = News::create([
            'author_id' => $author->id,
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);

        $news->categories()->sync($data['categories'] ?? []);

        return $news;
    }
}