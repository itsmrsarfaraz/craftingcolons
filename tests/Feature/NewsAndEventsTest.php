<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsAndEventsTest extends TestCase
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

    public function test_staff_can_publish_a_news_item(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->post(route('staff.news.store'), [
            'title' => 'We Hit 100 Projects',
            'body' => 'A huge milestone for the team.',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('news', ['title' => 'We Hit 100 Projects', 'status' => 'published']);
    }

    public function test_staff_can_create_an_event(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->post(route('staff.events.store'), [
            'title' => 'Tech Meetup',
            'description' => 'Monthly community meetup.',
            'is_virtual' => '0',
            'location' => 'Islamabad Office',
            'starts_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'ends_at' => now()->addDays(5)->addHours(2)->format('Y-m-d H:i:s'),
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('events', ['title' => 'Tech Meetup']);
    }

    public function test_a_user_can_register_for_an_upcoming_event(): void
    {
        $staff = $this->staff();
        $event = Event::create([
            'organizer_id' => $staff->id,
            'title' => 'Workshop',
            'slug' => 'workshop',
            'description' => 'A hands-on workshop.',
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addHours(3),
            'status' => 'published',
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.register', $event->slug));

        $response->assertRedirect();
        $this->assertDatabaseHas('event_registrations', ['event_id' => $event->id, 'user_id' => $user->id]);
    }

    public function test_a_user_cannot_register_twice_for_the_same_event(): void
    {
        $staff = $this->staff();
        $event = Event::create([
            'organizer_id' => $staff->id,
            'title' => 'Workshop',
            'slug' => 'workshop-2',
            'description' => 'A hands-on workshop.',
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addHours(3),
            'status' => 'published',
        ]);

        $user = User::factory()->create();
        $this->actingAs($user)->post(route('events.register', $event->slug));

        $response = $this->actingAs($user)->post(route('events.register', $event->slug));

        $response->assertSessionHasErrors('event');
        $this->assertDatabaseCount('event_registrations', 1);
    }

    public function test_registration_is_blocked_once_event_reaches_capacity(): void
    {
        $staff = $this->staff();
        $event = Event::create([
            'organizer_id' => $staff->id,
            'title' => 'Small Workshop',
            'slug' => 'small-workshop',
            'description' => 'Limited seats.',
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addHours(3),
            'status' => 'published',
            'max_attendees' => 1,
        ]);

        $firstUser = User::factory()->create();
        $this->actingAs($firstUser)->post(route('events.register', $event->slug));

        $secondUser = User::factory()->create();
        $response = $this->actingAs($secondUser)->post(route('events.register', $event->slug));

        $response->assertSessionHasErrors('event');
        $this->assertDatabaseCount('event_registrations', 1);
    }
}