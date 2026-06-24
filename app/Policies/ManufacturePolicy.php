<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Manufacture;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManufacturePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Manufacture');
    }

    public function view(AuthUser $authUser, Manufacture $manufacture): bool
    {
        return $authUser->can('View:Manufacture');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Manufacture');
    }

    public function update(AuthUser $authUser, Manufacture $manufacture): bool
    {
        return $authUser->can('Update:Manufacture');
    }

    public function delete(AuthUser $authUser, Manufacture $manufacture): bool
    {
        if ($manufacture->models->count() > 0) {
            return false;
        }
        return $authUser->can('Delete:Manufacture');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Manufacture');
    }

    public function restore(AuthUser $authUser, Manufacture $manufacture): bool
    {
        return $authUser->can('Restore:Manufacture');
    }

    public function forceDelete(AuthUser $authUser, Manufacture $manufacture): bool
    {
        if ($manufacture->models->count() > 0) {
            return false;
        }
        return $authUser->can('ForceDelete:Manufacture');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Manufacture');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Manufacture');
    }

    public function replicate(AuthUser $authUser, Manufacture $manufacture): bool
    {
        return $authUser->can('Replicate:Manufacture');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Manufacture');
    }

}