<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Deprecation;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeprecationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Deprecation');
    }

    public function view(AuthUser $authUser, Deprecation $deprecation): bool
    {
        return $authUser->can('View:Deprecation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Deprecation');
    }

    public function update(AuthUser $authUser, Deprecation $deprecation): bool
    {
        return $authUser->can('Update:Deprecation');
    }

    public function delete(AuthUser $authUser, Deprecation $deprecation): bool
    {
        return $authUser->can('Delete:Deprecation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Deprecation');
    }

    public function restore(AuthUser $authUser, Deprecation $deprecation): bool
    {
        return $authUser->can('Restore:Deprecation');
    }

    public function forceDelete(AuthUser $authUser, Deprecation $deprecation): bool
    {
        return $authUser->can('ForceDelete:Deprecation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Deprecation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Deprecation');
    }

    public function replicate(AuthUser $authUser, Deprecation $deprecation): bool
    {
        return $authUser->can('Replicate:Deprecation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Deprecation');
    }

}