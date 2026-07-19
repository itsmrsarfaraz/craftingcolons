<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\Stat;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function test_home_page_shows_published_stats(): void
    {
        Stat::create(['label' => 'Test Stat', 'value' => '42', 'order' => 1]);

        $response = $this->get(route('home'));

        $response->assertSee('Test Stat');
        $response->assertSee('42');
    }

    public function test_home_page_shows_published_job_postings(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');
        JobPosting::factory()->create([
            'title' => 'Homepage Visible Job',
            'created_by' => $hr->id,
            'status' => \App\Enums\JobPostingStatus::Published,
        ]);

        $response = $this->get(route('home'));

        $response->assertSee('Homepage Visible Job');
    }

    public function test_sitemap_returns_xml(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<?xml', false);
    }

    public function test_sitemap_includes_a_published_job_but_not_a_draft(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');
        $published = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => \App\Enums\JobPostingStatus::Published,
        ]);
        $draft = JobPosting::factory()->create([
            'created_by' => $hr->id,
            'status' => \App\Enums\JobPostingStatus::Draft,
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertSee(route('careers.show', $published->slug), false);
        $response->assertDontSee(route('careers.show', $draft->slug), false);
    }

    public function test_home_page_shows_technologies_when_present(): void
    {
        \App\Models\Technology::create(['name' => 'Laravel', 'slug' => 'laravel']);

        $response = $this->get(route('home'));

        $response->assertSee('Laravel');
    }
}