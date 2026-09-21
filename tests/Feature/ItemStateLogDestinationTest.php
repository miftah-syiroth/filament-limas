<?php

use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemStateLog;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use App\Models\Room;

/**
 * @return array{item: Item, otherLocation: Location, otherDepartment: Department}
 */
function createStateLogItem(): array
{
    $organization = Organization::create(['name' => 'Test Organization']);
    $location = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Current Location',
    ]);
    $otherLocation = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Other Location',
    ]);
    $department = Department::create(['name' => 'Current Department']);
    $otherDepartment = Department::create(['name' => 'Other Department']);
    $room = Room::create([
        'location_id' => $location->id,
        'name' => 'Current Room',
    ]);
    $model = InventoryModel::create(['name' => 'Test Model']);
    $item = Item::create([
        'model_id' => $model->id,
        'location_id' => $location->id,
        'department_id' => $department->id,
        'room_id' => $room->id,
        'serial_number' => fake()->unique()->regexify('[A-Z0-9]{8}'),
        'quantity' => 1,
        'status' => ItemStatus::Active,
    ]);

    return compact('item', 'otherLocation', 'otherDepartment');
}

test('state log omits destinations that match the item current values', function () {
    ['item' => $item, 'otherLocation' => $otherLocation] = createStateLogItem();

    $log = ItemStateLog::create([
        'item_id' => $item->id,
        'event_type' => ItemStateEventType::Transfer,
        'from_location_id' => $item->location_id,
        'to_location_id' => $otherLocation->id,
        'from_department_id' => $item->department_id,
        'to_department_id' => $item->department_id,
        'from_room_id' => $item->room_id,
        'to_room_id' => $item->room_id,
        'from_status' => $item->status,
        'to_status' => $item->status,
    ]);

    expect($log->from_location_id)->toBe($item->location_id)
        ->and($log->to_location_id)->toBe($otherLocation->id)
        ->and($log->from_department_id)->toBeNull()
        ->and($log->to_department_id)->toBeNull()
        ->and($log->from_room_id)->toBeNull()
        ->and($log->to_room_id)->toBeNull()
        ->and($log->from_status)->toBeNull()
        ->and($log->to_status)->toBeNull();

    $item->refresh();

    expect($item->location_id)->toBe($otherLocation->id)
        ->and($item->department_id)->not->toBeNull()
        ->and($item->room_id)->not->toBeNull()
        ->and($item->status)->toBe(ItemStatus::Active);
});

test('state log keeps a destination that differs from the item current value', function () {
    ['item' => $item, 'otherDepartment' => $otherDepartment] = createStateLogItem();
    $currentLocationId = $item->location_id;

    $log = ItemStateLog::create([
        'item_id' => $item->id,
        'event_type' => ItemStateEventType::Transfer,
        'from_location_id' => $currentLocationId,
        'to_location_id' => $currentLocationId,
        'from_department_id' => $item->department_id,
        'to_department_id' => $otherDepartment->id,
    ]);

    expect($log->from_location_id)->toBeNull()
        ->and($log->to_location_id)->toBeNull()
        ->and($log->from_department_id)->toBe($item->department_id)
        ->and($log->to_department_id)->toBe($otherDepartment->id);

    $item->refresh();

    expect($item->location_id)->toBe($currentLocationId)
        ->and($item->department_id)->toBe($otherDepartment->id);
});
