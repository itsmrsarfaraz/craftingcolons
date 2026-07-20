<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppShellDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    /** @dataProvider dashboardRoutes */
    public function test_dashboard_renders_for_its_role(string $role, string $route): void
    {
        $user = $this->userWithRole($role);

        $response = $this->actingAs($user)->get(route($route));

        $response->assertOk();
    }

    public static function dashboardRoutes(): array
    {
        return [
            'applicant' => ['applicant', 'applicant.dashboard'],
            'intern' => ['intern', 'intern.dashboard'],
            'employee' => ['employee', 'employee.dashboard'],
            'team-lead' => ['team-lead', 'team-lead.dashboard'],
            'hr' => ['hr', 'hr.dashboard'],
            'staff' => ['staff', 'staff.dashboard'],
            'admin' => ['admin', 'admin.dashboard'],
        ];
    }

    public function test_sidebar_shows_hr_only_links_for_hr_user(): void
    {
        $hr = $this->userWithRole('hr');

        $response = $this->actingAs($hr)->get(route('hr.dashboard'));

        $response->assertSee('Job Postings');
        $response->assertDontSee('Activity Log');
    }

    public function test_sidebar_shows_admin_links_for_admin_user(): void
    {
        $admin = $this->userWithRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertSee('Activity Log');
        $response->assertSee('Settings');
    }
}