<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditCompletenessAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    private function staff(): User
    {
        $user = User::factory()->create();
        $user->assignRole('staff');

        return $user;
    }

    private function hr(): User
    {
        $user = User::factory()->create();
        $user->assignRole('hr');

        return $user;
    }

    public function test_service_edit_page_renders_and_updates(): void
    {
        $staff = $this->staff();
        $service = Service::create([
            'author_id' => $staff->id,
            'title' => 'Old Service Title',
            'slug' => 'old-service-title',
            'short_description' => 'Old.',
            'body' => 'Old body.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->actingAs($staff)->get(route('staff.services.edit', $service))->assertOk();

        $response = $this->actingAs($staff)->put(route('staff.services.update', $service), [
            'title' => 'New Service Title',
            'short_description' => 'New.',
            'body' => 'New body.',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('services', ['id' => $service->id, 'title' => 'New Service Title']);
    }

    public function test_hr_can_edit_an_existing_job_posting(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id, 'title' => 'Old Job Title']);

        $this->actingAs($hr)->get(route('hr.jobs.edit', $posting))->assertOk();

        $response = $this->actingAs($hr)->put(route('hr.jobs.update', $posting), [
            'title' => 'New Job Title',
            'employment_type' => 'full_time',
            'description' => 'Updated description.',
            'assessment_required' => '0',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_postings', ['id' => $posting->id, 'title' => 'New Job Title']);
    }

    public function test_hr_can_edit_an_existing_mcq_question(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create(['created_by' => $hr->id]);
        $this->actingAs($hr)->post(route('hr.assessments.store', $posting), [
            'title' => 'Test', 'duration_minutes' => 30, 'passing_marks' => 70, 'max_violations_allowed' => 3,
        ]);
        $assessment = $posting->fresh()->assessment;

        $this->actingAs($hr)->post(route('hr.questions.store', $assessment), [
            'type' => 'mcq', 'prompt' => 'Old prompt', 'marks' => 5,
            'options' => [
                ['label' => 'A', 'is_correct' => '1'],
                ['label' => 'B', 'is_correct' => '0'],
            ],
        ]);
        $question = $assessment->fresh()->questions->first();

        $response = $this->actingAs($hr)->put(route('hr.questions.update', [$assessment, $question]), [
            'type' => 'mcq', 'prompt' => 'Updated prompt', 'marks' => 8,
            'options' => [
                ['label' => 'A updated', 'is_correct' => '1'],
                ['label' => 'B updated', 'is_correct' => '0'],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('questions', ['id' => $question->id, 'prompt' => 'Updated prompt', 'marks' => 8]);
        $this->assertEquals(8, $assessment->fresh()->total_marks);
    }
}