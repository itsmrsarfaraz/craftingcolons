<?php

namespace Tests\Feature;

use App\Enums\JobPostingStatus;
use App\Models\JobPosting;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPageDesignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    public function test_careers_index_renders(): void
    {
        $response = $this->get(route('careers.index'));

        $response->assertOk();
        $response->assertSee('Open Positions');
    }

    public function test_careers_show_renders_for_a_published_job(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');
        $job = JobPosting::factory()->create(['created_by' => $hr->id, 'status' => JobPostingStatus::Published]);

        $response = $this->get(route('careers.show', $job->slug));

        $response->assertOk();
        $response->assertSee($job->title);
    }

    public function test_projects_index_renders(): void
    {
        $response = $this->get(route('projects.index'));

        $response->assertOk();
        $response->assertSee('Our Work');
    }

    public function test_projects_show_renders_for_a_published_project(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');
        $project = Project::create([
            'author_id' => $staff->id,
            'title' => 'Rendered Project',
            'slug' => 'rendered-project',
            'project_type' => 'web',
            'summary' => 'A summary.',
            'body' => 'Full body.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('projects.show', $project->slug));

        $response->assertOk();
        $response->assertSee('Rendered Project');
    }
}