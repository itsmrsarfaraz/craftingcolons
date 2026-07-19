<?php

namespace Tests\Feature;

use App\Models\Technology;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectShowcaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        Storage::fake('public');
    }

    private function staff(): User
    {
        $user = User::factory()->create();
        $user->assignRole('staff');

        return $user;
    }

    public function test_staff_can_publish_a_project_with_technologies_and_results(): void
    {
        $staff = $this->staff();
        $tech = Technology::create(['name' => 'Laravel', 'slug' => 'laravel']);

        $response = $this->actingAs($staff)->post(route('staff.projects.store'), [
            'title' => 'SeeHostels Platform',
            'project_type' => 'saas',
            'summary' => 'A multi-tenant hostel management SaaS.',
            'body' => 'Full case study text here.',
            'status' => 'published',
            'technologies' => [$tech->id],
            'results' => [
                ['metric_label' => 'Bookings Processed', 'metric_value' => '5,000+'],
                ['metric_label' => 'Load Time', 'metric_value' => '1.2s'],
            ],
        ]);

        $response->assertRedirect();
        $project = \App\Models\Project::first();
        $this->assertTrue($project->technologies->contains($tech));
        $this->assertCount(2, $project->results);
    }

    public function test_draft_project_is_hidden_from_public_showcase(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff)->post(route('staff.projects.store'), [
            'title' => 'Unfinished Case Study',
            'project_type' => 'web',
            'summary' => 'Not ready.',
            'body' => 'Draft content.',
            'status' => 'draft',
        ]);

        $response = $this->get(route('projects.index'));

        $response->assertDontSee('Unfinished Case Study');
    }

    public function test_projects_can_be_filtered_by_type(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff)->post(route('staff.projects.store'), [
            'title' => 'Mobile App Project',
            'project_type' => 'mobile',
            'summary' => 'A mobile app.',
            'body' => 'Content.',
            'status' => 'published',
        ]);
        $this->actingAs($staff)->post(route('staff.projects.store'), [
            'title' => 'Web App Project',
            'project_type' => 'web',
            'summary' => 'A web app.',
            'body' => 'Content.',
            'status' => 'published',
        ]);

        $response = $this->get(route('projects.index', ['type' => 'mobile']));

        $response->assertSee('Mobile App Project');
        $response->assertDontSee('Web App Project');
    }

    public function test_uploading_a_featured_image_attaches_to_project(): void
    {
        $staff = $this->staff();
        $file = UploadedFile::fake()->image('project-cover.jpg');

        $this->actingAs($staff)->post(route('staff.projects.store'), [
            'title' => 'Visual Project',
            'project_type' => 'web',
            'summary' => 'Has a cover.',
            'body' => 'Content.',
            'status' => 'published',
            'featured_image' => $file,
        ]);

        $project = \App\Models\Project::first();
        $this->assertNotNull($project->featuredImage());
        Storage::disk('public')->assertExists($project->featuredImage()->path);
    }
}