<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $defaultUsers = [
            [
                'role' => 'super_admin',
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                'email' => env('SUPER_ADMIN_EMAIL', 'superadmin@seafood.local'),
                'password' => env('SUPER_ADMIN_PASSWORD', 'ChangeMe123!'),
            ],
            [
                'role' => 'manager',
                'name' => env('MANAGER_NAME', 'Manager'),
                'email' => env('MANAGER_EMAIL', 'manager@seafood.local'),
                'password' => env('MANAGER_PASSWORD', 'ChangeMe123!'),
            ],
            [
                'role' => 'admin',
                'name' => env('ADMIN_NAME', 'Admin'),
                'email' => env('ADMIN_EMAIL', 'admin@seafood.local'),
                'password' => env('ADMIN_PASSWORD', 'ChangeMe123!'),
            ],
            [
                'role' => 'kasir',
                'name' => env('KASIR_NAME', 'Kasir'),
                'email' => env('KASIR_EMAIL', 'kasir@seafood.local'),
                'password' => env('KASIR_PASSWORD', 'ChangeMe123!'),
            ],
        ];

        foreach ($defaultUsers as $defaultUser) {
            $role = Role::where('name', $defaultUser['role'])->first();

            if (! $role) {
                continue;
            }

            User::updateOrCreate(
                ['email' => $defaultUser['email']],
                [
                    'role_id' => $role->id,
                    'name' => $defaultUser['name'],
                    'password' => $defaultUser['password'],
                ]
            );
        }
    }
}
