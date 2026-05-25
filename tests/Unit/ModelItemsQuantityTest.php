<?php

use App\Enums\ItemStatus;
use App\Models\Item;
use App\Models\Model as InventoryModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

uses(TestCase::class);

test('excluded from inventory statuses are terminal item statuses', function (): void {
    expect(ItemStatus::excludedFromInventory())->toBe([
        ItemStatus::Lost,
        ItemStatus::Stolen,
        ItemStatus::Archived,
        ItemStatus::Disposed,
    ]);
});

test('in inventory scope excludes terminal statuses', function (): void {
    $query = Item::query()->inInventory();

    expect($query->getBindings())->toBe([
        ItemStatus::Lost->value,
        ItemStatus::Stolen->value,
        ItemStatus::Archived->value,
        ItemStatus::Disposed->value,
    ]);
});

test('model query loads sum of in inventory item quantities as items_quantity', function (): void {
    $query = InventoryModel::query()->withSum('itemsInInventory as items_quantity', 'quantity');

    expect($query->toSql())->toContain('items_quantity');
});

test('items in inventory relationship applies in inventory scope', function (): void {
    $relation = (new InventoryModel)->itemsInInventory();

    expect($relation)->toBeInstanceOf(HasMany::class);

    $relationQuery = $relation->getQuery();

    expect($relationQuery->getBindings())->toBe([
        ItemStatus::Lost->value,
        ItemStatus::Stolen->value,
        ItemStatus::Archived->value,
        ItemStatus::Disposed->value,
    ]);
});
