<?php

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use App\Filament\Resources\Items\Pages\CreateItem;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;

/**
 * @return array{model: InventoryModel, location1: Location, location2: Location}
 */
function createItemBatchFixtures(CategoryType $categoryType = CategoryType::Asset): array
{
    $organization = Organization::create(['name' => 'Test Organization']);

    $location1 = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Location One',
    ]);

    $location2 = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Location Two',
    ]);

    $category = Category::create([
        'name' => 'Test Category',
        'type' => $categoryType,
    ]);

    $model = InventoryModel::create([
        'name' => 'Test Model',
        'category_id' => $category->id,
    ]);

    return compact('model', 'location1', 'location2');
}

/**
 * @param  array<string, mixed>  $data
 */
function handleItemBatchCreation(array $data): Model
{
    $page = new CreateItem;
    $method = new ReflectionMethod(CreateItem::class, 'handleRecordCreation');
    $method->setAccessible(true);

    return $method->invoke($page, $data);
}

test('individual tracking creates one item per quantity in a repeater row', function () {
    $fixtures = createItemBatchFixtures();

    handleItemBatchCreation([
        'model_id' => $fixtures['model']->id,
        'is_individual_tracking' => true,
        'status' => ItemStatus::Active,
        'items' => [
            [
                'location_id' => $fixtures['location1']->id,
                'department_id' => null,
                'room_id' => null,
                'quantity' => 3,
            ],
        ],
    ]);

    expect(Item::count())->toBe(3);
    expect(Item::query()->where('location_id', $fixtures['location1']->id)->count())->toBe(3);
    expect(Item::query()->where('quantity', 1)->count())->toBe(3);
    expect(Item::query()->where('is_individual_tracking', true)->count())->toBe(3);
    expect(StockMovement::count())->toBe(0);
});

test('non-individual tracking creates one item per repeater row with form quantity', function () {
    $fixtures = createItemBatchFixtures();

    handleItemBatchCreation([
        'model_id' => $fixtures['model']->id,
        'is_individual_tracking' => false,
        'status' => ItemStatus::Active,
        'items' => [
            [
                'location_id' => $fixtures['location1']->id,
                'department_id' => null,
                'room_id' => null,
                'quantity' => 10,
            ],
            [
                'location_id' => $fixtures['location2']->id,
                'department_id' => null,
                'room_id' => null,
                'quantity' => 5,
            ],
        ],
    ]);

    expect(Item::count())->toBe(2);
    expect(Item::query()->where('location_id', $fixtures['location1']->id)->value('quantity'))->toBe(10);
    expect(Item::query()->where('location_id', $fixtures['location2']->id)->value('quantity'))->toBe(5);
    expect(Item::query()->where('is_individual_tracking', false)->count())->toBe(2);
    expect(StockMovement::count())->toBe(2);
    expect(StockMovement::query()->sum('quantity'))->toBe(15);
});

test('consumable category always creates bulk items with stock movements', function () {
    $fixtures = createItemBatchFixtures(CategoryType::Consumable);

    handleItemBatchCreation([
        'model_id' => $fixtures['model']->id,
        'is_individual_tracking' => true,
        'status' => ItemStatus::Active,
        'items' => [
            [
                'location_id' => $fixtures['location1']->id,
                'department_id' => null,
                'room_id' => null,
                'quantity' => 20,
            ],
        ],
    ]);

    expect(Item::count())->toBe(1);
    expect(Item::first()->quantity)->toBe(20);
    expect(Item::first()->is_individual_tracking)->toBeFalse();
    expect(StockMovement::count())->toBe(1);
    expect(StockMovement::first()->quantity)->toBe(20);
});

test('batch create throws when no repeater rows are provided', function () {
    $fixtures = createItemBatchFixtures();

    expect(fn () => handleItemBatchCreation([
        'model_id' => $fixtures['model']->id,
        'is_individual_tracking' => true,
        'items' => [],
    ]))->toThrow(RuntimeException::class, 'No items created.');
});
