<?php

use App\Models\Item;
use App\Models\Room;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

uses(TestCase::class);

test('room belongs to location', function (): void {
    $room = new Room;

    expect($room->getFillable())->toContain('location_id')
        ->and($room->location())->toBeInstanceOf(BelongsTo::class)
        ->and($room->location()->getForeignKeyName())->toBe('location_id');
});

test('item belongs to room', function (): void {
    $item = new Item;

    expect($item->getFillable())->toContain('room_id')
        ->and($item->room())->toBeInstanceOf(BelongsTo::class)
        ->and($item->room()->getForeignKeyName())->toBe('room_id');
});
