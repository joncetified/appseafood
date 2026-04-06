<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('page_permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('page_permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'page_permission_id']);
        });

        $permissions = [
            ['code' => 'dashboard', 'label' => 'Dashboard', 'description' => 'Boleh membuka dashboard admin.', 'sort_order' => 10],
            ['code' => 'categories', 'label' => 'Kategori', 'description' => 'Boleh mengelola kategori menu.', 'sort_order' => 20],
            ['code' => 'seafood_items', 'label' => 'Menu Seafood', 'description' => 'Boleh mengelola item seafood.', 'sort_order' => 30],
            ['code' => 'promotions', 'label' => 'Promo', 'description' => 'Boleh mengelola promosi.', 'sort_order' => 40],
            ['code' => 'testimonials', 'label' => 'Testimoni', 'description' => 'Boleh mengelola testimoni.', 'sort_order' => 50],
            ['code' => 'website_settings', 'label' => 'Website Settings', 'description' => 'Boleh mengubah pengaturan website.', 'sort_order' => 60],
            ['code' => 'users', 'label' => 'User & Role', 'description' => 'Boleh mengelola akun user.', 'sort_order' => 70],
            ['code' => 'access_control', 'label' => 'Access Control', 'description' => 'Boleh mengubah checklist hak akses halaman.', 'sort_order' => 80],
            ['code' => 'deleted_records', 'label' => 'Deleted Records', 'description' => 'Boleh melihat dan restore data terhapus.', 'sort_order' => 90],
            ['code' => 'maintenance', 'label' => 'Backup & Maintenance', 'description' => 'Boleh backup dan refresh koneksi database.', 'sort_order' => 100],
            ['code' => 'orders', 'label' => 'Pesanan', 'description' => 'Boleh melihat dan mengubah pesanan.', 'sort_order' => 110],
            ['code' => 'reports', 'label' => 'Laporan', 'description' => 'Boleh melihat laporan dan diagram.', 'sort_order' => 120],
            ['code' => 'imports_exports', 'label' => 'Import / Export', 'description' => 'Boleh import, export, dan backup users/items.', 'sort_order' => 130],
        ];

        DB::table('page_permissions')->insert(array_map(
            fn (array $permission) => [
                ...$permission,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            $permissions
        ));

        $permissionIds = DB::table('page_permissions')->pluck('id', 'code');
        $roleIds = DB::table('roles')->pluck('id', 'name');

        $defaults = [
            'super_admin' => $permissionIds->keys()->all(),
            'admin' => ['dashboard', 'categories', 'seafood_items', 'promotions', 'testimonials', 'website_settings', 'orders', 'reports', 'imports_exports'],
            'manager' => ['dashboard', 'reports'],
            'kasir' => ['dashboard', 'orders'],
            'pelanggan' => [],
        ];

        foreach ($defaults as $roleName => $permissionCodes) {
            $roleId = $roleIds->get($roleName);

            if (! $roleId) {
                continue;
            }

            $rows = collect($permissionCodes)
                ->map(fn (string $code) => $permissionIds->get($code))
                ->filter()
                ->map(fn (int $permissionId) => [
                    'role_id' => $roleId,
                    'page_permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->values()
                ->all();

            if ($rows !== []) {
                DB::table('page_permission_role')->insert($rows);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_permission_role');
        Schema::dropIfExists('page_permissions');
    }
};
