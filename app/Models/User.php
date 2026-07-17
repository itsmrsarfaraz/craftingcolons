<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRolesAndPermissions;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => \App\Enums\UserStatus::class,
        ];
    }

    public function applicantProfile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ApplicantProfile::class);
    }

    public function applicantDocuments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ApplicantDocument::class);
    }
}