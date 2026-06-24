<?php

namespace App\Filament\Widgets\Stock;

use App\Enums\CategoryType;
use App\Filament\Resources\Models\ModelResource;
use App\Filament\Widgets\Concerns\InteractsWithDashboard;
use App\Models\Model;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CriticalConsumableTable extends BaseWidget
{
    use InteractsWithDashboard;

    protected static ?int $sort = 82;


    protected int|string|array $columnSpan = [
        'lg' => 2,
    ];
    
    // public static function canView(): bool
    // {
    //     return static::canViewShield('ViewAny:Model');
    // }

    public function table(Table $table): Table
    {
        $ids = static::modelsBelowMinAmount(CategoryType::Consumable, includeAtMinimum: true)->pluck('id');

        return $table
            ->heading(__('dashboard.stock.critical_table'))
            ->query(
                Model::query()
                    ->whereIn('id', $ids)
                    ->withSum('itemsInInventory as total_quantity', 'quantity')
                    ->orderBy('name')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label(__('model.table.name'))
                    ->url(fn (Model $record): string => ModelResource::getUrl('view', ['record' => $record])),
                TextColumn::make('total_quantity')
                    ->label(__('dashboard.stock.stock'))
                    ->numeric()
                    ->default(0),
                TextColumn::make('min_amount')
                    ->label(__('dashboard.stock.minimum'))
                    ->numeric(),
                TextColumn::make('difference')
                    ->label(__('dashboard.stock.difference'))
                    ->state(fn (Model $record): int => (int) ($record->total_quantity ?? 0) - (int) $record->min_amount)
                    ->numeric()
                    ->color('danger'),
            ])
            ->paginated(false);
    }
}
