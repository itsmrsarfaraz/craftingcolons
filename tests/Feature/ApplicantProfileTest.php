<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicantProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        Storage::fake('local');
    }

    private function applicant(): User
    {
        $user = User::factory()->create();
        $user->assignRole('applicant');

        return $user;
    }

    public function test_applicant_can_update_their_profile(): void
    {
        $user = $this->applicant();

        $response = $this->actingAs($user)->put(route('applicant.profile.update'), [
            'headline' => 'Backend Developer',
            'city' => 'Islamabad',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('applicant_profiles', [
            'user_id' => $user->id,
            'headline' => 'Backend Developer',
        ]);
    }

    public function test_applicant_can_upload_a_cv(): void
    {
        $user = $this->applicant();
        $file = UploadedFile::fake()->create('resume.pdf', 200, 'application/pdf');

        $response = $this->actingAs($user)->post(route('applicant.documents.store'), [
            'type' => 'cv',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('applicant_documents', [
            'user_id' => $user->id,
            'type' => 'cv',
            'original_name' => 'resume.pdf',
        ]);
    }

    public function test_another_applicant_cannot_download_someone_elses_document(): void
    {
        $owner = $this->applicant();
        $intruder = $this->applicant();

        $file = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');
        $this->actingAs($owner)->post(route('applicant.documents.store'), [
            'type' => 'cv',
            'file' => $file,
        ]);

        $document = $owner->applicantDocuments()->first();

        $response = $this->actingAs($intruder)->get(route('applicant.documents.download', $document));

        $response->assertForbidden();
    }
}