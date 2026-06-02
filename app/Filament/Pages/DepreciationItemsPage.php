<?php

namespace App\Filament\Pages;

use App\Enums\ItemStatus;
use App\Filament\Exports\DepreciationItemExporter;
use App\Models\Item;
use BackedEnum;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\ExportAction;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class DepreciationItemsPage extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    use HasPageShield;

    protected string $view = 'filament.pages.depreciation-items-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    public static function getNavigationLabel(): string
    {
        return __('items.pages.depreciation_items.navigation_label');
    }

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {
                return
                    Item::query()
                        ->where(function (Builder $query) {
                            $query->whereNotNull('purchase_date')
                                ->whereNotNull('purchase_price')
                                ->whereNotIn('status', [
                                    ItemStatus::Disposed,
                                    ItemStatus::Archived,
                                    ItemStatus::Lost,
                                    ItemStatus::Stolen,
                                ]);
                        })
                        ->whereHas('model', function (Builder $query) {
                            $query->whereHas('depreciation');
                        });
            })
            ->defaultSort('purchase_date', direction: 'asc')
            ->columns([
                TextColumn::make('serial_number')
                    ->label(__('items.table.serial_number'))
                    ->searchable(),
                TextColumn::make('model.name')
                    ->label(__('items.table.model'))
                    ->searchable(),
                TextColumn::make('model.category.name')
                    ->label(__('items.table.category'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('items.table.status'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('purchase_date')
                    ->label(__('items.table.purchase_date'))
                    ->date('d M Y'),
                TextColumn::make('eol_date')
                    ->label(__('items.table.eol_date'))
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('warranty_months')
                    ->label(__('items.table.warranty_months'))
                    ->numeric()
                    ->alignCenter()
                    ->suffix(__('items.table.warranty_suffix'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('purchase_price')
                    ->label(__('items.table.purchase_price'))
                    ->money('IDR', locale: 'id', decimalPlaces: 0),
                TextColumn::make('minimum_value')
                    ->label(__('items.pages.depreciation_items.minimum_value'))
                    ->state(function (Item $record): string {
                        return round($record->purchase_price * ($record->model?->depreciation?->minimum_value / 100), 0);
                    })
                    ->suffix(function (Item $record): string {
                        return ' ('.round($record->model?->depreciation?->minimum_value, 0).'%)';
                    })
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('depreciated_price')
                    ->label(__('items.pages.depreciation_items.depreciated_price'))
                    ->state(fn (Item $record) => $record->depreciated_price)
                    ->money('IDR', locale: 'id', decimalPlaces: 0),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(ItemStatus::class),
                SelectFilter::make('model_name')
                    ->label(__('items.table.model'))
                    ->relationship('model', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(DepreciationItemExporter::class)
                    ->label(__('items.pages.depreciation_items.export'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->fileDisk('public'),
            ]);
    }
}
