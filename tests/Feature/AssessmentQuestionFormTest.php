<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentQuestionFormTest extends TestCase
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

    private function assessmentFor(User $hr): \App\Models\Assessment
    {
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);
        $this->actingAs($hr)->post(route('hr.assessments.store', $posting), [
            'title' => 'Test', 'duration_minutes' => 30, 'passing_marks' => 70, 'max_violations_allowed' => 3,
        ]);

        return $posting->fresh()->assessment;
    }

    public function test_the_edit_page_renders_four_real_option_inputs_in_the_html(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->get(route('hr.assessments.edit', $assessment));

        $response->assertOk();
        for ($i = 0; $i < 4; $i++) {
            $response->assertSee("options[{$i}][label]", false);
            $response->assertSee("options[{$i}][is_correct]", false);
        }
    }

    public function test_the_edit_page_renders_a_language_input_for_coding_questions(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->get(route('hr.assessments.edit', $assessment));

        $response->assertSee('name="language"', false);
    }

    public function test_submitting_an_mcq_question_with_options_succeeds(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'mcq',
            'prompt' => 'What is 2+2?',
            'marks' => 5,
            'options' => [
                ['label' => '4', 'is_correct' => '1'],
                ['label' => '5', 'is_correct' => '0'],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('questions', ['prompt' => 'What is 2+2?']);
    }

    public function test_submitting_a_coding_question_with_a_language_succeeds(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'coding',
            'prompt' => 'Write a function to reverse a string.',
            'marks' => 10,
            'language' => 'python',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('questions', ['prompt' => 'Write a function to reverse a string.', 'language' => 'python']);
    }
}