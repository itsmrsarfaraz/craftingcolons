<?php

namespace App\Providers;

use App\Events\AnnouncementPublished;
use App\Events\AssessmentGraded;
use App\Listeners\NotifyAudienceOfAnnouncement;
use App\Listeners\SyncJobApplicationStatusFromAssessment;
use App\Models\Announcement;
use App\Models\ApplicantDocument;
use App\Models\Assessment;
use App\Models\Attempt;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Task;
use App\Policies\AnnouncementPolicy;
use App\Policies\ApplicantDocumentPolicy;
use App\Policies\AssessmentPolicy;
use App\Policies\AttemptPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\JobApplicationPolicy;
use App\Policies\JobPostingPolicy;
use App\Policies\TaskPolicy;
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
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(Announcement::class, AnnouncementPolicy::class);

        Event::listen(AssessmentGraded::class, SyncJobApplicationStatusFromAssessment::class);
        Event::listen(AnnouncementPublished::class, NotifyAudienceOfAnnouncement::class);

    }
}