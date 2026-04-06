<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageAccessPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_page_permission_gets_forbidden(): void
    {
        $role = Role::create([
            'name' => 'custom_admin',
            'label' => 'Custom Admin',
        ]);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Custom Admin',
            'email' => 'custom-admin@example.test',
            'password' => 'ChangeMe123!',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/admin/reports');

        $response->assertForbidden();
    }
}
