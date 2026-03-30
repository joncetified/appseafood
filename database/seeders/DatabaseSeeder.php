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

        $superAdminRole = Role::where('name', 'super_admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $kasirRole = Role::where('name', 'kasir')->first();

        if ($superAdminRole) {
            User::updateOrCreate(
                ['email' => env('SUPER_ADMIN_EMAIL', 'superadmin@seafood.local')],
                [
                    'role_id' => $superAdminRole->id,
                    'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                    'password' => env('SUPER_ADMIN_PASSWORD', 'ChangeMe123!'),
                ]
            );
        }

// Hanya super admin yang dibuat
        // if ($managerRole) {
        //     User::updateOrCreate(
        //         ['email' => env('MANAGER_EMAIL', 'manager@seafood.local')],
        //         ...
        //     );
        // } dst...
    }
}
