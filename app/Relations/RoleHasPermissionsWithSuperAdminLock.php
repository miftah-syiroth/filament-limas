<?php

declare(strict_types=1);

namespace App\Relations;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

/**
 * Blocks removing rows in `role_has_permissions` that involve the super admin role.
 */
class RoleHasPermissionsWithSuperAdminLock extends BelongsToMany
{
    /** @return array{attached: array, detached: array, updated: array} */
    public function sync($ids, $detaching = true)
    {
        if ($this->parent instanceof Role && $this->parentIsSuperAdminRole()) {
            return parent::sync($ids, false);
        }

        if ($this->parent instanceof Permission) {
            $superAdminId = $this->superAdminRoleKey();

            if ($superAdminId !== null) {
                $this->parent->loadMissing('roles');

                if ($this->parent->roles->contains(
                    fn (Role $role): bool => (string) $role->getKey() === $superAdminId
                )) {
                    $ids = [...collect($ids)->flatten()->all(), $superAdminId];
                }
            }
        }

        return parent::sync($ids, $detaching);
    }

    public function detach($ids = null, $touch = true)
    {
        if ($this->parent instanceof Role && $this->parentIsSuperAdminRole()) {
            return 0;
        }

        if ($this->parent instanceof Permission) {
            $superAdminId = $this->superAdminRoleKey();

            if ($superAdminId === null) {
                return parent::detach($ids, $touch);
            }

            if ($ids === null) {
                return (int) $this->newPivotQuery()
                    ->where($this->getQualifiedRelatedPivotKeyName(), '!=', $superAdminId)
                    ->delete();
            }

            $parsed = array_values(array_diff(
                (array) $this->parseIds($ids),
                [$superAdminId]
            ));

            if ($parsed === []) {
                return 0;
            }

            return parent::detach($parsed, $touch);
        }

        return parent::detach($ids, $touch);
    }

    private function parentIsSuperAdminRole(): bool
    {
        return $this->parent->getAttribute('name') === config('filament-shield.super_admin.name', 'super_admin');
    }

    private function superAdminRoleKey(): ?string
    {
        try {
            /** @var class-string<Role> $roleClass */
            $roleClass = config('permission.models.role');

            return (string) $roleClass::findByName(
                config('filament-shield.super_admin.name', 'super_admin'),
                $this->parent->getDefaultGuardName(),
            )->getKey();
        } catch (RoleDoesNotExist) {
            return null;
        }
    }
}
