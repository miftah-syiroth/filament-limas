<?php

namespace App\Filament\Widgets\Inventory;

use App\Filament\Resources\Items\ItemResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ExpiringWarrantyTable extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 53;

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
            ->inInventory()
            ->whereNotNull('purchase_date')
            ->whereNotNull('warranty_months')
            ->get()
            ->filter(function (Item $item): bool {
                $warrantyEnd = $item->purchase_date->copy()->addMonths($item->warranty_months);

                return $warrantyEnd->between(now(), now()->addDays(30));
            })
            ->pluck('id');

        return $table
            ->heading(__('dashboard.warranty.table'))
            ->query(
                Item::query()
                    ->whereIn('id', $ids)
                    ->with(['model', 'supplier'])
                    ->orderBy('purchase_date')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('serial_number')
                    ->label(__('items.table.serial_number'))
                    ->url(fn (Item $record): string => ItemResource::getUrl('view', ['record' => $record])),
                TextColumn::make('model.name')
                    ->label(__('items.table.model')),
                TextColumn::make('warranty_end')
                    ->label(__('dashboard.warranty.ends_at'))
                    ->state(fn (Item $record) => $record->purchase_date->copy()->addMonths($record->warranty_months))
                    ->date('d M Y'),
                TextColumn::make('supplier.name')
                    ->label(__('items.table.supplier'))
                    ->placeholder('-'),
            ])
            ->paginated(false);
    }
}
