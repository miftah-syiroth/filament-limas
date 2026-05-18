<?php

use App\Models\Role;
use App\Models\User;

test('guests are redirected to the login page when visiting admin panel', function () {
    $response = $this->get(route('filament.admin.pages.dashboard'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the admin dashboard', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get(route('filament.admin.pages.dashboard'));

    $response->assertSuccessful();
});
