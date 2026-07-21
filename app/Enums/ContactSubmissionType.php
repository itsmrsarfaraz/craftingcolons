<?php

namespace App\Enums;

enum ContactSubmissionType: string
{
    case Contact = 'contact';
    case Newsletter = 'newsletter';

    public function label(): string
    {
        return match ($this) {
            self::Contact => 'Contact Form',
            self::Newsletter => 'Newsletter Signup',
        };
    }
}