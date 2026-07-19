<?php

namespace App\Services\Cms;

use App\Models\Event;
use App\Models\User;

class EventService
{
    public function create(User $organizer, array $data, string $slug): Event
    {
        $event = Event::create([
            ...\Illuminate\Support\Arr::except($data, 'categories'),
            'organizer_id' => $organizer->id,
            'slug' => $slug,
        ]);

        $event->categories()->sync($data['categories'] ?? []);

        return $event;
    }
}