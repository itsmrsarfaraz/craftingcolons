<?php

namespace App\Providers;

use App\Models\ApplicantDocument;
use App\Models\Assessment;
use App\Models\JobPosting;
use App\Policies\ApplicantDocumentPolicy;
use App\Policies\AssessmentPolicy;
use App\Policies\JobPostingPolicy;
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
    }
}