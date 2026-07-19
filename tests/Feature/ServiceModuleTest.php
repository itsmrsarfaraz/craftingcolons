<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceModuleTest extends TestCase
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

    public function test_staff_can_publish_a_service(): void
    {
        $staff = $this->staff();

        $response = $this->actingAs($staff)->post(route('staff.services.store'), [
            'title' => 'Web Development',
            'icon' => '💻',
            'short_description' => 'We build web platforms.',
            'body' => 'Full service description.',
            'status' => 'published',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('services', ['title' => 'Web Development', 'status' => 'published']);
    }

    public function test_draft_service_is_hidden_from_public_listing(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff)->post(route('staff.services.store'), [
            'title' => 'Unfinished Service',
            'short_description' => 'Not ready.',
            'body' => 'Draft content.',
            'status' => 'draft',
        ]);

        $response = $this->get(route('services.index'));

        $response->assertDontSee('Unfinished Service');
    }

    public function test_published_service_is_visible_on_public_page(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff)->post(route('staff.services.store'), [
            'title' => 'Mobile Apps',
            'short_description' => 'We build mobile apps.',
            'body' => 'Full content.',
            'status' => 'published',
        ]);

        $response = $this->get(route('services.index'));

        $response->assertSee('Mobile Apps');
    }

    public function test_services_render_on_home_page_in_display_order(): void
    {
        $staff = $this->staff();
        $this->actingAs($staff)->post(route('staff.services.store'), [
            'title' => 'Second Service', 'short_description' => 'B', 'body' => 'B', 'status' => 'published', 'order' => 2,
        ]);
        $this->actingAs($staff)->post(route('staff.services.store'), [
            'title' => 'First Service', 'short_description' => 'A', 'body' => 'A', 'status' => 'published', 'order' => 1,
        ]);

        $response = $this->get(route('home'));

        $response->assertSeeInOrder(['First Service', 'Second Service']);
    }

    public function test_non_staff_cannot_create_services(): void
    {
        $applicant = User::factory()->create();
        $applicant->assignRole('applicant');

        $response = $this->actingAs($applicant)->post(route('staff.services.store'), [
            'title' => 'Sneaky Service', 'short_description' => 'x', 'body' => 'x', 'status' => 'published',
        ]);

        $response->assertForbidden();
    }
}