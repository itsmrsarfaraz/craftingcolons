<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'HR', 'slug' => 'hr'],
            ['name' => 'Staff', 'slug' => 'staff'],
            ['name' => 'Team Lead', 'slug' => 'team-lead'],
            ['name' => 'Employee', 'slug' => 'employee'],
            ['name' => 'Intern', 'slug' => 'intern'],
            ['name' => 'Applicant', 'slug' => 'applicant'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}