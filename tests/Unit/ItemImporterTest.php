<?php

use App\Filament\Imports\ItemImporter;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

it('validates purchase_date as nullable date', function (): void {
    $column = collect(ItemImporter::getColumns())->first(
        fn (ImportColumn $c): bool => $c->getName() === 'purchase_date'
    );

    expect($column)->not->toBeNull();
    expect($column->getDataValidationRules())->toContain('date')
        ->not->toContain('datetime');
});

it('fills blank serial_number during castData', function (): void {
    $import = Mockery::mock(Import::class);
    $importer = new ItemImporter($import, [], []);

    $reflection = new ReflectionClass($importer);
    $dataProperty = $reflection->getProperty('data');
    $dataProperty->setAccessible(true);
    $dataProperty->setValue($importer, ['serial_number' => null]);

    $castData = $reflection->getMethod('castData');
    $castData->setAccessible(true);
    $castData->invoke($importer);

    $serial = $dataProperty->getValue($importer)['serial_number'];

    expect($serial)->toBeString()
        ->and($serial)->toHaveLength(8);
});

it('fills missing serial_number key during castData', function (): void {
    $import = Mockery::mock(Import::class);
    $importer = new ItemImporter($import, [], []);

    $reflection = new ReflectionClass($importer);
    $dataProperty = $reflection->getProperty('data');
    $dataProperty->setAccessible(true);
    $dataProperty->setValue($importer, ['name' => 'only-other-field']);

    $castData = $reflection->getMethod('castData');
    $castData->setAccessible(true);
    $castData->invoke($importer);

    expect($dataProperty->getValue($importer))->toHaveKey('serial_number')
        ->and($dataProperty->getValue($importer)['serial_number'])->toHaveLength(8);
});

it('preserves non-blank serial_number during castData', function (): void {
    $import = Mockery::mock(Import::class);
    $importer = new ItemImporter($import, [], []);

    $reflection = new ReflectionClass($importer);
    $dataProperty = $reflection->getProperty('data');
    $dataProperty->setAccessible(true);
    $dataProperty->setValue($importer, ['serial_number' => 'KEEP-123']);

    $castData = $reflection->getMethod('castData');
    $castData->setAccessible(true);
    $castData->invoke($importer);

    expect($dataProperty->getValue($importer)['serial_number'])->toBe('KEEP-123');
});
