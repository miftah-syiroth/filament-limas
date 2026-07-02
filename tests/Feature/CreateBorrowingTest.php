<?php

use App\Enums\BorrowingStatus;
use App\Enums\ItemStatus;
use App\Filament\Resources\Borrowings\Pages\CreateBorrowing;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Item;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;

/**
 * @return array{item: Item, location: Location}
 */
function createBorrowableItemForBorrowingTest(int $quantity = 10): array
{
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

    return compact('item', 'location');
}

function createBorrowingPage(): CreateBorrowing
{
    return new CreateBorrowing;
}

/**
 * @param  array<int, mixed>  $arguments
 */
function invokeCreateBorrowingMethod(CreateBorrowing $page, string $method, array $arguments = []): mixed
{
    $reflection = new ReflectionMethod(CreateBorrowing::class, $method);
    $reflection->setAccessible(true);

    return $reflection->invoke($page, ...$arguments);
}

test('getInvalidQuantitySerialNumbers flags items without quantity', function (): void {
    ['item' => $item] = createBorrowableItemForBorrowingTest();

    $page = createBorrowingPage();
    $page->quantitiesToBorrow = [];

    $records = Item::query()
        ->with('activeBorrowingItems')
        ->whereKey($item->id)
        ->get();

    $invalidSerialNumbers = invokeCreateBorrowingMethod($page, 'getInvalidQuantitySerialNumbers', [$records]);

    expect($invalidSerialNumbers)->toBe([$item->serial_number]);
});

test('getInvalidQuantitySerialNumbers flags items exceeding borrowable quantity', function (): void {
    ['item' => $item] = createBorrowableItemForBorrowingTest(quantity: 5);

    $page = createBorrowingPage();
    $page->quantitiesToBorrow = [$item->id => 6];

    $records = Item::query()
        ->with('activeBorrowingItems')
        ->whereKey($item->id)
        ->get();

    $invalidSerialNumbers = invokeCreateBorrowingMethod($page, 'getInvalidQuantitySerialNumbers', [$records]);

    expect($invalidSerialNumbers)->toBe([$item->serial_number]);
});

test('createBorrowingFromSelectedItems creates borrowing and borrowing items', function (): void {
    ['item' => $item, 'location' => $location] = createBorrowableItemForBorrowingTest(quantity: 10);

    $page = createBorrowingPage();
    $page->quantitiesToBorrow = [$item->id => 3];

    $records = Item::query()
        ->with(['activeBorrowingItems', 'latestAudit'])
        ->whereKey($item->id)
        ->get();

    $borrowing = invokeCreateBorrowingMethod($page, 'createBorrowingFromSelectedItems', [
        $records,
        [
            'borrowed_at' => now(),
            'due_at' => now()->addWeek(),
            'to_location_id' => $location->id,
            'to_department_id' => null,
            'to_room_id' => null,
            'notes' => null,
        ],
    ]);

    expect($borrowing)->toBeInstanceOf(Borrowing::class)
        ->and($borrowing->status)->toBe(BorrowingStatus::Active)
        ->and($borrowing->to_location_id)->toBe($location->id);

    $borrowingItem = BorrowingItem::query()->sole();

    expect($borrowingItem->borrowing_id)->toBe($borrowing->id)
        ->and($borrowingItem->item_id)->toBe($item->id)
        ->and($borrowingItem->quantity)->toBe(3)
        ->and($borrowingItem->from_location_id)->toBe($location->id)
        ->and($borrowingItem->to_location_id)->toBe($location->id);
});
