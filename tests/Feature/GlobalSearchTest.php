<?php

namespace Tests\Feature;

use App\Enums\JobPostingStatus;
use App\Models\Article;
use App\Models\JobPosting;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    public function test_search_finds_a_published_article_by_title(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');
        Article::create([
            'author_id' => $staff->id,
            'title' => 'Scaling Laravel on Shared Hosting',
            'slug' => 'scaling-laravel',
            'body' => 'A deep dive.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('search.index', ['q' => 'Scaling Laravel']));

        $response->assertSee('Scaling Laravel on Shared Hosting');
    }

    public function test_search_does_not_return_a_draft_article(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');
        Article::create([
            'author_id' => $staff->id,
            'title' => 'Secret Draft Post',
            'slug' => 'secret-draft',
            'body' => 'Not published yet.',
            'status' => 'draft',
        ]);

        $response = $this->get(route('search.index', ['q' => 'Secret Draft']));

        $response->assertDontSee('Secret Draft Post');
    }

    public function test_search_does_not_return_an_unpublished_job_posting(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');
        JobPosting::factory()->create([
            'title' => 'Confidential New Role',
            'created_by' => $hr->id,
            'status' => JobPostingStatus::Draft,
        ]);

        $response = $this->get(route('search.index', ['q' => 'Confidential New Role']));

        $response->assertViewHas('results', function ($results) {
            return $results->isEmpty();
        });
    }

    public function test_search_finds_results_across_multiple_content_types(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');

        Article::create([
            'author_id' => $staff->id,
            'title' => 'Hostel Management Insights',
            'slug' => 'hostel-insights',
            'body' => 'Content.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Project::create([
            'author_id' => $staff->id,
            'title' => 'Hostel Management SaaS',
            'slug' => 'hostel-saas',
            'project_type' => 'saas',
            'summary' => 'A platform for hostels.',
            'body' => 'Full case study.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get(route('search.index', ['q' => 'Hostel']));

        $response->assertSee('Hostel Management Insights');
        $response->assertSee('Hostel Management SaaS');
    }

    public function test_suggest_endpoint_returns_json(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('staff');
        Article::create([
            'author_id' => $staff->id,
            'title' => 'JSON Suggest Test Article',
            'slug' => 'json-suggest-test',
            'body' => 'Content.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->getJson(route('search.suggest', ['q' => 'JSON Suggest']));

        $response->assertOk();
        $response->assertJsonPath('articles.0.title', 'JSON Suggest Test Article');
    }

    public function test_a_query_shorter_than_two_characters_returns_no_results(): void
    {
        $response = $this->getJson(route('search.suggest', ['q' => 'a']));

        $response->assertOk();
        $response->assertJson([]);
    }
}