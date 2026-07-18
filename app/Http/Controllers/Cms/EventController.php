<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::published();

        $query = $request->query('when', 'upcoming') === 'past'
            ? $query->where('starts_at', '<', now())
            : $query->where('starts_at', '>=', now());

        $events = $query->orderBy('starts_at')->paginate(9);

        return view('cms.events.index', compact('events'));
    }

    public function show(Event $event): View
    {
        $this->authorize('view', $event);

        $event->loadCount('registrations');

        return view('cms.events.show', compact('event'));
    }
}