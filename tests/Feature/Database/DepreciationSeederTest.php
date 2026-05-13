<?php

use App\Enums\DepreciationMethod;
use App\Models\Depreciation;
use Database\Seeders\DepreciationSeeder;
use Database\Seeders\ModelSeeder;

it('seeds two depreciation rows and assigns them to Apple smartphone and laptop models', function (): void {
    $this->seed(ModelSeeder::class);
    $this->seed(DepreciationSeeder::class);

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
});
