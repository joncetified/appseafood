<?php

namespace Tests\Feature;

use App\Mail\AccountActivationMail;
use App\Models\AccountActivationToken;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_inactive_user_and_sends_activation_mail(): void
    {
        Mail::fake();

        Role::create([
            'name' => 'pelanggan',
            'label' => 'Pelanggan',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test Customer',
            'email' => 'customer@example.test',
            'whatsapp_number' => '6281234567890',
            'password' => 'Customer123!',
            'password_confirmation' => 'Customer123!',
        ]);

        $response->assertRedirectContains('/activation-sent');

        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.test',
            'is_active' => false,
        ]);

        Mail::assertSent(AccountActivationMail::class);
    }

    public function test_activation_link_activates_the_user(): void
    {
        $role = Role::create([
            'name' => 'pelanggan',
            'label' => 'Pelanggan',
        ]);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Pending Customer',
            'email' => 'pending@example.test',
            'password' => 'Customer123!',
            'is_active' => false,
        ]);

        $rawToken = 'activation-token';

        AccountActivationToken::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => hash('sha256', $rawToken),
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->get('/activate-account/'.$rawToken);

        $response->assertRedirect('/owner-login');
        $user->refresh();

        $this->assertTrue($user->is_active);
        $this->assertNotNull($user->email_verified_at);
    }
}
