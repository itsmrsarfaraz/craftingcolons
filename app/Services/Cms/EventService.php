<?php

namespace App\Services\Cms;

use App\Models\Event;
use App\Models\User;

class EventService
{
    public function create(User $organizer, array $data, string $slug): Event
    {
        return Event::create([
            ...$data,
            'organizer_id' => $organizer->id,
            'slug' => $slug,
        ]);
    }
}