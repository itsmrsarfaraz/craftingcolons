<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $logs = ActivityLog::query()
            ->with('user', 'subject')
            ->latest('created_at')
            ->paginate(30);

        return view('admin.activity-logs.index', compact('logs'));
    }
}