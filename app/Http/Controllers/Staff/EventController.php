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

        return view('staff.events.create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $this->eventService->create($request->user(), $request->validated(), $request->slug());

        return redirect()->route('staff.events.index')->with('status', 'Event created.');
    }
}