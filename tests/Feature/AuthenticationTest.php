<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
    }

    public function test_a_user_can_register_and_lands_on_applicant_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('applicant.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_a_user_can_login(): void
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);
        $user->assignRole('applicant');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('applicant.dashboard'));
    }

    public function test_wrong_role_cannot_access_admin_dashboard(): void
    {
        $user = \App\Models\User::factory()->create();
        $user->assignRole('applicant');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertForbidden();
    }
}