<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Review = 'review';
    case Completed = 'completed';
    case Blocked = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Review => 'In Review',
            self::Completed => 'Completed',
            self::Blocked => 'Blocked',
        };
    }

    /**
     * Employees move tasks between these statuses themselves.
     * "Completed" always routes through "Review" first — an employee
     * cannot self-certify a task done; a Team Lead has to sign off.
     */
    public function allowedNextStatuses(): array
    {
        return match ($this) {
            self::Pending => [self::InProgress, self::Blocked],
            self::InProgress => [self::Review, self::Blocked],
            self::Blocked => [self::InProgress],
            self::Review => [], // only a Team Lead can move out of Review
            self::Completed => [],
        };
    }
}