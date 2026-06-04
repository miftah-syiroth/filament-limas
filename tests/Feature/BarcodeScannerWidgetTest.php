<?php

use App\Enums\ItemStatus;
use App\Filament\Widgets\BarcodeScanner;
use App\Models\Item;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use App\Models\User;
use Livewire\Livewire;

test('applyScannedSerialNumber truncates scanned value to eight characters', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(BarcodeScanner::class)
        ->call('applyScannedSerialNumber', 'abcdefgh123')
        ->assertSet('serial_number', 'ABCDEFGH');
});

test('applyScannedSerialNumber shows notification for invalid serial number length', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(BarcodeScanner::class)
        ->call('applyScannedSerialNumber', 'abc')
        ->assertSet('serial_number', 'ABC')
        ->assertNotified(__('barcode_scanner.invalid_serial_number'));
});

test('applyScannedSerialNumber finds item and opens modal', function () {
    $this->actingAs(User::factory()->create());

    $organization = Organization::create(['name' => 'Test Organization']);
    $location = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Test Location',
    ]);
    $model = InventoryModel::create(['name' => 'Test Model']);

    $item = Item::create([
        'model_id' => $model->id,
        'location_id' => $location->id,
        'serial_number' => 'ABCD1234',
        'quantity' => 1,
        'order_quantity' => 1,
        'status' => ItemStatus::Active,
        'is_individual_tracking' => true,
    ]);

    Livewire::test(BarcodeScanner::class)
        ->call('applyScannedSerialNumber', 'abcd1234')
        ->assertSet('serial_number', 'ABCD1234')
        ->assertSet('scannedItem.id', $item->id)
        ->assertActionMounted('viewScannedItem');
});

test('updatedSerialNumber searches when eight characters are entered', function () {
    $this->actingAs(User::factory()->create());

    $organization = Organization::create(['name' => 'Test Organization']);
    $location = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Test Location',
    ]);
    $model = InventoryModel::create(['name' => 'Test Model']);

    $item = Item::create([
        'model_id' => $model->id,
        'location_id' => $location->id,
        'serial_number' => 'WXYZ9876',
        'quantity' => 1,
        'order_quantity' => 1,
        'status' => ItemStatus::Active,
        'is_individual_tracking' => true,
    ]);

    Livewire::test(BarcodeScanner::class)
        ->set('serial_number', 'wxyz9876')
        ->assertSet('serial_number', 'WXYZ9876')
        ->assertSet('scannedItem.id', $item->id)
        ->assertActionMounted('viewScannedItem');
});
