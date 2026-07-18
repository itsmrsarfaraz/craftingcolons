<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreAnnouncementRequest;
use App\Models\Announcement;
use App\Services\Staff\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(private readonly AnnouncementService $announcementService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Announcement::class);

        $announcements = Announcement::query()->latest()->paginate(15);

        return view('staff.announcements.index', compact('announcements'));
    }

    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $this->announcementService->create($request->user(), $request->validated());

        return back()->with('status', 'Announcement saved.');
    }

    public function publish(Announcement $announcement): RedirectResponse
    {
        $this->authorize('update', $announcement);

        $this->announcementService->publish($announcement);

        return back()->with('status', 'Announcement published and notifications queued.');
    }
}