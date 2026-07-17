<?php

namespace App\Http\Controllers\Careers;

use App\Enums\JobPostingStatus;
use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $jobs = JobPosting::query()
            ->where('status', JobPostingStatus::Published)
            ->where(fn ($q) => $q->whereNull('deadline')->orWhere('deadline', '>=', now()))
            ->latest()
            ->paginate(12);

        return view('careers.index', compact('jobs'));
    }

    public function show(JobPosting $jobPosting): View
    {
        $this->authorize('view', $jobPosting);

        return view('careers.show', compact('jobPosting'));
    }
}