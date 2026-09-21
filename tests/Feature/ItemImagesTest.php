<?php

use App\Enums\ItemStatus;
use App\Models\Item;
use App\Models\Location;
use App\Models\Model as InventoryModel;
use App\Models\Organization;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @return array{model: InventoryModel, item: Item}
 */
function createItemWithModel(): array
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
        'quantity' => 1,
        'status' => ItemStatus::Active,
    ]);

    return compact('model', 'item');
}

test('item images use the item media when it exists', function () {
    Storage::fake('public');

    ['model' => $model, 'item' => $item] = createItemWithModel();

    $model->addMedia(UploadedFile::fake()->image('model.jpg'))->toMediaCollection();
    $item->addMedia(UploadedFile::fake()->image('item.jpg'))->toMediaCollection();

    $item->unsetRelation('media');

    expect($item->images)->toBe([
        $item->getFirstMediaUrl(),
    ]);
});

test('item images fall back to the related model media', function () {
    Storage::fake('public');

    ['model' => $model, 'item' => $item] = createItemWithModel();

    $model->addMedia(UploadedFile::fake()->image('model.jpg'))->toMediaCollection();

    expect($item->images)->toBe([
        $model->getFirstMediaUrl(),
    ]);
});

test('item images are empty when neither the item nor the model has media', function () {
    ['item' => $item] = createItemWithModel();

    expect($item->images)->toBe([]);
});
