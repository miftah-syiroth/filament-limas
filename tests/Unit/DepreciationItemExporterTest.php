<?php

use App\Filament\Exports\DepreciationItemExporter;
use App\Models\Item;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Carbon;
use Tests\TestCase;

uses(TestCase::class);

test('depreciation item exporter formats dates and money for export', function (): void {
    $item = new Item;
    $item->purchase_date = Carbon::parse('2028-01-23 00:00:00');
    $item->purchase_price = '21000000.00';

    $export = new Export;
    $columnMap = [
        'purchase_date' => 'Tanggal Pembelian',
        'purchase_price' => 'Harga Pembelian',
    ];

    $exporter = new DepreciationItemExporter($export, $columnMap, []);

    expect($exporter($item))
        ->toBe(['2028-01-23', '21000000']);
});

test('depreciation item exporter columns use state for calculated depreciated price', function (): void {
    $columns = DepreciationItemExporter::getColumns();

    $depreciatedPriceColumn = collect($columns)
        ->first(fn (ExportColumn $column): bool => $column->getName() === 'depreciated_price');

    expect($depreciatedPriceColumn->getGetStateUsingCallback())->not->toBeNull();
});
