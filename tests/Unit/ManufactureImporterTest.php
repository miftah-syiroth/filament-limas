<?php

use App\Filament\Imports\ManufactureImporter;
use Filament\Actions\Imports\ImportColumn;

it('does not apply url or email validation to optional string columns', function (): void {
    $columns = ManufactureImporter::getColumns();
    $optionalNames = ['url', 'support_url', 'support_phone', 'support_email', 'warranty_lookup_url'];

    foreach ($optionalNames as $name) {
        $column = collect($columns)->first(
            fn (ImportColumn $c): bool => $c->getName() === $name
        );

        expect($column)->not->toBeNull();
        $rules = $column->getDataValidationRules();

        expect(in_array('url', $rules, true))->toBeFalse();
        expect(in_array('email', $rules, true))->toBeFalse();
        expect($rules)->toContain('max:255');
    }
});
