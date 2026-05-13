<?php

use App\Enums\CategoryType;
use App\Enums\DepreciationMethod;
use App\Models\Category;
use App\Models\Depreciation;
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

    $depreciation = Depreciation::create([
        'name' => 'SL 3 Tahun',
        'months' => 36,
        'minimum_value' => 100000,
        'method' => DepreciationMethod::Amount,
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
        'depreciation_id' => $depreciation->id,
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
        ->assertSee('36');
});
