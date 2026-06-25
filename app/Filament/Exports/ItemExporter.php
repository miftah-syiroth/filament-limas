<?php

namespace App\Filament\Exports;

use App\Models\Item;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class ItemExporter extends Exporter
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
            ExportColumn::make('model.category.type')
                ->label(__('items.table.type')),
            ExportColumn::make('name')
                ->label(__('items.table.name')),
            ExportColumn::make('location.name')
                ->label(__('items.table.location')),
            ExportColumn::make('department.name')
                ->label(__('items.table.department')),
            ExportColumn::make('room.name')
                ->label(__('items.table.room')),
            ExportColumn::make('supplier.name')
                ->label(__('items.table.supplier')),
            ExportColumn::make('user.name')
                ->label(__('items.table.user')),
            ExportColumn::make('status')
                ->label(__('items.table.status')),
            ExportColumn::make('quantity')
                ->label(__('items.table.quantity')),
            ExportColumn::make('purchase_date')
                ->label(__('items.table.purchase_date'))
                ->formatStateUsing(fn(mixed $state): ?string => filled($state) ? Carbon::parse($state)->format('Y-m-d') : null),
            ExportColumn::make('purchase_price')
                ->label(__('items.table.purchase_price'))
                ->state(fn(Item $record): ?int => $record->purchase_price === null ? null : (int) round($record->purchase_price, 0)),
            ExportColumn::make('eol_date')
                ->label(__('items.table.eol_date'))
                ->formatStateUsing(fn(mixed $state): ?string => filled($state) ? Carbon::parse($state)->format('Y-m-d') : null),
            ExportColumn::make('warranty_months')
                ->label(__('items.table.warranty_months')),
            ExportColumn::make('is_individual_tracking')
                ->label(__('items.table.individual')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
