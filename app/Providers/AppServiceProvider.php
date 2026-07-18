<?php

namespace App\Providers;

use App\Events\AssessmentGraded;
use App\Listeners\SyncJobApplicationStatusFromAssessment;
use App\Models\ApplicantDocument;
use App\Models\Assessment;
use App\Models\Attempt;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Policies\ApplicantDocumentPolicy;
use App\Policies\AssessmentPolicy;
use App\Policies\AttemptPolicy;
use App\Policies\JobApplicationPolicy;
use App\Policies\JobPostingPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {
            return $user->hasPermission($ability) ? true : null;
        });
        Gate::policy(ApplicantDocument::class, ApplicantDocumentPolicy::class);
        Gate::policy(JobPosting::class, JobPostingPolicy::class);
        Gate::policy(Assessment::class, AssessmentPolicy::class);
        Gate::policy(Attempt::class, AttemptPolicy::class);
        Gate::policy(JobApplication::class, JobApplicationPolicy::class);

        Event::listen(AssessmentGraded::class, SyncJobApplicationStatusFromAssessment::class);
    }
}