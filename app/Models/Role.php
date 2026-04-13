<?php

namespace App\Models;

use App\Models\Concerns\LocksSuperAdminModelHasRolesPivot;
use App\Models\Concerns\LocksSuperAdminRolePermissionsPivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUuids, LocksSuperAdminModelHasRolesPivot, LocksSuperAdminRolePermissionsPivot;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::deleting(function (Role $role): bool {
            return $role->name !== config('filament-shield.super_admin.name', 'super_admin');
        });
    }
}
