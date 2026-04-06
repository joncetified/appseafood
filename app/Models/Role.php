<?php

namespace App\Models;

use App\Support\DefaultPagePermissions;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
    ];

    protected static function booted(): void
    {
        static::created(function (self $role): void {
            if (! Schema::hasTable('page_permissions') || ! Schema::hasTable('page_permission_role')) {
                return;
            }

            if ($role->pagePermissions()->exists()) {
                return;
            }

            $permissionIds = PagePermission::query()
                ->whereIn('code', DefaultPagePermissions::forRole($role->name))
                ->pluck('id');

            if ($permissionIds->isNotEmpty()) {
                $role->pagePermissions()->sync($permissionIds);
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pagePermissions(): BelongsToMany
    {
        return $this->belongsToMany(PagePermission::class)->withTimestamps();
    }
}
