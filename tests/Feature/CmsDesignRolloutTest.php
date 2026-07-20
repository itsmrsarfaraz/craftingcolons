<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Event;
use App\Models\News;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsDesignRolloutTest extends TestCase
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

    public function test_articles_index_and_show_render(): void
    {
        $staff = $this->staff();
        $article = Article::create([
            'author_id' => $staff->id,
            'title' => 'Rendered Article',
            'slug' => 'rendered-article',
            'body' => 'Body.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('articles.index'))->assertOk()->assertSee('Rendered Article');
        $this->get(route('articles.show', $article->slug))->assertOk()->assertSee('Rendered Article');
    }

    public function test_news_index_and_show_render(): void
    {
        $staff = $this->staff();
        $news = News::create([
            'author_id' => $staff->id,
            'title' => 'Rendered News',
            'slug' => 'rendered-news',
            'body' => 'Body.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('news.index'))->assertOk()->assertSee('Rendered News');
        $this->get(route('news.show', $news->slug))->assertOk()->assertSee('Rendered News');
    }

    public function test_events_index_and_show_render(): void
    {
        $staff = $this->staff();
        $event = Event::create([
            'organizer_id' => $staff->id,
            'title' => 'Rendered Event',
            'slug' => 'rendered-event',
            'description' => 'Description.',
            'starts_at' => now()->addDays(2),
            'ends_at' => now()->addDays(2)->addHours(2),
            'status' => 'published',
        ]);

        $this->get(route('events.index'))->assertOk()->assertSee('Rendered Event');
        $this->get(route('events.show', $event->slug))->assertOk()->assertSee('Rendered Event');
    }
}