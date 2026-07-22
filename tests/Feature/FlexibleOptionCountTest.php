<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlexibleOptionCountTest extends TestCase
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

    public function test_mcq_question_can_be_created_with_only_three_options(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'mcq',
            'prompt' => 'Pick one of three',
            'marks' => 5,
            'options' => [
                ['label' => 'A', 'is_correct' => '1'],
                ['label' => 'B', 'is_correct' => '0'],
                ['label' => 'C', 'is_correct' => '0'],
                ['label' => '', 'is_correct' => '0'],
                ['label' => '', 'is_correct' => '0'],
                ['label' => '', 'is_correct' => '0'],
            ],
        ]);

        $response->assertRedirect();
        $question = \App\Models\Question::where('prompt', 'Pick one of three')->first();
        $this->assertCount(3, $question->options);
    }

    public function test_mcq_question_can_be_created_with_five_options(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'mcq',
            'prompt' => 'Pick one of five',
            'marks' => 5,
            'options' => [
                ['label' => 'A', 'is_correct' => '0'],
                ['label' => 'B', 'is_correct' => '1'],
                ['label' => 'C', 'is_correct' => '0'],
                ['label' => 'D', 'is_correct' => '0'],
                ['label' => 'E', 'is_correct' => '0'],
                ['label' => '', 'is_correct' => '0'],
            ],
        ]);

        $response->assertRedirect();
        $question = \App\Models\Question::where('prompt', 'Pick one of five')->first();
        $this->assertCount(5, $question->options);
    }

    public function test_true_false_question_with_exactly_two_options_succeeds(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'true_false',
            'prompt' => 'Laravel is a PHP framework',
            'marks' => 2,
            'options' => [
                ['label' => 'True', 'is_correct' => '1'],
                ['label' => 'False', 'is_correct' => '0'],
            ],
        ]);

        $response->assertRedirect();
        $question = \App\Models\Question::where('prompt', 'Laravel is a PHP framework')->first();
        $this->assertCount(2, $question->options);
    }

    public function test_true_false_question_rejects_a_third_option(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'true_false',
            'prompt' => 'Invalid question',
            'marks' => 2,
            'options' => [
                ['label' => 'True', 'is_correct' => '1'],
                ['label' => 'False', 'is_correct' => '0'],
                ['label' => 'Maybe', 'is_correct' => '0'],
            ],
        ]);

        $response->assertSessionHasErrors('options');
    }

    public function test_mcq_with_only_one_filled_option_is_rejected(): void
    {
        $hr = $this->hr();
        $assessment = $this->assessmentFor($hr);

        $response = $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'mcq',
            'prompt' => 'Only one option',
            'marks' => 5,
            'options' => [
                ['label' => 'A', 'is_correct' => '1'],
                ['label' => '', 'is_correct' => '0'],
            ],
        ]);

        $response->assertSessionHasErrors('options');
    }
}