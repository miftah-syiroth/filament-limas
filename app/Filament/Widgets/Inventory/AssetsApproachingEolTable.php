<?php

namespace App\Filament\Widgets\Inventory;

use App\Filament\Resources\Items\ItemResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Item;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AssetsApproachingEolTable extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 51;

    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:Item');
    // }

    protected int|string|array $columnSpan = [
        'lg' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('dashboard.eol.table'))
            ->query(
                Item::query()
                    ->inInventory()
                    ->whereNotNull('eol_date')
                    ->whereBetween('eol_date', [now(), now()->addDays(90)])
                    ->with(['model', 'location'])
                    ->orderBy('eol_date')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('serial_number')
                    ->label(__('items.table.serial_number'))
                    ->url(fn (Item $record): string => ItemResource::getUrl('view', ['record' => $record])),
                TextColumn::make('model.name')
                    ->label(__('items.table.model')),
                TextColumn::make('eol_date')
                    ->label(__('items.table.eol_date'))
                    ->date('d M Y'),
                TextColumn::make('location.name')
                    ->label(__('items.table.location'))
                    ->placeholder('-'),
            ])
            ->paginated(false);
    }
}
