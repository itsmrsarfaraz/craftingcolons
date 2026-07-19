<?php

namespace App\Providers;

use App\Events\AnnouncementPublished;
use App\Events\AssessmentGraded;
use App\Listeners\NotifyAudienceOfAnnouncement;
use App\Listeners\SyncJobApplicationStatusFromAssessment;
use App\Models\Announcement;
use App\Models\ApplicantDocument;
use App\Models\Article;
use App\Models\Assessment;
use App\Models\Attempt;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Event as EventModel;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\News;
use App\Models\Project;
use App\Models\Task;
use App\Observers\EmployeeObserver;
use App\Observers\JobApplicationObserver;
use App\Observers\JobPostingObserver;
use App\Policies\AnnouncementPolicy;
use App\Policies\ApplicantDocumentPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\AssessmentPolicy;
use App\Policies\AttemptPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\EventPolicy;
use App\Policies\JobApplicationPolicy;
use App\Policies\JobPostingPolicy;
use App\Policies\NewsPolicy;
use App\Policies\ProjectPolicy;
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
        Gate::policy(Article::class, ArticlePolicy::class);
        Gate::policy(News::class, NewsPolicy::class);
        Gate::policy(EventModel::class, EventPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);

        JobPosting::observe(JobPostingObserver::class);
        JobApplication::observe(JobApplicationObserver::class);
        Employee::observe(EmployeeObserver::class);

        Event::listen(AssessmentGraded::class, SyncJobApplicationStatusFromAssessment::class);
        Event::listen(AnnouncementPublished::class, NotifyAudienceOfAnnouncement::class);

    }
}