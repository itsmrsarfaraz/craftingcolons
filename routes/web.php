<?php

use App\Http\Controllers\Applicant\DocumentController;
use App\Http\Controllers\Applicant\ProfileController;
use App\Http\Controllers\Assessments\AttemptController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Careers\JobApplicationController;
use App\Http\Controllers\Careers\JobController;
use App\Http\Controllers\Hr\AssessmentController;
use App\Http\Controllers\Hr\AttemptReviewController;
use App\Http\Controllers\Hr\JobPostingController;
use App\Http\Controllers\Hr\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

    Route::get('/employee/dashboard', fn () => view('dashboards.employee'))
        ->middleware('role:employee')->name('employee.dashboard');

    Route::get('/team-lead/dashboard', fn () => view('dashboards.team-lead'))
        ->middleware('role:team-lead')->name('team-lead.dashboard');

    Route::get('/hr/dashboard', fn () => view('dashboards.hr'))
        ->middleware('role:hr')->name('hr.dashboard');

    Route::get('/staff/dashboard', fn () => view('dashboards.staff'))
        ->middleware('role:staff')->name('staff.dashboard');

    Route::get('/admin/dashboard', fn () => view('dashboards.admin'))
        ->middleware('role:admin')->name('admin.dashboard');

    Route::middleware('role:applicant')->prefix('applicant')->name('applicant.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
        Route::post('/careers/{jobPosting:slug}/apply', [JobApplicationController::class, 'store'])
            ->name('applications.store');
        Route::get('/applications', function () {
            $applications = auth()->user()->jobApplications()->with('jobPosting')->latest()->paginate(10);
            
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
    });
});