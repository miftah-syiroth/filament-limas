<?php

namespace App\Filament\Exports;

use App\Models\BorrowingItem;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class BorrowingItemExporter extends Exporter
{
    protected static ?string $model = BorrowingItem::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('borrowing.id'),
            ExportColumn::make('item.name'),
            ExportColumn::make('quantity'),
            ExportColumn::make('checked_out_at'),
            ExportColumn::make('checked_in_at'),
            ExportColumn::make('condition_in'),
            ExportColumn::make('condition_out'),
            ExportColumn::make('notes'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your borrowing item export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
