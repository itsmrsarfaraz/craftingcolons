<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AppShellMigrationSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    public function test_hr_jobs_index_renders_under_app_shell(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');
        JobPosting::factory()->create(['created_by' => $hr->id]);

        $response = $this->actingAs($hr)->get(route('hr.jobs.index'));

        $response->assertOk();
        $response->assertSee('Crafting Colons'); // sidebar brand mark present = shell is active
    }

    public function test_hr_jobs_create_form_renders(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');

        $response = $this->actingAs($hr)->get(route('hr.jobs.create'));

        $response->assertOk();
    }

    public function test_staff_articles_index_renders_under_app_shell(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        $response = $this->actingAs($staff)->get(route('staff.articles.index'));

        $response->assertOk();
        $response->assertSee('Crafting Colons');
    }

    public function test_employee_attendance_page_renders_under_app_shell(): void
    {
        $user = User::factory()->create();
        $user->assignRole('employee');
        Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'CC-0001',
            'employment_type' => 'full_time',
            'joined_at' => now(),
        ]);

        $response = $this->actingAs($user->fresh())->get(route('employee.attendance.index'));

        $response->assertOk();
        $response->assertSee('Crafting Colons');
    }

    public function test_applicant_profile_page_renders_under_app_shell(): void
    {
        $user = User::factory()->create();
        $user->assignRole('applicant');

        $response = $this->actingAs($user)->get(route('applicant.profile.edit'));

        $response->assertOk();
        $response->assertSee('Crafting Colons');
    }

    public function test_admin_settings_page_renders_under_app_shell(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('Crafting Colons');
    }
}