<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentQuestionBankTest extends TestCase
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

    public function test_hr_can_create_an_assessment_for_a_job_posting(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);

        $response = $this->actingAs($hr)->post(route('hr.assessments.store', $posting), [
            'title' => 'Backend Assessment',
            'duration_minutes' => 45,
            'passing_marks' => 70,
            'max_violations_allowed' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assessments', [
            'job_posting_id' => $posting->id,
            'title' => 'Backend Assessment',
        ]);
    }

    public function test_hr_can_add_an_mcq_question_with_one_correct_option(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);
        $this->actingAs($hr)->post(route('hr.assessments.store', $posting), [
            'title' => 'Backend Assessment',
            'duration_minutes' => 45,
            'passing_marks' => 70,
            'max_violations_allowed' => 3,
        ]);
        $assessment = $posting->fresh()->assessment;

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'mcq',
            'prompt' => 'What does SOLID stand for?',
            'marks' => 5,
            'options' => [
                ['label' => 'A design principle acronym', 'is_correct' => '1'],
                ['label' => 'A database engine', 'is_correct' => '0'],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('questions', ['prompt' => 'What does SOLID stand for?']);
        $this->assertEquals(5, $assessment->fresh()->total_marks);
    }

    public function test_mcq_question_requires_at_least_one_correct_option(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);
        $this->actingAs($hr)->post(route('hr.assessments.store', $posting), [
            'title' => 'Backend Assessment',
            'duration_minutes' => 45,
            'passing_marks' => 70,
            'max_violations_allowed' => 3,
        ]);
        $assessment = $posting->fresh()->assessment;

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'mcq',
            'prompt' => 'Bad question with no correct answer',
            'marks' => 5,
            'options' => [
                ['label' => 'Option A', 'is_correct' => '0'],
                ['label' => 'Option B', 'is_correct' => '0'],
            ],
        ]);

        $response->assertSessionHasErrors('options');
        $this->assertDatabaseMissing('questions', ['prompt' => 'Bad question with no correct answer']);
    }
}