<?php

namespace App\Policies;

use App\Enums\BorrowingStatus;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\User;

class BorrowingItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BorrowingItem $borrowingItem): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?Borrowing $borrowing = null): bool
    {
        // jika status returned && returned_at not null maka tidak boleh create
        if ($borrowing && $borrowing->status === BorrowingStatus::Returned && $borrowing->returned_at) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BorrowingItem $borrowingItem): bool
    {
        if ($borrowingItem->borrowing->status === BorrowingStatus::Returned && $borrowingItem->borrowing->returned_at) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BorrowingItem $borrowingItem): bool
    {
        if ($borrowingItem->borrowing->status === BorrowingStatus::Returned && $borrowingItem->borrowing->returned_at) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BorrowingItem $borrowingItem): bool
    {
        if ($borrowingItem->borrowing->status === BorrowingStatus::Returned && $borrowingItem->borrowing->returned_at) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BorrowingItem $borrowingItem): bool
    {
        if ($borrowingItem->borrowing->status === BorrowingStatus::Returned && $borrowingItem->borrowing->returned_at) {
            return false;
        }

        return true;
    }
}
