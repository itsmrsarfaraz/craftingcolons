<?php

namespace Tests\Feature;

use App\Enums\JobApplicationStatus;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeOnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function hr(): User
    {
        $user = User::factory()->create();
        $user->assignRole('hr');

        return $user;
    }

    private function hiredApplication(): JobApplication
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        return JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => JobApplicationStatus::Hired,
        ]);
    }

    public function test_hr_can_onboard_a_hired_applicant_as_an_employee(): void
    {
        $hr = $this->hr();
        $application = $this->hiredApplication();

        $response = $this->actingAs($hr)->post(route('hr.onboarding.store', $application), [
            'employment_type' => 'full_time',
            'joined_at' => now()->toDateString(),
            'department' => 'Engineering',
            'designation' => 'Backend Engineer',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('employees', [
            'user_id' => $application->user_id,
            'department' => 'Engineering',
        ]);
        $this->assertTrue($application->applicant->fresh()->hasRole('employee'));
    }

    public function test_cannot_onboard_an_applicant_who_is_not_hired(): void
    {
        $hr = $this->hr();
        $application = $this->hiredApplication();
        $application->update(['status' => JobApplicationStatus::Shortlisted]);

        $response = $this->actingAs($hr)->post(route('hr.onboarding.store', $application), [
            'employment_type' => 'full_time',
            'joined_at' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('application');
        $this->assertDatabaseCount('employees', 0);
    }

    public function test_cannot_onboard_the_same_applicant_twice(): void
    {
        $hr = $this->hr();
        $application = $this->hiredApplication();

        $this->actingAs($hr)->post(route('hr.onboarding.store', $application), [
            'employment_type' => 'full_time',
            'joined_at' => now()->toDateString(),
        ]);

        $response = $this->actingAs($hr)->post(route('hr.onboarding.store', $application), [
            'employment_type' => 'full_time',
            'joined_at' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('application');
        $this->assertDatabaseCount('employees', 1);
    }

    public function test_employee_codes_increment_sequentially(): void
    {
        $hr = $this->hr();
        $app1 = $this->hiredApplication();
        $app2 = $this->hiredApplication();

        $this->actingAs($hr)->post(route('hr.onboarding.store', $app1), [
            'employment_type' => 'full_time', 'joined_at' => now()->toDateString(),
        ]);
        $this->actingAs($hr)->post(route('hr.onboarding.store', $app2), [
            'employment_type' => 'full_time', 'joined_at' => now()->toDateString(),
        ]);

        $this->assertDatabaseHas('employees', ['user_id' => $app1->user_id, 'employee_code' => 'CC-0001']);
        $this->assertDatabaseHas('employees', ['user_id' => $app2->user_id, 'employee_code' => 'CC-0002']);
    }
}