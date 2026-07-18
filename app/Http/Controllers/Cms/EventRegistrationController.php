<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\Cms\EventRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function __construct(private readonly EventRegistrationService $registrationService)
    {
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->registrationService->register($request->user(), $event);

        return back()->with('status', "You're registered for \"{$event->title}\".");
    }
}