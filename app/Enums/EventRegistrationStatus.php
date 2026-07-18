<?php

namespace App\Enums;

enum EventRegistrationStatus: string
{
    case Registered = 'registered';
    case Attended = 'attended';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::Registered => 'Registered',
            self::Attended => 'Attended',
            self::Cancelled => 'Cancelled',
            self::NoShow => 'No Show',
        };
    }
}