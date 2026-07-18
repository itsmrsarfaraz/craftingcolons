<?php

namespace Tests\Feature;

use App\Enums\AttemptStatus;
use App\Enums\JobPostingStatus;
use App\Models\Assessment;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Question;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttemptTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function applicantWithApplication(): JobApplication
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');

        $posting = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Published,
            'assessment_required' => true,
        ]);

        $assessment = Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'Test Assessment',
            'duration_minutes' => 30,
            'passing_marks' => 70,
            'created_by' => $hr->id,
        ]);

        $question = $assessment->questions()->create([
            'type' => 'mcq',
            'prompt' => '2 + 2?',
            'marks' => 10,
            'order' => 1,
        ]);
        $question->options()->createMany([
            ['label' => '4', 'is_correct' => true, 'order' => 0],
            ['label' => '5', 'is_correct' => false, 'order' => 1],
        ]);
        $assessment->recalculateTotalMarks();

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        return JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => 'applied',
        ]);
    }

    public function test_applicant_can_start_an_attempt_on_desktop(): void
    {
        $application = $this->applicantWithApplication();

        // Fixed route name here 🛑
        $response = $this->actingAs($application->applicant)
            ->post(route('applicant.assessments.start', $application));

        $response->assertRedirect();
        $this->assertDatabaseHas('attempts', [
            'job_application_id' => $application->id,
            'status' => AttemptStatus::InProgress->value,
        ]);
    }

    public function test_mobile_user_agent_is_blocked_from_starting_an_attempt(): void
    {
        $application = $this->applicantWithApplication();

        // Fixed route name here 🛑
        $response = $this->actingAs($application->applicant)
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)'])
            ->post(route('applicant.assessments.start', $application));

        $response->assertForbidden();
        $this->assertDatabaseMissing('attempts', ['job_application_id' => $application->id]);
    }

    public function test_applicant_cannot_start_a_second_attempt_after_submitting(): void
    {
        $application = $this->applicantWithApplication();
        $applicant = $application->applicant;

        // Fixed route names here 🛑
        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));
        $attempt = $application->fresh()->attempt;
        $this->actingAs($applicant)->post(route('applicant.assessments.submit', $attempt));

        $response = $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));

        $response->assertSessionHasErrors('attempt');
        $this->assertDatabaseCount('attempts', 1);
    }

    public function test_answer_save_is_rejected_after_time_expires(): void
    {
        $application = $this->applicantWithApplication();
        $applicant = $application->applicant;

        // Fixed route names here 🛑
        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));
        $attempt = $application->fresh()->attempt;

        // Force expiry
        $attempt->update(['expires_at' => now()->subMinute()]);
        $question = $attempt->assessment->questions()->first();

        $response = $this->actingAs($applicant)->post(route('applicant.assessments.answer', $attempt), [
            'question_id' => $question->id,
            'selected_option_ids' => [$question->options()->first()->id],
        ]);

        $response->assertSessionHasErrors('attempt');
        $this->assertEquals(AttemptStatus::AutoSubmitted, $attempt->fresh()->status);
    }
}