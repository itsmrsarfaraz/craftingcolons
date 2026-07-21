<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $submissions = ContactSubmission::query()->latest()->paginate(20);

        return view('admin.contact-submissions.index', compact('submissions'));
    }

    public function markAsRead(Request $request, ContactSubmission $submission): RedirectResponse
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        $submission->update(['is_read' => true]);

        return back();
    }
}