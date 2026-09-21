<?php

use App\Enums\ItemStateEventType;
use App\Enums\ItemStatus;
use App\Filament\Resources\Items\Pages\ManageItemStateLogs;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemStateLog;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use App\Models\Room;
use Filament\Actions\CreateAction;
use Filament\Actions\Exceptions\Cancel;

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

/**
 * @param  array<string, mixed>  $data
 * @return array<string, mixed>
 */
function invokeNullifyFromWhenToIsNull(array $data): array
{
    $page = new ManageItemStateLogs;
    $method = new ReflectionMethod(ManageItemStateLogs::class, 'nullifyFromWhenToIsNull');
    $method->setAccessible(true);

    return $method->invoke($page, $data, CreateAction::make());
}

test('nullify clears matching transfer destinations on from and to', function () {
    ['item' => $item, 'otherLocation' => $otherLocation] = createStateLogItem();

    $data = invokeNullifyFromWhenToIsNull([
        'event_type' => ItemStateEventType::Transfer,
        'from_location_id' => $item->location_id,
        'to_location_id' => $otherLocation->id,
        'from_department_id' => $item->department_id,
        'to_department_id' => $item->department_id,
        'from_room_id' => $item->room_id,
        'to_room_id' => $item->room_id,
    ]);

    expect($data['from_location_id'])->toBe($item->location_id)
        ->and($data['to_location_id'])->toBe($otherLocation->id)
        ->and($data['from_department_id'])->toBeNull()
        ->and($data['to_department_id'])->toBeNull()
        ->and($data['from_room_id'])->toBeNull()
        ->and($data['to_room_id'])->toBeNull();

    $log = ItemStateLog::create([
        'item_id' => $item->id,
        ...$data,
    ]);

    expect($log->from_location_id)->toBe($item->location_id)
        ->and($log->to_location_id)->toBe($otherLocation->id)
        ->and($log->from_department_id)->toBeNull()
        ->and($log->to_department_id)->toBeNull()
        ->and($log->from_room_id)->toBeNull()
        ->and($log->to_room_id)->toBeNull();

    $item->refresh();

    expect($item->location_id)->toBe($otherLocation->id)
        ->and($item->department_id)->not->toBeNull()
        ->and($item->room_id)->not->toBeNull()
        ->and($item->status)->toBe(ItemStatus::Active);
});

test('nullify keeps a destination that differs and clears matching from and to', function () {
    ['item' => $item, 'otherDepartment' => $otherDepartment] = createStateLogItem();
    $currentLocationId = $item->location_id;

    $data = invokeNullifyFromWhenToIsNull([
        'event_type' => ItemStateEventType::Transfer,
        'from_location_id' => $currentLocationId,
        'to_location_id' => $currentLocationId,
        'from_department_id' => $item->department_id,
        'to_department_id' => $otherDepartment->id,
    ]);

    expect($data['from_location_id'])->toBeNull()
        ->and($data['to_location_id'])->toBeNull()
        ->and($data['from_department_id'])->toBe($item->department_id)
        ->and($data['to_department_id'])->toBe($otherDepartment->id);

    $log = ItemStateLog::create([
        'item_id' => $item->id,
        ...$data,
    ]);

    expect($log->from_location_id)->toBeNull()
        ->and($log->to_location_id)->toBeNull()
        ->and($log->from_department_id)->toBe($item->department_id)
        ->and($log->to_department_id)->toBe($otherDepartment->id);

    $item->refresh();

    expect($item->location_id)->toBe($currentLocationId)
        ->and($item->department_id)->toBe($otherDepartment->id);
});

test('nullify cancels create when all transfer destinations are unchanged', function () {
    ['item' => $item] = createStateLogItem();

    expect(fn () => invokeNullifyFromWhenToIsNull([
        'event_type' => ItemStateEventType::Transfer,
        'from_location_id' => $item->location_id,
        'to_location_id' => $item->location_id,
        'from_department_id' => $item->department_id,
        'to_department_id' => $item->department_id,
        'from_room_id' => $item->room_id,
        'to_room_id' => $item->room_id,
    ]))->toThrow(Cancel::class);
});
