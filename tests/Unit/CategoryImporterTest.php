<?php

use App\Filament\Imports\CategoryImporter;
use Filament\Actions\Imports\ImportColumn;

it('exposes category type enum values as import column examples', function (): void {
    $columns = CategoryImporter::getColumns();
    $typeColumn = collect($columns)->first(
        fn (ImportColumn $column): bool => $column->getName() === 'type'
    );

    expect($typeColumn)->not->toBeNull()
        ->getExamples()->toBe(['asset', 'accessory', 'consumable', 'license']);
});
