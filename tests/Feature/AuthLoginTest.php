<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_login(): void
    {
        $role = Role::create([
            'name' => 'super_admin',
            'label' => 'Super Admin',
        ]);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Super Admin',
            'email' => 'superadmin@seafood.local',
            'password' => 'ChangeMe123!',
        ]);

        $response = $this->post('/owner-login', [
            'email' => 'superadmin@seafood.local',
            'password' => 'ChangeMe123!',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_login(): void
    {
        $role = Role::create([
            'name' => 'admin',
            'label' => 'Admin',
        ]);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Admin',
            'email' => 'admin@seafood.local',
            'password' => 'ChangeMe123!',
        ]);

        $response = $this->post('/owner-login', [
            'email' => 'admin@seafood.local',
            'password' => 'ChangeMe123!',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_is_redirected_to_owner_login_from_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/owner-login');
    }
}
