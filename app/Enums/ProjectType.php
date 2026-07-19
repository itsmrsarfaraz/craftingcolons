<?php

namespace App\Enums;

enum ProjectType: string
{
    case Web = 'web';
    case Mobile = 'mobile';
    case Saas = 'saas';
    case Iot = 'iot';
    case Ai = 'ai';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Web => 'Web Application',
            self::Mobile => 'Mobile App',
            self::Saas => 'SaaS Platform',
            self::Iot => 'IoT System',
            self::Ai => 'AI / Automation',
            self::Other => 'Other',
        };
    }
}