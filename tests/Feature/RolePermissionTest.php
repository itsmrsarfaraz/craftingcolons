<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_assigned_a_role_and_inherit_its_permissions(): void
    {
        $role = Role::create(['name' => 'HR', 'slug' => 'hr']);
        $permission = Permission::create(['name' => 'Manage Jobs', 'slug' => 'manage-jobs']);
        $role->permissions()->attach($permission);

        $user = User::factory()->create();
        $user->assignRole('hr');
        $user->refresh();

        $this->assertTrue($user->hasRole('hr'));
        $this->assertTrue($user->hasPermission('manage-jobs'));
        $this->assertTrue($user->can('manage-jobs'));
        $this->assertFalse($user->hasPermission('manage-employees'));
    }
}