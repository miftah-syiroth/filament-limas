<?php

namespace App\Policies;

use App\Enums\ItemStatus;
use App\Enums\MaintenanceStatus;
use App\Models\Item;
use App\Models\Maintenance;
use App\Models\User;

class MaintenancePolicy
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
    public function view(User $user, Maintenance $maintenance): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Item $item): bool
    {
        // boleh jika status item adalah active, under_diagnosis, under_repair, damaged, irreparable, lost, stolen, archived
        if (! in_array($item->status, [
            ItemStatus::Active,
            ItemStatus::UnderDiagnosis,
            ItemStatus::UnderRepair,
            ItemStatus::Damaged,
        ])) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Maintenance $maintenance): bool
    {
        // tidak boleh diubah jika status completed && completed_at sudah ada
        if ($maintenance->status === MaintenanceStatus::Completed && $maintenance->completed_at) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Maintenance $maintenance): bool
    {
        if ($maintenance->status === MaintenanceStatus::Completed && $maintenance->completed_at) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Maintenance $maintenance): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Maintenance $maintenance): bool
    {
        if ($maintenance->status === MaintenanceStatus::Completed && $maintenance->completed_at) {
            return false;
        }

        return true;
    }
}
