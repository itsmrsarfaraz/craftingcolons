<?php

namespace Tests\Feature;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsEditCompletenessTest extends TestCase
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

    public function test_staff_can_edit_a_news_item(): void
    {
        $staff = $this->staff();
        $news = News::create([
            'author_id' => $staff->id,
            'title' => 'Original Title',
            'slug' => 'original-title',
            'body' => 'Original body.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $editResponse = $this->actingAs($staff)->get(route('staff.news.edit', $news));
        $editResponse->assertOk();

        $updateResponse = $this->actingAs($staff)->put(route('staff.news.update', $news), [
            'title' => 'Updated Title',
            'body' => 'Updated body.',
            'status' => 'published',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('news', ['id' => $news->id, 'title' => 'Updated Title']);
    }

    public function test_editing_a_published_news_item_does_not_reset_its_publish_timestamp(): void
    {
        $staff = $this->staff();
        $originalPublishedAt = now()->subDays(5)->startOfSecond();
        $news = News::create([
            'author_id' => $staff->id,
            'title' => 'Old News',
            'slug' => 'old-news',
            'body' => 'Body.',
            'status' => 'published',
            'published_at' => $originalPublishedAt,
        ]);

        $this->actingAs($staff)->put(route('staff.news.update', $news), [
            'title' => 'Old News (typo fixed)',
            'body' => 'Body.',
            'status' => 'published',
        ]);

        $this->assertTrue($news->fresh()->published_at->equalTo($originalPublishedAt));
    }

    public function test_staff_can_edit_an_event(): void
    {
        $staff = $this->staff();
        $event = Event::create([
            'organizer_id' => $staff->id,
            'title' => 'Original Event',
            'slug' => 'original-event',
            'description' => 'Original.',
            'starts_at' => now()->addDays(2),
            'ends_at' => now()->addDays(2)->addHours(2),
            'status' => 'published',
        ]);

        $editResponse = $this->actingAs($staff)->get(route('staff.events.edit', $event));
        $editResponse->assertOk();

        $updateResponse = $this->actingAs($staff)->put(route('staff.events.update', $event), [
            'title' => 'Updated Event',
            'description' => 'Updated.',
            'is_virtual' => '0',
            'starts_at' => $event->starts_at->format('Y-m-d H:i:s'),
            'ends_at' => $event->ends_at->format('Y-m-d H:i:s'),
            'status' => 'published',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Event']);
    }

    public function test_staff_can_rename_a_category(): void
    {
        $staff = $this->staff();
        $category = Category::create(['name' => 'Old Name', 'slug' => 'old-name', 'type' => CategoryType::Article]);

        $response = $this->actingAs($staff)->put(route('staff.categories.update', $category), [
            'name' => 'New Name',
            'type' => CategoryType::Article->value,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
    }

    public function test_editing_an_event_on_its_own_day_does_not_fail_the_after_now_rule(): void
    {
        $staff = $this->staff();
        $event = Event::create([
            'organizer_id' => $staff->id,
            'title' => 'Todays Event',
            'slug' => 'todays-event',
            'description' => 'Happening today.',
            'starts_at' => now()->addHour(),
            'ends_at' => now()->addHours(3),
            'status' => 'published',
        ]);

        // Simulate editing later the same day, after starts_at has technically become "now or past" relative to submission.
        $this->travel(2)->hours();

        $response = $this->actingAs($staff)->put(route('staff.events.update', $event), [
            'title' => 'Todays Event (edited)',
            'description' => 'Happening today.',
            'is_virtual' => '0',
            'starts_at' => $event->starts_at->format('Y-m-d H:i:s'),
            'ends_at' => $event->ends_at->format('Y-m-d H:i:s'),
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Todays Event (edited)']);
    }
}