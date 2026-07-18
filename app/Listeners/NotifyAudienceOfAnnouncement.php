<?php

namespace App\Listeners;

use App\Events\AnnouncementPublished;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NotifyAudienceOfAnnouncement implements ShouldQueue
{
    public function handle(AnnouncementPublished $event): void
    {
        $roleSlugs = $event->announcement->audience->targetRoleSlugs();

        User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('slug', $roleSlugs))
            ->chunkById(200, function ($users) use ($event) {
                Notification::send($users, new NewAnnouncementNotification($event->announcement));
            });
    }
}