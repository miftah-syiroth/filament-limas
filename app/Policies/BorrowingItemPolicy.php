<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\BorrowingStatus;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BorrowingItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class BorrowingItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BorrowingItem');
    }

    public function view(AuthUser $authUser, BorrowingItem $borrowingItem): bool
    {
        return $authUser->can('View:BorrowingItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BorrowingItem');
    }

    public function update(AuthUser $authUser, BorrowingItem $borrowingItem): bool
    {
        // jika borrowing status sudah returned, maka tidak dapat diupdate
        if ($borrowingItem->borrowing->status === BorrowingStatus::Returned) {
            return false;
        }

        return $authUser->can('Update:BorrowingItem');
    }

    public function delete(AuthUser $authUser, BorrowingItem $borrowingItem): bool
    {
        return $authUser->can('Delete:BorrowingItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BorrowingItem');
    }

    public function restore(AuthUser $authUser, BorrowingItem $borrowingItem): bool
    {
        return $authUser->can('Restore:BorrowingItem');
    }

    public function forceDelete(AuthUser $authUser, BorrowingItem $borrowingItem): bool
    {
        return $authUser->can('ForceDelete:BorrowingItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BorrowingItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BorrowingItem');
    }

    public function replicate(AuthUser $authUser, BorrowingItem $borrowingItem): bool
    {
        return $authUser->can('Replicate:BorrowingItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BorrowingItem');
    }

}