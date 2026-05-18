<?php

use App\Enums\ItemStatus;
use Tests\TestCase;

uses(TestCase::class);

it('excludes the item current status from to_status options', function () {
    $currentStatus = ItemStatus::Active;

    $options = collect(ItemStatus::cases())
        ->reject(fn (ItemStatus $status): bool => $status === $currentStatus)
        ->mapWithKeys(fn (ItemStatus $status): array => [$status->value => $status->getLabel()])
        ->all();

    expect($options)->not->toHaveKey(ItemStatus::Active->value)
        ->and($options)->toHaveCount(count(ItemStatus::cases()) - 1)
        ->and(array_keys($options))->toContain(ItemStatus::UnderRepair->value);
});
