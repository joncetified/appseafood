<?php

namespace App\Support;

class DefaultPagePermissions
{
    public const MAP = [
        'super_admin' => [
            'dashboard',
            'categories',
            'seafood_items',
            'promotions',
            'testimonials',
            'website_settings',
            'users',
            'access_control',
            'deleted_records',
            'maintenance',
            'orders',
            'reports',
            'imports_exports',
        ],
        'admin' => [
            'dashboard',
            'categories',
            'seafood_items',
            'promotions',
            'testimonials',
            'website_settings',
            'orders',
            'reports',
            'imports_exports',
        ],
        'manager' => [
            'dashboard',
            'reports',
        ],
        'kasir' => [
            'dashboard',
            'orders',
        ],
        'pelanggan' => [],
    ];

    public static function forRole(?string $roleName): array
    {
        return self::MAP[$roleName ?? ''] ?? [];
    }
}
