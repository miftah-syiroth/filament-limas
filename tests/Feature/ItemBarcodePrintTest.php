<?php

use App\Models\Item;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\User;
use App\Services\ItemBarcodeLabelGenerator;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

function createBarcodePrintUser(): User
{
    $user = User::factory()->create();

    Permission::findOrCreate('ViewAny:Item', 'web');
    $user->givePermissionTo('ViewAny:Item');

    return $user;
}

/**
 * @return array{0: Item, 1: InventoryModel, 2: Location}
 */
function createBarcodePrintItem(string $serialNumber = 'ABC12345'): array
{
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
        'serial_number' => $serialNumber,
    ]);

    return [$item, $model, $location];
}

test('guests cannot download item barcodes', function (): void {
    [$item] = createBarcodePrintItem();

    get(route('items.barcodes.print', [
        'items' => $item->id,
    ]))->assertRedirect();
});

test('downloads a png sheet for selected item barcodes', function (): void {
    $user = createBarcodePrintUser();
    [$item] = createBarcodePrintItem();

    actingAs($user);

    $response = get(route('items.barcodes.print', [
        'items' => $item->id,
    ]));

    $response->assertOk();
    $response->assertHeader('content-type', 'image/png');
    expect($response->streamedContent())->toStartWith("\x89PNG");
});

test('downloads a zip of png sheets when more than forty items are selected', function (): void {
    $user = createBarcodePrintUser();
    $organization = Organization::create(['name' => 'Zip Organization']);
    $location = Location::create([
        'organization_id' => $organization->id,
        'name' => 'Zip Location',
    ]);
    $model = InventoryModel::create([
        'name' => 'Zip Model',
    ]);

    $itemIds = collect(range(1, 41))->map(function (int $index) use ($model, $location): string {
        return Item::create([
            'model_id' => $model->id,
            'location_id' => $location->id,
            'serial_number' => sprintf('SN%06d', $index),
        ])->id;
    });

    actingAs($user);

    $response = get(route('items.barcodes.print', [
        'items' => $itemIds->implode(','),
    ]));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/zip');
    expect($response->headers->get('content-disposition'))->toContain('item-barcodes.zip');
});

test('renders a single barcode label at fifty by twenty five millimeters', function (): void {
    [$item] = createBarcodePrintItem('LABEL001');
    $item->load('model');

    $generator = app(ItemBarcodeLabelGenerator::class);
    $png = $generator->renderLabel($item);
    $size = getimagesizefromstring($png);

    expect($size)->not->toBeFalse()
        ->and($size[0])->toBe($generator->labelWidthPx())
        ->and($size[1])->toBe($generator->labelHeightPx())
        ->and($generator->labelWidthPx())->toBe(591)
        ->and($generator->labelHeightPx())->toBe(295);
});
