<?php

declare(strict_types=1);

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRole;
use App\Filament\Resources\Roles\RoleResource;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as ShieldRoleResource;

test('role resource extends filament shield role resource', function () {
    expect(is_subclass_of(RoleResource::class, ShieldRoleResource::class))->toBeTrue();
});

test('list roles page is registered to app role resource', function () {
    expect(ListRoles::getResource())->toBe(RoleResource::class);
});

test('app role resource registers app page classes', function () {
    $pages = RoleResource::getPages();

    expect($pages['index']->getPage())->toBe(ListRoles::class);
    expect($pages['create']->getPage())->toBe(CreateRole::class);
    expect($pages['view']->getPage())->toBe(ViewRole::class);
    expect($pages['edit']->getPage())->toBe(EditRole::class);
});
