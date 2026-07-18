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

class GradingTest extends TestCase
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

    public function test_mcq_only_assessment_is_fully_auto_graded_and_finalized_on_submit(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Published,
            'assessment_required' => true,
        ]);

        $assessment = Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'MCQ Only',
            'duration_minutes' => 30,
            'passing_marks' => 7, // Adjusted raw passing mark target (70% of 10 total marks) 🎯
            'created_by' => $hr->id,
        ]);

        $q1 = $assessment->questions()->create(['type' => 'mcq', 'prompt' => 'Q1', 'marks' => 10, 'order' => 1]);
        $correct = $q1->options()->create(['label' => 'Right', 'is_correct' => true, 'order' => 0]);
        $q1->options()->create(['label' => 'Wrong', 'is_correct' => false, 'order' => 1]);
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
            'question_id' => $q1->id,
            'selected_option_ids' => [$correct->id],
        ]);

        $this->actingAs($applicant)->post(route('applicant.assessments.submit', $attempt));

        $attempt->refresh();
        
        $this->assertEquals(100, $attempt->score); 
        $this->assertTrue($attempt->passed);
        $this->assertNotNull($attempt->graded_at);
    }

    public function test_assessment_with_a_manual_question_stays_pending_until_hr_grades_it(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Published,
            'assessment_required' => true,
        ]);

        $assessment = Assessment::create([
            'job_posting_id' => $posting->id,
            'title' => 'Mixed',
            'duration_minutes' => 30,
            'passing_marks' => 14, // Adjusted raw passing mark target (70% of 20 total marks) 🎯
            'created_by' => $hr->id,
        ]);

        $mcq = $assessment->questions()->create(['type' => 'mcq', 'prompt' => 'Q1', 'marks' => 5, 'order' => 1]);
        $correctOption = $mcq->options()->create(['label' => 'Right', 'is_correct' => true, 'order' => 0]);
        $mcq->options()->create(['label' => 'Wrong', 'is_correct' => false, 'order' => 1]);

        $essay = $assessment->questions()->create(['type' => 'long_answer', 'prompt' => 'Explain SOLID', 'marks' => 15, 'order' => 2]);
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
            'question_id' => $mcq->id,
            'selected_option_ids' => [$correctOption->id],
        ]);
        $this->actingAs($applicant)->post(route('applicant.assessments.answer', $attempt), [
            'question_id' => $essay->id,
            'text_answer' => 'Single responsibility, open/closed, ...',
        ]);
        
        $this->actingAs($applicant)->post(route('applicant.assessments.submit', $attempt));

        $attempt->refresh();
        $this->assertNull($attempt->score, 'Score should stay null until HR grades the manual question.');
        $this->assertTrue($attempt->needsManualReview());

        $essayAnswer = $attempt->answers()->where('question_id', $essay->id)->first();

        $this->actingAs($hr)->post(route('hr.grading.store', $attempt), [
            'grades' => [
                ['answer_id' => $attempt->answers()->where('question_id', $mcq->id)->first()->id, 'marks_awarded' => 5],
                ['answer_id' => $essayAnswer->id, 'marks_awarded' => 12],
            ],
        ]);

        $attempt->refresh();
        $this->assertEquals(85, $attempt->score); // (5+12)/20 * 100
        $this->assertTrue($attempt->passed);
        $this->assertNotNull($attempt->graded_at);
    }

    public function test_hr_cannot_award_more_than_a_questions_max_marks(): void
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
        $essay = $assessment->questions()->create(['type' => 'long_answer', 'prompt' => 'Q', 'marks' => 10, 'order' => 1]);
        $assessment->recalculateTotalMarks();

        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');
        $application = JobApplication::create([
            'job_posting_id' => $posting->id, 'user_id' => $applicant->id, 'status' => 'applied',
        ]);
        $this->actingAs($applicant)->post(route('applicant.assessments.start', $application));
        $attempt = $application->fresh()->attempt;
        $this->actingAs($applicant)->post(route('applicant.assessments.submit', $attempt));

        $answer = $attempt->answers()->where('question_id', $essay->id)->first();

        $response = $this->actingAs($hr)->post(route('hr.grading.store', $attempt), [
            'grades' => [['answer_id' => $answer->id, 'marks_awarded' => 999]],
        ]);

        $response->assertSessionHasErrors();
    }
}