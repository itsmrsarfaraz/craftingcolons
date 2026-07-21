<?php

namespace Tests\Feature;

use App\Enums\JobApplicationStatus;
use App\Models\Assessment;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HrNavigationWiringTest extends TestCase
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

    public function test_jobs_index_links_to_its_applications_page(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => JobApplicationStatus::Applied,
        ]);

        $response = $this->actingAs($hr)->get(route('hr.jobs.index'));

        $response->assertSee(route('hr.applications.index', $posting), false);
        $response->assertSee('1 Applicant');
    }

    public function test_jobs_index_warns_when_assessment_required_but_missing(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id, 'assessment_required' => true]);

        $response = $this->actingAs($hr)->get(route('hr.jobs.index'));

        $response->assertSee(route('hr.assessments.create', $posting), false);
        $response->assertSee('Create Assessment');
    }

    public function test_jobs_index_links_to_ranking_when_assessment_exists(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id, 'assessment_required' => true]);
        Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'Test Assessment',
            'duration_minutes' => 30,
            'passing_marks' => 70,
            'created_by' => $hr->id,
        ]);

        $response = $this->actingAs($hr)->get(route('hr.jobs.index'));

        $response->assertSee(route('hr.grading.ranking', $posting), false);
        $response->assertSee('View Ranking');
    }

    public function test_applications_show_links_to_both_grading_and_attempt_review(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        $application = JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => JobApplicationStatus::Applied,
        ]);

        Assessment::create([
            'job_posting_id' => $posting->id, 'title' => 'T', 'duration_minutes' => 30,
            'passing_marks' => 70, 'created_by' => $hr->id,
        ]);

        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));
        $attempt = $application->fresh()->attempt;

        $response = $this->actingAs($hr)->get(route('hr.applications.show', $application));

        $response->assertSee(route('hr.grading.show', $attempt), false);
        $response->assertSee(route('hr.attempts.show', $attempt), false);
    }
}