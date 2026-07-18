<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Announcement $announcement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Announcement: '.$this->announcement->title)
            ->greeting('Hi '.$notifiable->name.',')
            ->line($this->announcement->body)
            ->line('— Crafting Colons');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'excerpt' => str($this->announcement->body)->limit(120),
        ];
    }
}