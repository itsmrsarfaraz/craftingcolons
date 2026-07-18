<?php

namespace App\Enums;

enum JobApplicationStatus: string
{
    case Applied = 'applied';
    case Shortlisted = 'shortlisted';
    case Interview = 'interview';
    case Offered = 'offered';
    case Hired = 'hired';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Applied => 'Applied',
            self::Shortlisted => 'Shortlisted',
            self::Interview => 'Interview',
            self::Offered => 'Offered',
            self::Hired => 'Hired',
            self::Rejected => 'Rejected',
        };
    }

    /**
     * Valid next statuses from this one. Rejected is reachable from any
     * non-final state; Hired and Rejected are terminal — nothing follows them.
     */
    public function allowedNextStatuses(): array
    {
        return match ($this) {
            self::Applied => [self::Shortlisted, self::Rejected],
            self::Shortlisted => [self::Interview, self::Rejected],
            self::Interview => [self::Offered, self::Rejected],
            self::Offered => [self::Hired, self::Rejected],
            self::Hired, self::Rejected => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedNextStatuses(), true);
    }
}