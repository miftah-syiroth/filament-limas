<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ItemAudit;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemAuditPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ItemAudit');
    }

    public function view(AuthUser $authUser, ItemAudit $itemAudit): bool
    {
        return $authUser->can('View:ItemAudit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ItemAudit');
    }

    public function update(AuthUser $authUser, ItemAudit $itemAudit): bool
    {
        return $authUser->can('Update:ItemAudit');
    }

    public function delete(AuthUser $authUser, ItemAudit $itemAudit): bool
    {
        return $authUser->can('Delete:ItemAudit');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ItemAudit');
    }

    public function restore(AuthUser $authUser, ItemAudit $itemAudit): bool
    {
        return $authUser->can('Restore:ItemAudit');
    }

    public function forceDelete(AuthUser $authUser, ItemAudit $itemAudit): bool
    {
        return $authUser->can('ForceDelete:ItemAudit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ItemAudit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ItemAudit');
    }

    public function replicate(AuthUser $authUser, ItemAudit $itemAudit): bool
    {
        return $authUser->can('Replicate:ItemAudit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ItemAudit');
    }

}