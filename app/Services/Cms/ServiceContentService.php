<?php

namespace App\Services\Cms;

use App\Models\Service;
use App\Models\User;

class ServiceContentService
{
    public function create(User $author, array $data, string $slug): Service
    {
        return Service::create([
            'author_id' => $author->id,
            'title' => $data['title'],
            'slug' => $slug,
            'icon' => $data['icon'] ?? null,
            'short_description' => $data['short_description'],
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
            'order' => $data['order'] ?? 0,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update([
            'title' => $data['title'],
            'icon' => $data['icon'] ?? null,
            'short_description' => $data['short_description'],
            'body' => $data['body'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? ($service->published_at ?? now()) : null,
            'order' => $data['order'] ?? $service->order,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
        ]);

        return $service->fresh();
    }
}