<?php

namespace App\Enums;

enum DocumentType: string
{
    case Cv = 'cv';
    case Portfolio = 'portfolio';
    case Certificate = 'certificate';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Cv => 'CV / Resume',
            self::Portfolio => 'Portfolio',
            self::Certificate => 'Certificate',
            self::Other => 'Other',
        };
    }
}