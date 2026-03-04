<?php

use App\Models\Category;
use App\Models\Manufacture;
use App\Models\Model as InventoryModel;
use Database\Seeders\ModelSeeder;

it('seeds 10 manufactures with 2 categories and 2 models per category', function () {
    $this->seed(ModelSeeder::class);

    expect(Manufacture::query()->count())->toBe(18)
        ->and(Category::query()->count())->toBe(26)
        ->and(InventoryModel::query()->count())->toBe(42);

    // Test first 8 manufactures have 2 categories with 2 models each
    $manufactureNamesWith2Categories = [
        'Apple',
        'Lenovo',
        'HP',
        'IKEA',
        'Olympus',
        'Gramedia',
        'Nike',
        'Adidas',
    ];

    foreach ($manufactureNamesWith2Categories as $name) {
        $manufacture = Manufacture::query()->where('name', $name)->first();

        expect($manufacture)->not->toBeNull();

        $categoryIds = InventoryModel::query()
            ->where('manufacture_id', $manufacture->id)
            ->distinct()
            ->pluck('category_id');

        expect($categoryIds)->toHaveCount(2);

        foreach ($categoryIds as $categoryId) {
            $modelCount = InventoryModel::query()
                ->where('manufacture_id', $manufacture->id)
                ->where('category_id', $categoryId)
                ->count();

            expect($modelCount)->toBe(2);
        }
    }

    // Test last 10 manufactures have 1 category with 1 model each
    $manufactureNamesWith1Category = [
        'Manduka',
        'Dr. Martens',
        'Pyrex',
        'Schott Duran',
        'Maspion',
        'Thermos',
        'Omron Healthcare',
        'B. Braun',
        'Philips',
        'Dräger',
    ];

    foreach ($manufactureNamesWith1Category as $name) {
        $manufacture = Manufacture::query()->where('name', $name)->first();

        expect($manufacture)->not->toBeNull();

        $categoryIds = InventoryModel::query()
            ->where('manufacture_id', $manufacture->id)
            ->distinct()
            ->pluck('category_id');

        expect($categoryIds)->toHaveCount(1);

        $modelCount = InventoryModel::query()
            ->where('manufacture_id', $manufacture->id)
            ->count();

        expect($modelCount)->toBe(1);
    }
});
