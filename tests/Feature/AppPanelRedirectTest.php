<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('guests visiting app path are redirected to login', function () {
    $response = $this->get('/app');

    $response->assertRedirect(route('login'));
});

test('guests visiting nested app path are redirected to login', function () {
    $response = $this->get('/app/dashboard');

    $response->assertRedirect(route('login'));
});

test('authenticated admin users visiting app path are redirected to admin', function () {
    Role::create(['name' => 'admin', 'guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/app');

    $response->assertRedirect('/admin');
});

test('filament app panel routes are not registered', function () {
    expect(Route::has('filament.app.pages.dashboard'))->toBeFalse();
});
