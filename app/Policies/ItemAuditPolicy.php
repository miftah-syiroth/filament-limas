<?php

namespace App\Policies;

use App\Enums\ItemStatus;
use App\Models\Item;
use App\Models\ItemAudit;
use App\Models\User;

class ItemAuditPolicy
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
    public function view(User $user, ItemAudit $itemAudit): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?Item $item = null): bool
    {
        if ($item && $item->status === ItemStatus::Disposed) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ItemAudit $itemAudit): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ItemAudit $itemAudit): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ItemAudit $itemAudit): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ItemAudit $itemAudit): bool
    {
        return true;
    }
}
