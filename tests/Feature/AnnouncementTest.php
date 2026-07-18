<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AnnouncementTest extends TestCase
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

    private function employee(): User
    {
        $user = User::factory()->create();
        $user->assignRole('employee');
        Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'CC-0001',
            'employment_type' => 'full_time',
            'joined_at' => now(),
        ]);

        return $user->fresh();
    }

    public function test_staff_can_create_a_draft_announcement_without_notifying_anyone(): void
    {
        Notification::fake();
        $staff = $this->staff();

        $response = $this->actingAs($staff)->post(route('staff.announcements.store'), [
            'title' => 'New Office Hours',
            'body' => 'We are updating our office hours starting next week.',
            'audience' => 'employees',
            'publish_now' => '0',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('announcements', ['title' => 'New Office Hours', 'published_at' => null]);
        Notification::assertNothingSent();
    }

    public function test_publishing_an_announcement_notifies_the_target_audience(): void
    {
        Notification::fake();
        $staff = $this->staff();
        $employee = $this->employee();

        $this->actingAs($staff)->post(route('staff.announcements.store'), [
            'title' => 'Holiday Notice',
            'body' => 'Office closed Monday.',
            'audience' => 'employees',
            'publish_now' => '1',
        ]);

        Notification::assertSentTo($employee, NewAnnouncementNotification::class);
    }

    public function test_intern_only_announcement_does_not_notify_regular_employees(): void
    {
        Notification::fake();
        $staff = $this->staff();
        $employee = $this->employee();

        $intern = User::factory()->create();
        $intern->assignRole('intern');

        $this->actingAs($staff)->post(route('staff.announcements.store'), [
            'title' => 'Intern Orientation',
            'body' => 'Orientation is this Friday.',
            'audience' => 'interns',
            'publish_now' => '1',
        ]);

        Notification::assertSentTo($intern, NewAnnouncementNotification::class);
        Notification::assertNotSentTo($employee, NewAnnouncementNotification::class);
    }

    public function test_employee_can_see_published_announcements_targeted_at_them(): void
    {
        $staff = $this->staff();
        $employee = $this->employee();

        $this->actingAs($staff)->post(route('staff.announcements.store'), [
            'title' => 'Visible Announcement',
            'body' => 'This should show up.',
            'audience' => 'employees',
            'publish_now' => '1',
        ]);

        $response = $this->actingAs($employee)->get(route('announcements.feed'));

        $response->assertSee('Visible Announcement');
    }
}