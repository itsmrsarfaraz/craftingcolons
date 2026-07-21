<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactAndSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    public function test_about_page_renders(): void
    {
        $this->get(route('about'))->assertOk()->assertSee('Our Mission');
    }

    public function test_contact_page_renders(): void
    {
        $this->get(route('contact.show'))->assertOk();
    }

    public function test_a_visitor_can_submit_the_contact_form(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Interested in working together.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_submissions', ['email' => 'jane@example.com', 'type' => 'contact']);
    }

    public function test_a_visitor_can_subscribe_to_the_newsletter(): void
    {
        $response = $this->post(route('newsletter.subscribe'), ['email' => 'subscriber@example.com']);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_submissions', ['email' => 'subscriber@example.com', 'type' => 'newsletter']);
    }

    public function test_subscribing_twice_with_the_same_email_does_not_duplicate(): void
    {
        $this->post(route('newsletter.subscribe'), ['email' => 'dupe@example.com']);
        $this->post(route('newsletter.subscribe'), ['email' => 'dupe@example.com']);

        $this->assertDatabaseCount('contact_submissions', 1);
    }

    public function test_admin_can_view_contact_submissions(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->post(route('contact.store'), ['name' => 'Test', 'email' => 'x@x.com', 'message' => 'Hi']);

        $response = $this->actingAs($admin)->get(route('admin.contact-submissions.index'));

        $response->assertOk();
        $response->assertSee('Test');
    }

    public function test_non_admin_cannot_view_contact_submissions(): void
    {
        $hr = User::factory()->create();
        $hr->assignRole('hr');

        $response = $this->actingAs($hr)->get(route('admin.contact-submissions.index'));

        $response->assertForbidden();
    }

    public function test_login_is_rate_limited_after_repeated_attempts(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);
        }

        $response = $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);

        $response->assertStatus(429);
    }

    public function test_contact_form_is_rate_limited_after_repeated_submissions(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('contact.store'), ['name' => 'Spam', 'email' => "spam{$i}@test.com", 'message' => 'x']);
        }

        $response = $this->post(route('contact.store'), ['name' => 'Spam', 'email' => 'spam6@test.com', 'message' => 'x']);

        $response->assertStatus(429);
    }
}