<?php

use App\Support\ItemSerialNumber;

it('generates an eight character uppercase alphanumeric serial number', function (): void {
    $serial = ItemSerialNumber::generate();

    expect($serial)->toHaveLength(8)
        ->and($serial)->toBe(mb_strtoupper($serial))
        ->and(ctype_alnum($serial))->toBeTrue();
});

it('generates unique serial numbers', function (): void {
    $serials = collect(range(1, 50))->map(fn (): string => ItemSerialNumber::generate());

    expect($serials->unique()->count())->toBe(50);
});
