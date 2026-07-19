<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreEventRequest;
use App\Models\Event;
use App\Services\Cms\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private readonly EventService $eventService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Event::class);

        $events = Event::query()->withCount('registrations')->latest('starts_at')->paginate(15);

        return view('staff.events.index', compact('events'));
    }

    public function create(): View
    {
        $this->authorize('create', Event::class);

        $categories = \App\Models\Category::where('type', \App\Enums\CategoryType::Event)->get();

        return view('staff.events.create', compact('categories'));
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $this->eventService->create($request->user(), $request->validated(), $request->slug());

        return redirect()->route('staff.events.index')->with('status', 'Event created.');
    }

    public function edit(Event $event): View
    {
        $this->authorize('update', $event);

        $categories = \App\Models\Category::where('type', \App\Enums\CategoryType::Event)->get();
        $event->load('categories');

        return view('staff.events.edit', compact('event', 'categories'));
    }

    public function update(StoreEventRequest $request, Event $event): RedirectResponse
    {
        $this->authorize('update', $event);

        $this->eventService->update($event, $request->validated());

        return redirect()->route('staff.events.index')->with('status', 'Event updated.');
    }
}