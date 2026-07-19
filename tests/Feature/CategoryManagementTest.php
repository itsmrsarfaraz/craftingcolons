<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
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

    public function test_staff_can_create_a_category(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->post(route('staff.categories.store'), [
            'name' => 'Engineering',
            'type' => CategoryType::Article->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Engineering', 'type' => 'article']);
    }

    public function test_news_create_page_loads_without_undefined_variable_error(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->get(route('staff.news.create'));

        $response->assertOk();
    }

    public function test_events_create_page_loads_without_undefined_variable_error(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->get(route('staff.events.create'));

        $response->assertOk();
    }

    public function test_projects_create_page_loads_without_undefined_variable_error(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->get(route('staff.projects.create'));

        $response->assertOk();
    }

    public function test_staff_can_publish_news_with_a_category_attached(): void
    {
        $staff = $this->staff();
        $category = \App\Models\Category::create(['name' => 'Company Updates', 'slug' => 'company-updates', 'type' => CategoryType::News]);

        $response = $this->actingAs($staff)->post(route('staff.news.store'), [
            'title' => 'Big Announcement',
            'body' => 'Details here.',
            'status' => 'published',
            'categories' => [$category->id],
        ]);

        $response->assertRedirect();
        $news = \App\Models\News::first();
        $this->assertTrue($news->categories->contains($category));
    }
}