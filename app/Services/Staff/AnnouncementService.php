<?php

namespace App\Services\Staff;

use App\Events\AnnouncementPublished;
use App\Models\Announcement;
use App\Models\User;

class AnnouncementService
{
    public function create(User $publisher, array $data): Announcement
    {
        $announcement = Announcement::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'audience' => $data['audience'],
            'published_by' => $publisher->id,
            'published_at' => ($data['publish_now'] ?? false) ? now() : null,
        ]);

        if ($announcement->isPublished()) {
            AnnouncementPublished::dispatch($announcement);
        }

        return $announcement;
    }

    public function publish(Announcement $announcement): Announcement
    {
        if ($announcement->isPublished()) {
            return $announcement;
        }

        $announcement->update(['published_at' => now()]);

        AnnouncementPublished::dispatch($announcement);

        return $announcement->fresh();
    }
}