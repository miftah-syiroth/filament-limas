<?php

declare(strict_types=1);

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

test('users cannot update the super admin role even with permission', function () {
    Permission::create(['name' => 'Update:Role', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->givePermissionTo('Update:Role');

    $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $other = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    expect($user->can('update', $superAdmin))->toBeFalse();
    expect($user->can('update', $other))->toBeTrue();
});

test('users cannot delete the super admin role even with permission', function () {
    Permission::create(['name' => 'Delete:Role', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->givePermissionTo('Delete:Role');

    $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $other = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    expect($user->can('delete', $superAdmin))->toBeFalse();
    expect($user->can('delete', $other))->toBeTrue();
});

test('super admin role cannot be deleted at model level', function () {
    $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);

    expect($superAdmin->delete())->toBeFalse();
    expect(Role::query()->whereKey($superAdmin->getKey())->exists())->toBeTrue();
});

test('non super admin roles can be deleted at model level', function () {
    $editor = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    expect($editor->delete())->toBeTrue();
    expect(Role::query()->whereKey($editor->getKey())->exists())->toBeFalse();
});

test('super admin role stays assigned when syncing roles without it', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::create(['name' => 'editor', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin', 'editor');

    $user->syncRoles(['editor']);

    expect($user->fresh()->hasRole('super_admin'))->toBeTrue();
    expect($user->fresh()->hasRole('editor'))->toBeTrue();
});

test('super admin role cannot be removed with removeRole', function () {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $user->removeRole('super_admin');

    expect($user->fresh()->hasRole('super_admin'))->toBeTrue();
});

test('super admin role users pivot cannot be cleared via detach', function () {
    $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $superAdmin->users()->attach($user);

    expect($superAdmin->users()->detach())->toBe(0);
    expect($user->fresh()->hasRole('super_admin'))->toBeTrue();
});

test('super admin role cannot have permissions detached via role relation', function () {
    $permission = Permission::create(['name' => 'View:Item', 'guard_name' => 'web']);
    $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $superAdmin->givePermissionTo($permission);

    expect($superAdmin->permissions()->detach($permission->getKey()))->toBe(0);
    expect($superAdmin->fresh()->hasPermissionTo('View:Item'))->toBeTrue();
});

test('super admin role cannot be detached from permission via permission relation', function () {
    $permission = Permission::create(['name' => 'Edit:Item', 'guard_name' => 'web']);
    $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $superAdmin->givePermissionTo($permission);

    expect($permission->roles()->detach($superAdmin->getKey()))->toBe(0);
    expect($superAdmin->fresh()->hasPermissionTo('Edit:Item'))->toBeTrue();
});

test('non super admin roles can detach permissions', function () {
    $permission = Permission::create(['name' => 'Delete:Item', 'guard_name' => 'web']);
    $editor = Role::create(['name' => 'editor', 'guard_name' => 'web']);
    $editor->givePermissionTo($permission);

    expect($editor->permissions()->detach($permission->getKey()))->toBe(1);
    expect($editor->fresh()->hasPermissionTo('Delete:Item'))->toBeFalse();
});
