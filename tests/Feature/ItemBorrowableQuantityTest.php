<?php

use App\Enums\BorrowingStatus;
use App\Enums\ItemAuditCondition;
use App\Enums\ItemStatus;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Item;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @return array{item: Item, borrowing: Borrowing}
 */
function createBorrowableItemFixture(int $quantity = 10, int $borrowedQuantity = 3): array
{
    $user = User::factory()->create();
    $organization = Organization::create(['name' => 'Test Organization']);
    $location = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Test Location',
    ]);
    $model = InventoryModel::create(['name' => 'Test Model']);

    $item = Item::create([
        'model_id' => $model->id,
        'location_id' => $location->id,
        'serial_number' => fake()->unique()->regexify('[A-Z0-9]{8}'),
        'quantity' => $quantity,
        'status' => ItemStatus::Active,
    ]);

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'borrowed_at' => now(),
        'due_at' => now()->addWeek(),
        'status' => BorrowingStatus::Active,
    ]);

    BorrowingItem::create([
        'borrowing_id' => $borrowing->id,
        'item_id' => $item->id,
        'quantity' => $borrowedQuantity,
        'checked_out_at' => now(),
        'condition_out' => ItemAuditCondition::Good,
    ]);

    return compact('item', 'borrowing');
}

function borrowableItemsTableQuery(): Builder
{
    return Item::query()
        ->borrowable()
        ->select('items.*')
        ->selectRaw('CASE WHEN items.quantity - COALESCE(SUM(borrowing_items.quantity), 0) < 0 THEN 0 ELSE items.quantity - COALESCE(SUM(borrowing_items.quantity), 0) END as borrowable_quantity')
        ->leftJoin('borrowing_items', function ($join): void {
            $join->on('borrowing_items.item_id', '=', 'items.id')
                ->whereNull('borrowing_items.checked_in_at');
        })
        ->groupBy('items.id');
}

test('borrowable quantity subtracts active borrowing item quantities', function (): void {
    ['item' => $item] = createBorrowableItemFixture(quantity: 10, borrowedQuantity: 3);

    expect($item->fresh()->borrowable_quantity)->toBe(7);
});

test('borrowable quantity uses join aggregate without loading active borrowing items relation', function (): void {
    ['item' => $item] = createBorrowableItemFixture(quantity: 10, borrowedQuantity: 3);

    $loadedItem = borrowableItemsTableQuery()->find($item->id);

    expect($loadedItem->borrowable_quantity)->toBe(7)
        ->and($loadedItem->relationLoaded('activeBorrowingItems'))->toBeFalse();
});

test('borrowable quantity uses eager loaded active borrowing items when present', function (): void {
    ['item' => $item] = createBorrowableItemFixture(quantity: 10, borrowedQuantity: 3);

    $loadedItem = Item::query()
        ->with('activeBorrowingItems')
        ->find($item->id);

    expect($loadedItem->borrowable_quantity)->toBe(7)
        ->and($loadedItem->relationLoaded('activeBorrowingItems'))->toBeTrue();
});

test('borrowable query with join does not scale queries per item', function (): void {
    createBorrowableItemFixture(quantity: 10, borrowedQuantity: 2);
    createBorrowableItemFixture(quantity: 8, borrowedQuantity: 1);
    createBorrowableItemFixture(quantity: 5, borrowedQuantity: 1);

    DB::enableQueryLog();

    borrowableItemsTableQuery()
        ->get()
        ->each(fn (Item $item): int => $item->borrowable_quantity);

    expect(count(DB::getQueryLog()))->toBe(1);
});
