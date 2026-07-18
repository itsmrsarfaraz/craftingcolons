<?php

namespace Tests\Feature;

use App\Enums\AttemptStatus;
use App\Enums\JobPostingStatus;
use App\Models\Assessment;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AntiCheatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function startedAttempt(int $maxViolations = 3): \App\Models\Attempt
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');

        $posting = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Published,
            'assessment_required' => true,
        ]);

        // Capture the assessment variable here 👇
        $assessment = Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'Test Assessment',
            'duration_minutes' => 30,
            'passing_marks' => 70,
            'max_violations_allowed' => $maxViolations,
            'created_by' => $hr->id,
        ]);

        // Add a dummy question so tests calling answers won't crash 👇
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

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $application = JobApplication::create([
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
            'status' => 'applied',
        ]);

        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));

        return $application->fresh()->attempt;
    }

    public function test_a_violation_is_recorded_and_counted(): void
    {
        $attempt = $this->startedAttempt();

        // Fixed route name here 🛑
        $response = $this->actingAs($attempt->candidate)->postJson(
            route('applicant.assessments.violation', $attempt),
            ['type' => 'window_blur']
        );

        $response->assertOk();
        $response->assertJson(['violation_count' => 1, 'disqualified' => false]);
        $this->assertDatabaseHas('violations', ['attempt_id' => $attempt->id, 'type' => 'window_blur']);
    }

    public function test_crossing_the_threshold_disqualifies_the_attempt(): void
    {
        $attempt = $this->startedAttempt(maxViolations: 2);

        // Fixed route names here 🛑
        $this->actingAs($attempt->candidate)->postJson(route('applicant.assessments.violation', $attempt), ['type' => 'window_blur']);
        $response = $this->actingAs($attempt->candidate)->postJson(route('applicant.assessments.violation', $attempt), ['type' => 'window_blur']);

        $response->assertJson(['disqualified' => true]);
        $this->assertEquals(AttemptStatus::Disqualified, $attempt->fresh()->status);
    }

    public function test_devtools_detection_is_an_instant_fail_regardless_of_threshold(): void
    {
        $attempt = $this->startedAttempt(maxViolations: 10);

        // Fixed route name here 🛑
        $response = $this->actingAs($attempt->candidate)->postJson(
            route('applicant.assessments.violation', $attempt),
            ['type' => 'devtools_detected']
        );

        $response->assertJson(['disqualified' => true]);
        $this->assertEquals(AttemptStatus::Disqualified, $attempt->fresh()->status);
    }

    public function test_disqualified_attempt_rejects_further_answer_saves(): void
    {
        $attempt = $this->startedAttempt(maxViolations: 1);
        
        // Fixed route names here 🛑
        $this->actingAs($attempt->candidate)->postJson(route('applicant.assessments.violation', $attempt), ['type' => 'devtools_detected']);

        $question = $attempt->assessment->questions()->first();

        $response = $this->actingAs($attempt->candidate)->post(route('applicant.assessments.answer', $attempt), [
            'question_id' => $question->id,
            'text_answer' => 'trying to sneak in an answer',
        ]);

        $response->assertSessionHasErrors('attempt');
    }
}