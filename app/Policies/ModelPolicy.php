<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Model');
    }

    public function view(AuthUser $authUser, Model $model): bool
    {
        return $authUser->can('View:Model');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Model');
    }

    public function update(AuthUser $authUser, Model $model): bool
    {
        return $authUser->can('Update:Model');
    }

    public function delete(AuthUser $authUser, Model $model): bool
    {
        if ($model->items->count() > 0) {
            return false;
        }
        return $authUser->can('Delete:Model');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Model');
    }

    public function restore(AuthUser $authUser, Model $model): bool
    {
        return $authUser->can('Restore:Model');
    }

    public function forceDelete(AuthUser $authUser, Model $model): bool
    {
        if ($model->items->count() > 0) {
            return false;
        }
        return $authUser->can('ForceDelete:Model');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Model');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Model');
    }

    public function replicate(AuthUser $authUser, Model $model): bool
    {
        return $authUser->can('Replicate:Model');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Model');
    }

}