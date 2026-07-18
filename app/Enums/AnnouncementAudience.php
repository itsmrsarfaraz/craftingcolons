<?php

namespace App\Enums;

enum AnnouncementAudience: string
{
    case All = 'all';
    case Employees = 'employees';
    case Interns = 'interns';
    case Hr = 'hr';

    public function label(): string
    {
        return match ($this) {
            self::All => 'Everyone',
            self::Employees => 'Employees',
            self::Interns => 'Interns',
            self::Hr => 'HR Team',
        };
    }

    /**
     * Role slugs that should receive this announcement.
     */
    public function targetRoleSlugs(): array
    {
        return match ($this) {
            self::All => ['employee', 'intern', 'team-lead', 'hr', 'staff', 'admin'],
            self::Employees => ['employee', 'team-lead'],
            self::Interns => ['intern'],
            self::Hr => ['hr', 'admin'],
        };
    }
}