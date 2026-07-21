<?php

namespace App\Http\Controllers\Public;

use App\Enums\ContactSubmissionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreContactSubmissionRequest;
use App\Http\Requests\Public\StoreNewsletterSubscriberRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function store(StoreContactSubmissionRequest $request): RedirectResponse
    {
        ContactSubmission::create([
            ...$request->validated(),
            'type' => ContactSubmissionType::Contact,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', 'Thanks for reaching out — we\'ll get back to you soon.');
    }

    public function subscribe(StoreNewsletterSubscriberRequest $request): RedirectResponse
    {
        // firstOrCreate keyed on (email, type) so resubmitting the same
        // newsletter form twice doesn't create duplicate signups.
        ContactSubmission::firstOrCreate(
            ['email' => $request->validated('email'), 'type' => ContactSubmissionType::Newsletter],
            ['ip_address' => $request->ip()]
        );

        return back()->with('status', 'You\'re subscribed!');
    }
}