<?php

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use App\Models\Category;
use App\Models\Item;
use App\Models\Manufacture;
use App\Models\Model as InventoryModel;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DepreciationSeeder;
use Database\Seeders\ModelSeeder;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\UserSeeder;

it('seeds Apple smartphone and laptop, Swallow sandal, and Sidu kertas with expected models', function () {
    $this->seed(DepartmentSeeder::class);
    $this->seed(UserSeeder::class);
    $this->seed(SupplierSeeder::class);
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

it('seeds items per model with tracked assets and bulk stock', function () {
    $this->seed(DepartmentSeeder::class);
    $this->seed(UserSeeder::class);
    $this->seed(SupplierSeeder::class);
    $this->seed(DepreciationSeeder::class);
    $this->seed(ModelSeeder::class);

    expect(Item::query()->count())->toBe(28);

    $smartphoneModel = InventoryModel::query()
        ->where('name', 'iPhone 14 128GB')
        ->first();

    expect($smartphoneModel)->not->toBeNull()
        ->and($smartphoneModel->items()->count())->toBe(3);

    $smartphoneItems = $smartphoneModel->items()->get();

    foreach ($smartphoneItems as $item) {
        expect($item->is_individual_tracking)->toBeTrue()
            ->and($item->quantity)->toBe(1)
            ->and($item->order_quantity)->toBe(1)
            ->and($item->status)->toBe(ItemStatus::Active)
            ->and($item->name)->toBeNull()
            ->and($item->serial_number)->toHaveLength(8)
            ->and($item->serial_number)->toBe(mb_strtoupper($item->serial_number))
            ->and(ctype_alnum($item->serial_number))->toBeTrue()
            ->and($item->purchase_date?->year)->toBe(2025)
            ->and($item->department->location_id)->toBe($item->location_id)
            ->and($item->room->location_id)->toBe($item->location_id);
    }

    $sandalModel = InventoryModel::query()
        ->where('name', 'sandal batik')
        ->first();

    expect($sandalModel)->not->toBeNull()
        ->and($sandalModel->items()->count())->toBe(1);

    $sandalItem = $sandalModel->items()->first();

    expect($sandalItem->is_individual_tracking)->toBeFalse()
        ->and($sandalItem->quantity)->toBe(20)
        ->and($sandalItem->order_quantity)->toBe(20)
        ->and($sandalItem->eol_date)->toBeNull();

    $kertasModel = InventoryModel::query()
        ->where('name', 'kertas A4')
        ->first();

    expect($kertasModel)->not->toBeNull()
        ->and($kertasModel->items()->count())->toBe(1);

    $kertasItem = $kertasModel->items()->first();

    expect($kertasItem->is_individual_tracking)->toBeFalse()
        ->and($kertasItem->quantity)->toBe(20)
        ->and($kertasItem->eol_date)->toBeNull();
});
