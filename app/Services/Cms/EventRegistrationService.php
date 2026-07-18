<?php

namespace App\Services\Cms;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class EventRegistrationService
{
    public function register(User $user, Event $event): EventRegistration
    {
        if ($event->status->value !== 'published') {
            throw ValidationException::withMessages([
                'event' => 'This event is not open for registration.',
            ]);
        }

        if ($event->registrations()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'event' => 'You are already registered for this event.',
            ]);
        }

        if ($event->isFull()) {
            throw ValidationException::withMessages([
                'event' => 'This event has reached capacity.',
            ]);
        }

        return $event->registrations()->create([
            'user_id' => $user->id,
        ]);
    }

    public function cancel(EventRegistration $registration): EventRegistration
    {
        $registration->update(['status' => 'cancelled']);

        return $registration->fresh();
    }

    public function markAttended(EventRegistration $registration): EventRegistration
    {
        $registration->update(['status' => 'attended']);

        return $registration->fresh();
    }
}