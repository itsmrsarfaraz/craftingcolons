<?php

namespace App\Enums;

enum EmploymentType: string
{
    case FullTime = 'full_time';
    case PartTime = 'part_time';
    case Internship = 'internship';
    case Contract = 'contract';
    case Remote = 'remote';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::FullTime => 'Full Time',
            self::PartTime => 'Part Time',
            self::Internship => 'Internship',
            self::Contract => 'Contract',
            self::Remote => 'Remote',
            self::Hybrid => 'Hybrid',
        };
    }
}