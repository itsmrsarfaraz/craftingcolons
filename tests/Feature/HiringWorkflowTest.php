<?php

namespace Tests\Feature;

use App\Enums\JobApplicationStatus;
use App\Enums\JobPostingStatus;
use App\Models\Assessment;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HiringWorkflowTest extends TestCase
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

    public function test_passing_the_assessment_auto_shortlists_the_application(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Published,
            'assessment_required' => true,
        ]);

        $assessment = Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'Test',
            'duration_minutes' => 30,
            'passing_marks' => 50,
            'created_by' => $hr->id,
        ]);
        $q = $assessment->questions()->create(['type' => 'mcq', 'prompt' => 'Q', 'marks' => 10, 'order' => 1]);
        $correct = $q->options()->create(['label' => 'Right', 'is_correct' => true, 'order' => 0]);
        $q->options()->create(['label' => 'Wrong', 'is_correct' => false, 'order' => 1]);
        $assessment->recalculateTotalMarks();

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        $application = JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => 'applied',
        ]);

        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));
        $attempt = $application->fresh()->attempt;
        $this->actingAs($applicant)->post(route('applicant.assessments.answer', $attempt), [
            'question_id' => $q->id,
            'selected_option_ids' => [$correct->id],
        ]);
        $this->actingAs($applicant)->post(route('applicant.assessments.submit', $attempt));

        $this->assertEquals(JobApplicationStatus::Shortlisted, $application->fresh()->status);
    }

    public function test_failing_the_assessment_auto_rejects_the_application(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Published,
            'assessment_required' => true,
        ]);

        $assessment = Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'Test',
            'duration_minutes' => 30,
            'passing_marks' => 70,
            'created_by' => $hr->id,
        ]);
        $q = $assessment->questions()->create(['type' => 'mcq', 'prompt' => 'Q', 'marks' => 10, 'order' => 1]);
        $q->options()->create(['label' => 'Right', 'is_correct' => true, 'order' => 0]);
        $wrong = $q->options()->create(['label' => 'Wrong', 'is_correct' => false, 'order' => 1]);
        $assessment->recalculateTotalMarks();

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        $application = JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => 'applied',
        ]);

        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));
        $attempt = $application->fresh()->attempt;
        $this->actingAs($applicant)->post(route('applicant.assessments.answer', $attempt), [
            'question_id' => $q->id,
            'selected_option_ids' => [$wrong->id],
        ]);
        $this->actingAs($applicant)->post(route('applicant.assessments.submit', $attempt));

        $this->assertEquals(JobApplicationStatus::Rejected, $application->fresh()->status);
    }

    public function test_hr_can_manually_advance_a_shortlisted_application_through_the_pipeline(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        $application = JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => JobApplicationStatus::Shortlisted,
        ]);

        $response = $this->actingAs($hr)->patch(route('hr.applications.status', $application), [
            'status' => JobApplicationStatus::Interview->value,
        ]);

        $response->assertRedirect();
        $this->assertEquals(JobApplicationStatus::Interview, $application->fresh()->status);
    }

    public function test_hr_cannot_skip_directly_from_applied_to_hired(): void
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

        $response = $this->actingAs($hr)->patch(route('hr.applications.status', $application), [
            'status' => JobApplicationStatus::Hired->value,
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertEquals(JobApplicationStatus::Applied, $application->fresh()->status);
    }

    public function test_automatic_sync_never_overwrites_a_status_hr_already_set_manually(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Published,
            'assessment_required' => true,
        ]);

        $assessment = Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'Test',
            'duration_minutes' => 30,
            'passing_marks' => 50,
            'created_by' => $hr->id,
        ]);
        $q = $assessment->questions()->create(['type' => 'mcq', 'prompt' => 'Q', 'marks' => 10, 'order' => 1]);
        $correct = $q->options()->create(['label' => 'Right', 'is_correct' => true, 'order' => 0]);
        $q->options()->create(['label' => 'Wrong', 'is_correct' => false, 'order' => 1]);
        $assessment->recalculateTotalMarks();

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        $application = JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => 'applied',
        ]);

        // HR manually rejects before the candidate even finishes the assessment.
        $this->actingAs($hr)->patch(route('hr.applications.status', $application), [
            'status' => JobApplicationStatus::Rejected->value,
        ]);

        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));
        $attempt = $application->fresh()->attempt;
        $this->actingAs($applicant)->post(route('applicant.assessments.answer', $attempt), [
            'question_id' => $q->id,
            'selected_option_ids' => [$correct->id],
        ]);
        $this->actingAs($applicant)->post(route('applicant.assessments.submit', $attempt));

        // Still rejected — a passing score must not resurrect it automatically.
        $this->assertEquals(JobApplicationStatus::Rejected, $application->fresh()->status);
    }
}