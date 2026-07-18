<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementFeedController extends Controller
{
    public function index(Request $request): View
    {
        $roleSlugs = $request->user()->roles->pluck('slug');

        $announcements = Announcement::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get()
            ->filter(fn (Announcement $a) => collect($a->audience->targetRoleSlugs())->intersect($roleSlugs)->isNotEmpty())
            ->sortByDesc('published_at')
            ->values();

        return view('employee.announcements.index', compact('announcements'));
    }
}