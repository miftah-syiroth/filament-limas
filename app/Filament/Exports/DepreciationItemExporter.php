<?php

namespace App\Filament\Exports;

use App\Enums\ItemStatus;
use App\Models\Item;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class DepreciationItemExporter extends Exporter
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('serial_number'),
            ExportColumn::make('model.name')
                ->label('Model'),
            ExportColumn::make('model.category.name')
                ->label('Kategori'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn(mixed $state): ?string => $state instanceof ItemStatus ? $state->getLabel() : (filled($state) ? (string) $state : null)),
            ExportColumn::make('purchase_date')
                ->label('Tanggal Pembelian')
                ->formatStateUsing(fn(mixed $state): ?string => filled($state) ? Carbon::parse($state)->format('Y-m-d') : null),
            ExportColumn::make('eol_date')
                ->label('Tanggal Kadaluarsa')
                ->formatStateUsing(fn(mixed $state): ?string => filled($state) ? Carbon::parse($state)->format('Y-m-d') : null),
            ExportColumn::make('warranty_months')
                ->label('Garansi'),
            ExportColumn::make('purchase_price')
                ->label('Harga Pembelian')
                ->formatStateUsing(fn(mixed $state): ?int => filled($state) ? (int) round((float) $state, 0) : null),
            ExportColumn::make('minimum_value')
                ->label('Harga Minimum')
                ->state(function (Item $record): ?int {
                    if ($record->purchase_price === null || $record->model?->depreciation?->minimum_value === null) {
                        return null;
                    }

                    return (int) round($record->purchase_price * ($record->model->depreciation->minimum_value / 100), 0);
                }),
            ExportColumn::make('depreciated_price')
                ->label('Harga Sekarang')
                ->state(fn(Item $record): ?int => $record->depreciated_price === null ? null : (int) round($record->depreciated_price, 0)),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your depreciation item export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
