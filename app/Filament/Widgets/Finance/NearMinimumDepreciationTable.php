<?php

namespace App\Filament\Widgets\Finance;

use App\Enums\ItemStatus;
use App\Filament\Resources\Items\ItemResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use App\Support\DepreciationCalculator;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class NearMinimumDepreciationTable extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 92;

    protected int|string|array $columnSpan = [
        'lg' => 2,
    ];

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:Item');
    // }

    public function table(Table $table): Table
    {
        $ids = Item::query()
            ->where('status', ItemStatus::Active)
            ->whereNotNull('purchase_price')
            ->whereHas('model.depreciation')
            ->with(['model.depreciation'])
            ->get()
            ->filter(function (Item $item): bool {
                $minimumValue = DepreciationCalculator::minimumValue($item);

                if ($minimumValue === null || $item->depreciated_price === null) {
                    return false;
                }

                return $item->depreciated_price <= ($minimumValue * 1.1);
            })
            ->pluck('id');

        return $table
            ->heading(__('dashboard.finance.near_minimum_table'))
            ->query(
                Item::query()
                    ->whereIn('id', $ids)
                    ->with(['model'])
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('serial_number')
                    ->label(__('items.table.serial_number'))
                    ->url(fn (Item $record): string => ItemResource::getUrl('view', ['record' => $record])),
                TextColumn::make('model.name')
                    ->label(__('items.table.model')),
                TextColumn::make('purchase_price')
                    ->label(__('items.table.purchase_price'))
                    ->money('IDR', locale: 'id', decimalPlaces: 0),
                TextColumn::make('depreciated_price')
                    ->label(__('items.pages.depreciation_items.depreciated_price'))
                    ->state(fn (Item $record) => $record->depreciated_price)
                    ->money('IDR', locale: 'id', decimalPlaces: 0),
                TextColumn::make('minimum_value')
                    ->label(__('dashboard.finance.minimum_value'))
                    ->state(fn (Item $record) => DepreciationCalculator::minimumValue($record))
                    ->money('IDR', locale: 'id', decimalPlaces: 0),
            ])
            ->paginated(false)
            ->headerActions([]);
    }
}
