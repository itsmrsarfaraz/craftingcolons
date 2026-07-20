<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_admin_can_create_an_hr_user(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New HR Person',
            'email' => 'newhr@craftingcolons.com',
            'role' => 'hr',
        ]);

        $response->assertRedirect();
        $newUser = User::where('email', 'newhr@craftingcolons.com')->first();
        $this->assertNotNull($newUser);
        $this->assertTrue($newUser->hasRole('hr'));
    }

    public function test_admin_can_create_a_staff_user(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Staff Person',
            'email' => 'newstaff@craftingcolons.com',
            'role' => 'staff',
        ]);

        $response->assertRedirect();
        $this->assertTrue(User::where('email', 'newstaff@craftingcolons.com')->first()->hasRole('staff'));
    }

    public function test_non_admin_cannot_create_users(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');

        $response = $this->actingAs($hr)->post(route('admin.users.store'), [
            'name' => 'Sneaky', 'email' => 'sneaky@test.com', 'role' => 'admin',
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_change_an_existing_users_role(): void
    {
        $admin = $this->admin();
        $staffUser = User::factory()->create();
        $staffUser->assignRole('staff');

        $response = $this->actingAs($admin)->patch(route('admin.users.role', $staffUser), [
            'role' => 'hr',
        ]);

        $response->assertRedirect();
        $staffUser->refresh();
        $this->assertTrue($staffUser->hasRole('hr'));
        $this->assertFalse($staffUser->hasRole('staff'));
    }

    public function test_the_generated_password_actually_logs_the_user_in(): void
    {
        $admin = $this->admin();

        $service = app(\App\Services\Admin\UserManagementService::class);
        [$user, $password] = $service->create(['name' => 'Second User', 'email' => 'second@test.com', 'role' => 'staff']);

        // Log out of the admin session created by $this->admin() before attempting
        // a fresh login — the /login route sits behind 'guest' middleware, so an
        // already-authenticated request would be redirected away before the
        // login attempt ever runs, silently no-op'ing the assertion below.
        $this->post(route('logout'));

        $response = $this->post('/login', ['email' => $user->email, 'password' => $password]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }
}