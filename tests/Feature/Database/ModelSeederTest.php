<?php

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Manufacture;
use App\Models\Model as InventoryModel;
use Database\Seeders\DepreciationSeeder;
use Database\Seeders\ModelSeeder;

it('seeds Apple smartphone and laptop, Swallow sandal, and Sidu kertas with expected models', function () {
    $this->seed(DepreciationSeeder::class);
    $this->seed(ModelSeeder::class);

    expect(Manufacture::query()->count())->toBe(3)
        ->and(Category::query()->count())->toBe(4)
        ->and(InventoryModel::query()->count())->toBe(12);

    $apple = Manufacture::query()->where('name', 'Apple')->first();
    expect($apple)->not->toBeNull();

    $smartphone = Category::query()->where('name', 'smartphone')->first();
    $laptop = Category::query()->where('name', 'laptop')->first();

    expect($smartphone)->not->toBeNull()
        ->and($smartphone->type)->toBe(CategoryType::Asset)
        ->and($laptop)->not->toBeNull()
        ->and($laptop->type)->toBe(CategoryType::Asset);

    expect(InventoryModel::query()
        ->where('manufacture_id', $apple->id)
        ->where('category_id', $smartphone->id)
        ->count())->toBe(4);

    expect(InventoryModel::query()
        ->where('manufacture_id', $apple->id)
        ->where('category_id', $laptop->id)
        ->count())->toBe(4);

    $swallow = Manufacture::query()->where('name', 'Swallow')->first();
    $sandal = Category::query()->where('name', 'sandal')->first();

    expect($swallow)->not->toBeNull()
        ->and($sandal)->not->toBeNull()
        ->and($sandal->type)->toBe(CategoryType::Accessory);

    expect(InventoryModel::query()
        ->where('manufacture_id', $swallow->id)
        ->where('category_id', $sandal->id)
        ->pluck('name')
        ->sort()
        ->values()
        ->all())->toBe(['sandal batik', 'sandal polos']);

    $sidu = Manufacture::query()->where('name', 'Sidu')->first();
    $kertas = Category::query()->where('name', 'kertas')->first();

    expect($sidu)->not->toBeNull()
        ->and($kertas)->not->toBeNull()
        ->and($kertas->type)->toBe(CategoryType::Consumable);

    $kertasNames = InventoryModel::query()
        ->where('manufacture_id', $sidu->id)
        ->where('category_id', $kertas->id)
        ->pluck('name');

    expect($kertasNames)->toHaveCount(2)
        ->and($kertasNames)->toContain('kertas A4', 'Kertas F4');
});
