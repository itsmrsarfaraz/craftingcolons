<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage-jobs', 'manage-applications', 'manage-assessments',
            'review-candidates', 'publish-articles', 'manage-events',
            'manage-announcements', 'manage-employees', 'manage-tasks',
            'review-tasks', 'view-reports', 'manage-settings',
        ];

        foreach ($permissions as $slug) {
            Permission::firstOrCreate(['slug' => $slug], [
                'name' => ucwords(str_replace('-', ' ', $slug)),
                'slug' => $slug,
            ]);
        }

        // Admin gets everything
        $admin = Role::where('slug', 'admin')->first();
        $admin->permissions()->sync(Permission::all());

        // HR gets recruitment + employee management
        Role::where('slug', 'hr')->first()->permissions()->sync(
            Permission::whereIn('slug', [
                'manage-jobs', 'manage-applications', 'manage-assessments',
                'review-candidates', 'manage-employees', 'view-reports',
            ])->pluck('id')
        );

        // Staff gets content
        Role::where('slug', 'staff')->first()->permissions()->sync(
            Permission::whereIn('slug', [
                'publish-articles', 'manage-events', 'manage-announcements',
            ])->pluck('id')
        );

        // Team Lead gets task oversight
        Role::where('slug', 'team-lead')->first()->permissions()->sync(
            Permission::whereIn('slug', ['manage-tasks', 'review-tasks'])->pluck('id')
        );
    }
}