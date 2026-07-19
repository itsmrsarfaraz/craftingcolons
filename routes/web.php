<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Applicant\DocumentController;
use App\Http\Controllers\Applicant\ProfileController;
use App\Http\Controllers\Assessments\AttemptController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Careers\JobApplicationController;
use App\Http\Controllers\Hr\JobApplicationController as HrJobApplicationController;
use App\Http\Controllers\Careers\JobController;
use App\Http\Controllers\Cms\ArticleController as PublicArticleController;
use App\Http\Controllers\Cms\NewsController as PublicNewsController;
use App\Http\Controllers\Cms\EventController as PublicEventController;
use App\Http\Controllers\Cms\ProjectController as PublicProjectController;
use App\Http\Controllers\Cms\EventRegistrationController;
use App\Http\Controllers\Cms\ServiceController as PublicServiceController;
use App\Http\Controllers\Staff\ArticleController as StaffArticleController;
use App\Http\Controllers\Staff\NewsController as StaffNewsController;
use App\Http\Controllers\Staff\EventController as StaffEventController;
use App\Http\Controllers\Staff\ProjectController as StaffProjectController;
use App\Http\Controllers\Staff\ServiceController as StaffServiceController;
use App\Http\Controllers\Employee\AnnouncementFeedController;
use App\Http\Controllers\Employee\AttendanceController;
use App\Http\Controllers\Employee\NotificationController;
use App\Http\Controllers\Employee\TaskController;
use App\Http\Controllers\Employee\TaskReportController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Hr\AssessmentController;
use App\Http\Controllers\Hr\AttemptReviewController;
use App\Http\Controllers\Hr\EmployeeOnboardingController;
use App\Http\Controllers\Hr\GradingController;
use App\Http\Controllers\Hr\JobPostingController;
use App\Http\Controllers\Hr\QuestionController;
use App\Http\Controllers\Search\GlobalSearchController;
use App\Http\Controllers\Seo\SitemapController;
use App\Http\Controllers\Staff\AnnouncementController;
use App\Http\Controllers\Staff\CategoryController;
use App\Http\Controllers\TeamLead\TaskReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [PublicServiceController::class, 'show'])->name('services.show');

Route::get('/search', [GlobalSearchController::class, 'index'])->name('search.index');
Route::get('/search/suggest', [GlobalSearchController::class, 'suggest'])->name('search.suggest');

Route::get('/articles', [PublicArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [PublicArticleController::class, 'show'])->name('articles.show');

Route::get('/news', [PublicNewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [PublicNewsController::class, 'show'])->name('news.show');

Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [PublicEventController::class, 'show'])->name('events.show');

Route::get('/portfolio', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/portfolio/{project:slug}', [PublicProjectController::class, 'show'])->name('projects.show');

Route::middleware('auth')->post('/events/{event:slug}/register', [EventRegistrationController::class, 'store'])
    ->name('events.register');

// Public Careeers Pages
Route::get('/careers', [JobController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobPosting:slug}', [JobController::class, 'show'])->name('careers.show');


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/applicant/dashboard', fn () => view('dashboards.applicant'))
        ->middleware('role:applicant')->name('applicant.dashboard');

    Route::get('/intern/dashboard', fn () => view('dashboards.intern'))
        ->middleware('role:intern')->name('intern.dashboard');

    Route::get('/employee/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])
        ->middleware('role:employee')->name('employee.dashboard');

    Route::get('/team-lead/dashboard', fn () => view('dashboards.team-lead'))
        ->middleware('role:team-lead')->name('team-lead.dashboard');

    Route::get('/hr/dashboard', fn () => view('dashboards.hr'))
        ->middleware('role:hr')->name('hr.dashboard');

    Route::get('/staff/dashboard', fn () => view('dashboards.staff'))
        ->middleware('role:staff')->name('staff.dashboard');

    Route::get('/admin/dashboard', fn () => view('dashboards.admin'))
        ->middleware('role:admin')->name('admin.dashboard');

    Route::get('/announcements', [AnnouncementFeedController::class, 'index'])->name('announcements.feed');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notificationId}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::middleware('role:applicant')->prefix('applicant')->name('applicant.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
        Route::post('/careers/{jobPosting:slug}/apply', [JobApplicationController::class, 'store'])
            ->name('applications.store');
        Route::get('/applications', function (\Illuminate\Http\Request $request) {
            $applications = $request->user()->jobApplications()->with('jobPosting')->latest()->paginate(10);
            return view('applicant.applications', compact('applications'));
        })->name('applications.index');


        Route::middleware(['desktop'])
            ->prefix('assessments')
            ->name('assessments.')
            ->group(function () {
                Route::post('/applications/{jobApplication}/start', [AttemptController::class, 'start'])
                    ->name('start');
                Route::get('/{attempt}', [AttemptController::class, 'show'])->name('show');
                Route::post('/{attempt}/answer', [AttemptController::class, 'saveAnswer'])->name('answer');
                Route::post('/{attempt}/submit', [AttemptController::class, 'submit'])->name('submit');

                Route::post('/{attempt}/violation', [AttemptController::class, 'reportViolation'])->name('violation');
            });
    });

    Route::middleware('role:hr,admin')->prefix('hr')->name('hr.')->group(function () {
        Route::get('/jobs', [JobPostingController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [JobPostingController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [JobPostingController::class, 'store'])->name('jobs.store');
        Route::patch('/jobs/{jobPosting}/publish', [JobPostingController::class, 'publish'])->name('jobs.publish');
        Route::patch('/jobs/{jobPosting}/close', [JobPostingController::class, 'close'])->name('jobs.close');

        Route::get('/jobs/{jobPosting}/assessment/create', [AssessmentController::class, 'create'])
            ->name('assessments.create');
        Route::post('/jobs/{jobPosting}/assessment', [AssessmentController::class, 'store'])
            ->name('assessments.store');
        Route::get('/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])
            ->name('assessments.edit');

        Route::post('/assessments/{assessment}/questions', [QuestionController::class, 'store'])
            ->name('questions.store');
        Route::put('/assessments/{assessment}/questions/{question}', [QuestionController::class, 'update'])
            ->name('questions.update');
        Route::delete('/assessments/{assessment}/questions/{question}', [QuestionController::class, 'destroy'])
            ->name('questions.destroy');

        Route::get('/attempts/{attempt}', [AttemptReviewController::class, 'show'])->name('attempts.show');

        Route::get('/jobs/{jobPosting}/ranking', [GradingController::class, 'ranking'])->name('grading.ranking');
        Route::get('/attempts/{attempt}/grade', [GradingController::class, 'show'])->name('grading.show');
        Route::post('/attempts/{attempt}/grade', [GradingController::class, 'store'])->name('grading.store');

        Route::get('/jobs/{jobPosting}/applications', [HrJobApplicationController::class, 'index'])
            ->name('applications.index');
        Route::get('/applications/{application}', [HrJobApplicationController::class, 'show'])
            ->name('applications.show');
        Route::patch('/applications/{application}/status', [HrJobApplicationController::class, 'updateStatus'])
            ->name('applications.status');

        Route::get('/applications/{application}/onboard', [EmployeeOnboardingController::class, 'create'])
            ->name('onboarding.create');
        Route::post('/applications/{application}/onboard', [EmployeeOnboardingController::class, 'store'])
            ->name('onboarding.store');
    });

    Route::middleware('role:employee')->prefix('employee')->name('employee.')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
        Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');

        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
        Route::post('/tasks/{task}/reports', [TaskReportController::class, 'store'])->name('tasks.reports.store');
    });

    Route::middleware('role:team-lead,hr,admin')->prefix('team-lead')->name('team-lead.')->group(function () {
        Route::get('/tasks', [TaskReviewController::class, 'index'])->name('tasks.review');
        Route::patch('/tasks/{task}/approve', [TaskReviewController::class, 'approve'])->name('tasks.approve');
        Route::patch('/tasks/{task}/request-changes', [TaskReviewController::class, 'requestChanges'])->name('tasks.request-changes');
    });

    Route::middleware('role:staff,admin')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::patch('/announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])->name('announcements.publish');

        Route::get('/articles', [StaffArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/create', [StaffArticleController::class, 'create'])->name('articles.create');
        Route::post('/articles', [StaffArticleController::class, 'store'])->name('articles.store');
        Route::get('/articles/{article}/edit', [StaffArticleController::class, 'edit'])->name('articles.edit');
        Route::put('/articles/{article}', [StaffArticleController::class, 'update'])->name('articles.update');

        Route::get('/news', [StaffNewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [StaffNewsController::class, 'create'])->name('news.create');
        Route::post('/news', [StaffNewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit', [StaffNewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [StaffNewsController::class, 'update'])->name('news.update');

        Route::get('/events', [StaffEventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [StaffEventController::class, 'create'])->name('events.create');
        Route::post('/events', [StaffEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [StaffEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [StaffEventController::class, 'update'])->name('events.update');

        Route::get('/projects', [StaffProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [StaffProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [StaffProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [StaffProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [StaffProjectController::class, 'update'])->name('projects.update');

        Route::get('/services', [StaffServiceController::class, 'index'])->name('services.index');
        Route::get('/services/create', [StaffServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [StaffServiceController::class, 'store'])->name('services.store');
        Route::get('/services/{service}/edit', [StaffServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [StaffServiceController::class, 'update'])->name('services.update');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});