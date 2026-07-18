<?php

namespace App\Enums;

enum AttemptStatus: string
{
    case InProgress = 'in_progress';
    case Submitted = 'submitted';
    case AutoSubmitted = 'auto_submitted';
    case Disqualified = 'disqualified';

    public function label(): string
    {
        return match ($this) {
            self::InProgress => 'In Progress',
            self::Submitted => 'Submitted',
            self::AutoSubmitted => 'Auto-Submitted (Time Expired)',
            self::Disqualified => 'Disqualified',
        };
    }

    public function isFinal(): bool
    {
        return $this !== self::InProgress;
    }
}