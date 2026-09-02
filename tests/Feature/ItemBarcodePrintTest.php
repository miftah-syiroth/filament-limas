<?php

use App\Models\Item;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('renders a pdf for selected item barcodes', function (): void {
    $user = User::factory()->create();
    $organization = Organization::create(['name' => 'Test Organization']);
    $location = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Test Location',
    ]);
    $model = InventoryModel::create([
        'name' => 'Test Model',
    ]);
    $item = Item::create([
        'model_id' => $model->id,
        'location_id' => $location->id,
        'serial_number' => 'ABC12345',
    ]);

    actingAs($user);

    $response = get(route('items.barcodes.print', [
        'items' => $item->id,
    ]));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
    expect($response->getContent())->toStartWith('%PDF');
});
