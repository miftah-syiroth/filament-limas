<?php

use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

uses(TestCase::class);

it('allows stock movements that keep the balance at or above zero', function () {
    $query = Mockery::mock(HasMany::class);
    $query->shouldReceive('sum')->with('quantity')->andReturn(20);

    $item = Mockery::mock(Item::class)->makePartial();
    $item->shouldReceive('stockMovements')->andReturn($query);

    expect($item->stockMovementBalance())->toBe(20)
        ->and($item->canApplyStockMovement(5))->toBeTrue()
        ->and($item->canApplyStockMovement(-15))->toBeTrue()
        ->and($item->canApplyStockMovement(-20))->toBeTrue();
});

it('rejects stock movements that would make the balance negative', function () {
    $query = Mockery::mock(HasMany::class);
    $query->shouldReceive('sum')->with('quantity')->andReturn(20);

    $item = Mockery::mock(Item::class)->makePartial();
    $item->shouldReceive('stockMovements')->andReturn($query);

    expect($item->canApplyStockMovement(-21))->toBeFalse()
        ->and($item->canApplyStockMovement(-25))->toBeFalse();
});
