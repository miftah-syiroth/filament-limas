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

/**
 * @return array{borrowing: Borrowing, items: array<int, BorrowingItem>}
 */
function createBorrowingWithTwoItems(): array
{
    $user = User::factory()->create();
    $organization = Organization::create(['name' => 'Test Organization']);
    $location = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Test Location',
    ]);
    $model = InventoryModel::create(['name' => 'Test Model']);

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'borrowed_at' => now(),
        'due_at' => now()->addWeek(),
        'status' => BorrowingStatus::Active,
    ]);

    $items = [];

    foreach (range(1, 2) as $index) {
        $item = Item::create([
            'model_id' => $model->id,
            'location_id' => $location->id,
            'serial_number' => fake()->unique()->regexify('[A-Z0-9]{8}'),
            'quantity' => 10,
            'status' => ItemStatus::Active,
        ]);

        $items[$index] = BorrowingItem::create([
            'borrowing_id' => $borrowing->id,
            'item_id' => $item->id,
            'quantity' => 2,
            'checked_out_at' => now(),
            'condition_out' => ItemAuditCondition::Good,
        ]);
    }

    return compact('borrowing', 'items');
}

test('borrowing stays active when only some items are checked in', function (): void {
    ['borrowing' => $borrowing, 'items' => $items] = createBorrowingWithTwoItems();

    $items[1]->update([
        'checked_in_at' => now(),
        'condition_in' => ItemAuditCondition::Good,
    ]);

    $borrowing->refresh();

    expect($borrowing->status)->toBe(BorrowingStatus::Active)
        ->and($borrowing->returned_at)->toBeNull();
});

test('borrowing is marked returned when all items are checked in', function (): void {
    ['borrowing' => $borrowing, 'items' => $items] = createBorrowingWithTwoItems();

    $items[1]->update([
        'checked_in_at' => now(),
        'condition_in' => ItemAuditCondition::Good,
    ]);

    $items[2]->update([
        'checked_in_at' => now(),
        'condition_in' => ItemAuditCondition::Good,
    ]);

    $borrowing->refresh();

    expect($borrowing->status)->toBe(BorrowingStatus::Returned)
        ->and($borrowing->returned_at)->not->toBeNull();
});

test('returned_at is not overwritten when borrowing is already returned', function (): void {
    ['borrowing' => $borrowing, 'items' => $items] = createBorrowingWithTwoItems();

    $originalReturnedAt = now()->subDay();

    $borrowing->update([
        'status' => BorrowingStatus::Returned,
        'returned_at' => $originalReturnedAt,
    ]);

    foreach ($items as $borrowingItem) {
        $borrowingItem->update([
            'checked_in_at' => now(),
            'condition_in' => ItemAuditCondition::Good,
        ]);
    }

    $borrowing->refresh();

    expect($borrowing->status)->toBe(BorrowingStatus::Returned)
        ->and($borrowing->returned_at?->toDateTimeString())->toBe($originalReturnedAt->toDateTimeString());
});
