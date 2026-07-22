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

class HrApplicationsOverviewTest extends TestCase
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

    public function test_hr_can_view_all_applications_across_every_job(): void
    {
        $hr = $this->hr();
        $postingA = JobPosting::factory()->create(['created_by' => $hr->id, 'title' => 'Backend Role']);
        $postingB = JobPosting::factory()->create(['created_by' => $hr->id, 'title' => 'Design Role']);

        $applicantA = User::factory()->create();
        $applicantA->assignRole('applicant');
        JobApplication::create(['job_posting_id' => $postingA->id, 'user_id' => $applicantA->id, 'status' => JobApplicationStatus::Applied]);

        $applicantB = User::factory()->create();
        $applicantB->assignRole('applicant');
        JobApplication::create(['job_posting_id' => $postingB->id, 'user_id' => $applicantB->id, 'status' => JobApplicationStatus::Shortlisted]);

        $response = $this->actingAs($hr)->get(route('hr.applications.all'));

        $response->assertOk();
        $response->assertSee('Backend Role');
        $response->assertSee('Design Role');
    }

    public function test_onboarding_create_page_renders_for_a_hired_applicant(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        $application = JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => JobApplicationStatus::Hired,
        ]);

        $response = $this->actingAs($hr)->get(route('hr.onboarding.create', $application));

        $response->assertOk();
        $response->assertSee('Onboard '.$applicant->name);
    }

    public function test_hr_sidebar_shows_all_applications_link(): void
    {
        $hr = $this->hr();

        $response = $this->actingAs($hr)->get(route('hr.dashboard'));

        $response->assertSee('All Applications');
    }
}