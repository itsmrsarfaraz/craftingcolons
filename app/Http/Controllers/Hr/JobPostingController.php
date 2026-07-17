<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreJobPostingRequest;
use App\Models\JobPosting;
use App\Services\Hr\JobPostingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JobPostingController extends Controller
{
    public function __construct(private readonly JobPostingService $jobPostingService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', JobPosting::class);

        $postings = JobPosting::latest()->paginate(15);

        return view('hr.jobs.index', compact('postings'));
    }

    public function create(): View
    {
        $this->authorize('create', JobPosting::class);

        return view('hr.jobs.create');
    }

    public function store(StoreJobPostingRequest $request): RedirectResponse
    {
        $posting = $this->jobPostingService->create(
            $request->user(),
            $request->validated(),
            $request->slug()
        );

        return redirect()->route('hr.jobs.index')->with('status', "Job posting \"{$posting->title}\" created.");
    }

    public function publish(JobPosting $jobPosting): RedirectResponse
    {
        $this->authorize('update', $jobPosting);

        $this->jobPostingService->publish($jobPosting);

        return back()->with('status', 'Job posting published.');
    }

    public function close(JobPosting $jobPosting): RedirectResponse
    {
        $this->authorize('update', $jobPosting);

        $this->jobPostingService->close($jobPosting);

        return back()->with('status', 'Job posting closed.');
    }
}