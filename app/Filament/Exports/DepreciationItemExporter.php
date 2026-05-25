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
            ExportColumn::make('serial_number')
                ->label(__('items.table.serial_number')),
            ExportColumn::make('model.name')
                ->label(__('items.table.model')),
            ExportColumn::make('model.category.name')
                ->label(__('items.table.category')),
            ExportColumn::make('status')
                ->label(__('items.table.status'))
                ->formatStateUsing(fn (mixed $state): ?string => $state instanceof ItemStatus ? $state->getLabel() : (filled($state) ? (string) $state : null)),
            ExportColumn::make('purchase_date')
                ->label(__('items.table.purchase_date'))
                ->formatStateUsing(fn (mixed $state): ?string => filled($state) ? Carbon::parse($state)->format('Y-m-d') : null),
            ExportColumn::make('eol_date')
                ->label(__('items.table.eol_date'))
                ->formatStateUsing(fn (mixed $state): ?string => filled($state) ? Carbon::parse($state)->format('Y-m-d') : null),
            ExportColumn::make('warranty_months')
                ->label(__('items.table.warranty_months')),
            ExportColumn::make('purchase_price')
                ->label(__('items.table.purchase_price'))
                ->formatStateUsing(fn (mixed $state): ?int => filled($state) ? (int) round((float) $state, 0) : null),
            ExportColumn::make('minimum_value')
                ->label(__('items.pages.depreciation_items.minimum_value'))
                ->state(function (Item $record): ?int {
                    if ($record->purchase_price === null || $record->model?->depreciation?->minimum_value === null) {
                        return null;
                    }

                    return (int) round($record->purchase_price * ($record->model->depreciation->minimum_value / 100), 0);
                }),
            ExportColumn::make('depreciated_price')
                ->label(__('items.pages.depreciation_items.depreciated_price'))
                ->state(fn (Item $record): ?int => $record->depreciated_price === null ? null : (int) round($record->depreciated_price, 0)),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __('items.pages.depreciation_items.export_completed', [
            'count' => Number::format($export->successful_rows),
        ]);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= __('items.pages.depreciation_items.export_failed', [
                'count' => Number::format($failedRowsCount),
            ]);
        }

        return $body;
    }
}
