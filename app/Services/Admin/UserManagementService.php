<?php

namespace App\Services\Admin;

use App\Enums\UserStatus;
use App\Models\User;
use App\Notifications\NewAccountCredentialsNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementService
{
    public function create(array $data): User
    {
        $temporaryPassword = Str::password(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($temporaryPassword),
            'status' => UserStatus::Active,
        ]);

        $user->assignRole($data['role']);

        $user->notify(new NewAccountCredentialsNotification($temporaryPassword));

        return $user;
    }

    public function changeRole(User $user, string $roleSlug): User
    {
        $user->roles()->sync(\App\Models\Role::where('slug', $roleSlug)->firstOrFail());

        return $user->fresh();
    }
}