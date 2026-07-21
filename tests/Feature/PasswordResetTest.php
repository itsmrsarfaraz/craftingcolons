<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_form_renders(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
    }

    public function test_submitting_a_known_email_sends_a_reset_notification(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $response = $this->post(route('password.email'), ['email' => $user->email]);

        $response->assertRedirect();
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_submitting_an_unknown_email_gives_the_same_response_as_a_known_one(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), ['email' => 'nobody@nowhere.com']);

        // Same redirect/status message regardless — no enumeration signal.
        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_a_user_can_reset_their_password_with_a_valid_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('NewSecurePassword123!', $user->fresh()->password));
    }

    public function test_a_reset_attempt_with_an_invalid_token_fails(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('password.update'), [
            'token' => 'not-a-real-token',
            'email' => $user->email,
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(Hash::check('NewSecurePassword123!', $user->fresh()->password));
    }

    public function test_the_new_password_actually_logs_the_user_in(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'BrandNewPassword456!',
            'password_confirmation' => 'BrandNewPassword456!',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'BrandNewPassword456!',
        ]);

        $this->assertAuthenticatedAs($user->fresh());
    }
}