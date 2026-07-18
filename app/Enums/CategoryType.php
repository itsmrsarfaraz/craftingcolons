<?php

namespace App\Enums;

enum CategoryType: string
{
    case Article = 'article';
    case News = 'news';
    case Event = 'event';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}