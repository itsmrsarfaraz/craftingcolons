<?php

namespace App\Services\Auth;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Register a new user and assign the default "applicant" role.
     * Every public registration starts as an applicant — HR/Admin
     * later promotes them to employee/intern/etc. through the HR portal.
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => UserStatus::Active,
        ]);

        $user->assignRole('applicant');

        return $user;
    }

    /**
     * Attempt login. Returns true on success.
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        );
    }

    /**
     * Resolve where to send a user after login, based on their highest-priority role.
     * Order matters: an Admin who is also seeded as Employee should still land on /admin.
     */
    public function redirectPathFor(User $user): string
    {
        return match (true) {
            $user->hasRole('admin') => route('admin.dashboard'),
            $user->hasRole('hr') => route('hr.dashboard'),
            $user->hasRole('staff') => route('staff.dashboard'),
            $user->hasRole('team-lead') => route('team-lead.dashboard'),
            $user->hasRole('employee') => route('employee.dashboard'),
            $user->hasRole('intern') => route('intern.dashboard'),
            default => route('applicant.dashboard'),
        };
    }
}