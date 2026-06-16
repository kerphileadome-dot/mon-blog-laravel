<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\RegistrationOtpNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_must_verify_email_otp_before_access(): void
    {
        Notification::fake();

        $email = 'test.user@gmail.com';

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('register.verify'));

        Notification::assertSentOnDemand(
            RegistrationOtpNotification::class,
            function (RegistrationOtpNotification $notification, array $channels, object $notifiable) use ($email, &$otp) {
                $otp = $notification->otp;

                return $notifiable->routes['mail'] === $email;
            }
        );

        $verifyResponse = $this->withSession(['registration_email' => $email])
            ->post('/register/verify', ['otp' => $otp]);

        $this->assertAuthenticated();
        $verifyResponse->assertRedirect(route('posts.index', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'visitor',
        ]);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_registration_rejects_non_gmail_addresses(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@yahoo.fr',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }
}
