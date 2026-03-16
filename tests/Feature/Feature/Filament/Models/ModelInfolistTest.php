<?php

use App\Enums\CategoryType;
use App\Enums\DeprecationMethod;
use App\Models\Category;
use App\Models\Deprecation;
use App\Models\Manufacture;
use App\Models\Model;
use App\Models\User;

test('model infolist menampilkan data relasi yang relevan', function () {
    config(['app.env' => 'local']);

    $user = User::factory()->create();

    $category = Category::create([
        'name' => 'Laptop',
        'type' => CategoryType::Asset,
        'notes' => null,
    ]);

    $manufacture = Manufacture::create([
        'name' => 'Dell',
        'url' => null,
        'support_url' => null,
        'support_phone' => null,
        'support_email' => null,
        'warranty_lookup_url' => null,
        'notes' => null,
    ]);

    $deprecation = Deprecation::create([
        'name' => 'SL 3 Tahun',
        'months' => 36,
        'minimum_value' => 100000,
        'method' => DeprecationMethod::Amount,
        'notes' => null,
    ]);

    $model = Model::create([
        'name' => 'Latitude 7440',
        'model_number' => 'LAT-7440',
        'min_amount' => 3,
        'end_of_life' => 60,
        'require_serial_number' => true,
        'manufacture_id' => $manufacture->id,
        'category_id' => $category->id,
        'deprecation_id' => $deprecation->id,
        'audit_interval' => 6,
        'notes' => 'Model untuk tim engineering',
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('filament.admin.resources.models.view', ['record' => $model]));

    $response
        ->assertOk()
        ->assertSee('Latitude 7440')
        ->assertSee('LAT-7440')
        ->assertSee('Dell')
        ->assertSee('Laptop')
        ->assertSee('Asset')
        ->assertSee('SL 3 Tahun')
        ->assertSee('36')
        ->assertSee('Amount');
});
