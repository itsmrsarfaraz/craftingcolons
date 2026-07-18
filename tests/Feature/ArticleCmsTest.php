<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleCmsTest extends TestCase
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

    public function test_staff_can_publish_an_article_with_categories_and_tags(): void
    {
        $staff = $this->staff();
        $category = Category::create(['name' => 'Engineering', 'slug' => 'engineering', 'type' => CategoryType::Article]);

        $response = $this->actingAs($staff)->post(route('staff.articles.store'), [
            'title' => 'Why We Chose Laravel',
            'body' => 'A deep dive into our stack decisions.',
            'status' => 'published',
            'categories' => [$category->id],
            'tags' => 'laravel, php, backend',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', ['title' => 'Why We Chose Laravel', 'status' => 'published']);

        $article = \App\Models\Article::first();
        $this->assertTrue($article->categories->contains($category));
        $this->assertCount(3, $article->tags);
    }

    public function test_a_draft_article_is_not_visible_on_the_public_index(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff)->post(route('staff.articles.store'), [
            'title' => 'Unfinished Draft',
            'body' => 'Not ready yet.',
            'status' => 'draft',
        ]);

        $response = $this->get(route('articles.index'));

        $response->assertDontSee('Unfinished Draft');
    }

    public function test_a_published_article_is_visible_to_guests(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff)->post(route('staff.articles.store'), [
            'title' => 'Published Piece',
            'body' => 'Ready for the world.',
            'status' => 'published',
        ]);

        $response = $this->get(route('articles.index'));

        $response->assertSee('Published Piece');
    }

    public function test_uploading_a_featured_image_attaches_media(): void
    {
        $staff = $this->staff();
        $file = UploadedFile::fake()->image('cover.jpg');

        $this->actingAs($staff)->post(route('staff.articles.store'), [
            'title' => 'With Cover Image',
            'body' => 'Body text.',
            'status' => 'published',
            'featured_image' => $file,
        ]);

        $article = \App\Models\Article::first();
        $this->assertNotNull($article->featuredImage());
        Storage::disk('public')->assertExists($article->featuredImage()->path);
    }

    public function test_non_staff_cannot_create_articles(): void
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $response = $this->actingAs($applicant)->post(route('staff.articles.store'), [
            'title' => 'Sneaky Post',
            'body' => 'Should not work.',
            'status' => 'published',
        ]);

        $response->assertForbidden();
    }
}