<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Applicant\UpdateProfileRequest;
use App\Services\Applicant\ApplicantProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly ApplicantProfileService $profileService)
    {
    }

    public function edit(Request $request): View
    {
        $user = $request->user()->load('applicantProfile', 'applicantDocuments');

        return view('applicant.profile', [
            'profile' => $user->applicantProfile,
            'documents' => $user->applicantDocuments,
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile($request->user(), $request->validated());

        return back()->with('status', 'Profile updated successfully.');
    }
}