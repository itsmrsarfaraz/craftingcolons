<?php

namespace Tests\Feature;

use App\Enums\ArticleStatus;
use App\Enums\ProjectType;
use App\Models\Article;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditViewsRenderTest extends TestCase
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

    public function test_article_edit_page_renders(): void
    {
        $staff = $this->staff();
        $article = Article::create([
            'author_id' => $staff->id,
            'title' => 'Editable Article',
            'slug' => 'editable-article',
            'body' => 'Body.',
            'status' => ArticleStatus::Draft,
        ]);

        $response = $this->actingAs($staff)->get(route('staff.articles.edit', $article));

        $response->assertOk();
        $response->assertSee('Editable Article');
    }

    public function test_project_edit_page_renders(): void
    {
        $staff = $this->staff();
        $project = Project::create([
            'author_id' => $staff->id,
            'title' => 'Editable Project',
            'slug' => 'editable-project',
            'project_type' => ProjectType::Web,
            'summary' => 'Summary.',
            'body' => 'Body.',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($staff)->get(route('staff.projects.edit', $project));

        $response->assertOk();
        $response->assertSee('Editable Project');
    }

    public function test_scheduling_an_article_with_a_publish_date_succeeds(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->post(route('staff.articles.store'), [
            'title' => 'Scheduled Article',
            'body' => 'Content.',
            'status' => 'scheduled',
            'published_at' => now()->addDay()->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', ['title' => 'Scheduled Article', 'status' => 'scheduled']);
    }

    public function test_scheduling_an_article_without_a_publish_date_fails_validation(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->post(route('staff.articles.store'), [
            'title' => 'Missing Date Article',
            'body' => 'Content.',
            'status' => 'scheduled',
        ]);

        $response->assertSessionHasErrors('published_at');
    }
}