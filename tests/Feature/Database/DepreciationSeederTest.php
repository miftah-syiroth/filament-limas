<?php

use App\Enums\DepreciationMethod;
use App\Models\Depreciation;
use App\Models\Model as InventoryModel;
use Database\Seeders\DepreciationSeeder;
use Database\Seeders\ModelSeeder;

it('seeds two depreciation rows and assigns them to Apple smartphone and laptop models', function (): void {
    $this->seed(DepreciationSeeder::class);
    $this->seed(ModelSeeder::class);

    expect(Depreciation::query()->count())->toBe(2);

    $iphonePolicy = Depreciation::query()->where('name', 'Depresiasi iPhone')->first();
    $macbookPolicy = Depreciation::query()->where('name', 'Depresiasi MacBook')->first();

    expect($iphonePolicy)->not->toBeNull()
        ->and($iphonePolicy->months)->toBe(36)
        ->and((float) $iphonePolicy->minimum_value)->toBe(20.0)
        ->and($iphonePolicy->method)->toBe(DepreciationMethod::Amount);

    expect($macbookPolicy)->not->toBeNull()
        ->and($macbookPolicy->months)->toBe(60)
        ->and((float) $macbookPolicy->minimum_value)->toBe(15.0)
        ->and($macbookPolicy->method)->toBe(DepreciationMethod::Amount);

    $smartphoneModels = InventoryModel::query()
        ->whereHas('category', fn ($query) => $query->where('name', 'smartphone'))
        ->get();

    expect($smartphoneModels)->toHaveCount(4)
        ->and($smartphoneModels->every(fn ($model) => $model->depreciation_id === $iphonePolicy->id))->toBeTrue();

    $laptopModels = InventoryModel::query()
        ->whereHas('category', fn ($query) => $query->where('name', 'laptop'))
        ->get();

    expect($laptopModels)->toHaveCount(4)
        ->and($laptopModels->every(fn ($model) => $model->depreciation_id === $macbookPolicy->id))->toBeTrue();

    expect(InventoryModel::query()
        ->whereHas('category', fn ($query) => $query->whereIn('name', ['sandal', 'kertas']))
        ->whereNotNull('depreciation_id')
        ->count())->toBe(0);
});
