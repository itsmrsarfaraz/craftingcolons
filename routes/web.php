<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
});