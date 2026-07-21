<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned: '.$this->task->title)
            ->greeting('Hi '.$notifiable->name.',')
            ->line('You have been assigned a new task by '.$this->task->assignedBy?->name.'.')
            ->line($this->task->title)
            ->when($this->task->due_date, fn ($mail) => $mail->line('Due: '.$this->task->due_date->format('M j, Y')))
            ->action('View Task', route('employee.tasks.index'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'excerpt' => 'Assigned by '.$this->task->assignedBy?->name,
        ];
    }
}