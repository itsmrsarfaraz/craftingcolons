<?php

namespace Tests\Feature;

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use App\Models\JobPosting;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        Storage::fake('local');
    }

    private function hr(): User
    {
        $user = User::factory()->create();
        $user->assignRole('hr');

        return $user;
    }

    private function applicant(): User
    {
        $user = User::factory()->create();
        $user->assignRole('applicant');

        return $user;
    }

    public function test_hr_can_create_and_publish_a_job_posting(): void
    {
        $hr = $this->hr();

        $response = $this->actingAs($hr)->post(route('hr.jobs.store'), [
            'title' => 'Backend Engineer',
            'employment_type' => EmploymentType::FullTime->value,
            'description' => 'Build and maintain our Laravel platform.',
            'assessment_required' => '0',
        ]);

        $response->assertRedirect(route('hr.jobs.index'));
        $this->assertDatabaseHas('job_postings', ['title' => 'Backend Engineer']);
    }

    public function test_applicant_can_apply_with_an_uploaded_document(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create([
            'status' => JobPostingStatus::Published,
            'created_by' => $hr->id,
        ]);

        $applicant = $this->applicant();
        $this->actingAs($applicant)->post(route('applicant.documents.store'), [
            'type' => 'cv',
            'file' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);
        $document = $applicant->applicantDocuments()->first();

        $response = $this->actingAs($applicant)->post(
            route('applicant.applications.store', $posting->slug),
            ['applicant_document_id' => $document->id]
        );

        $response->assertRedirect(route('applicant.applications.index'));
        $this->assertDatabaseHas('job_applications', [
            'job_posting_id' => $posting->id,
            'user_id' => $applicant->id,
        ]);
    }

    public function test_applicant_cannot_apply_twice_to_the_same_posting(): void
    {
        $hr = $this->hr();
        $posting = JobPosting::factory()->create([
            'status' => JobPostingStatus::Published,
            'created_by' => $hr->id,
        ]);

        $applicant = $this->applicant();
        $this->actingAs($applicant)->post(route('applicant.documents.store'), [
            'type' => 'cv',
            'file' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);
        $document = $applicant->applicantDocuments()->first();

        $this->actingAs($applicant)->post(
            route('applicant.applications.store', $posting->slug),
            ['applicant_document_id' => $document->id]
        );

        $response = $this->actingAs($applicant)->post(
            route('applicant.applications.store', $posting->slug),
            ['applicant_document_id' => $document->id]
        );

        $response->assertSessionHasErrors('job_posting');
        $this->assertDatabaseCount('job_applications', 1);
    }
}