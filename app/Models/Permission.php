<?php

namespace App\Models;

use App\Models\Concerns\LocksSuperAdminRolePermissionsPivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUuids, LocksSuperAdminRolePermissionsPivot;

    protected $guarded = [];
}
