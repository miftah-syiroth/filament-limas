<?php

namespace App\Filament\Resources\Items\Tables;

use App\Enums\ItemStatus;
use App\Filament\Exports\ItemExporter;
use App\Filament\Imports\ItemImporter;
use App\Filament\Resources\Models\ModelResource;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', direction: 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('')
                    ->limit(1),
                TextColumn::make('serial_number')
                    ->label(__('items.table.serial_number'))
                    ->searchable(),
                TextColumn::make('model.category.name')
                    ->label(__('items.table.category')),
                TextColumn::make('model.manufacture.name')
                    ->label(__('items.table.manufacturer')),
                TextColumn::make('model.name')
                    ->label(__('items.table.model'))
                    ->searchable()
                    ->url(fn (Model $record): string => ModelResource::getUrl('view', ['record' => $record->model])),
                TextColumn::make('model.category.type')
                    ->label(__('items.table.type'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('items.table.name'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location.name')
                    ->label(__('items.table.location'))
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('department.name')
                    ->label(__('items.table.department'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('room.name')
                    ->label(__('items.table.room'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('supplier.name')
                    ->label(__('items.table.supplier'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('items.table.status'))
                    ->badge(),
                TextColumn::make('quantity')
                    ->label(__('items.table.quantity'))
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('borrowable')
                    ->label(__('items.table.borrowable_quantity'))
                    ->state(function (Model $record): int {
                        return max(0, $record->quantity - $record->activeBorrowingItems->sum('quantity'));
                    })
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('purchase_date')
                    ->label(__('items.table.purchase_date'))
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('purchase_price')
                    ->label(__('items.table.purchase_price'))
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('eol_date')
                    ->label(__('items.table.eol_date'))
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('warranty_months')
                    ->label(__('items.table.warranty_months'))
                    ->numeric()
                    ->alignCenter()
                    ->suffix(__('items.table.warranty_suffix'))
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_individual_tracking')
                    ->label(__('items.table.individual'))
                    ->alignCenter()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('items.table.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(ItemStatus::class),
                SelectFilter::make('category_name')
                    ->label(__('items.table.category'))
                    ->relationship('model.category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('manufacturer_name')
                    ->label(__('items.table.manufacturer'))
                    ->relationship('model.manufacture', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('model_name')
                    ->label(__('items.table.model'))
                    ->relationship(
                        'model',
                        'name'
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('department_name')
                    ->label(__('items.table.department'))
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('location_name')
                    ->label(__('items.table.location'))
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('room_name')
                    ->label(__('items.table.room'))
                    ->relationship('room', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('supplier_name')
                    ->label(__('items.table.supplier'))
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make()
                    ->native(false),
            ])
            ->filtersFormColumns(3)
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ItemExporter::class)
                    ->label(__('items.table.export'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->fileDisk('public'),
                ImportAction::make()
                    ->importer(ItemImporter::class)
                    ->label(__('items.table.import'))
                    ->icon(Heroicon::OutlinedArrowUpTray),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('barcodeprint')
                        ->action(),
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn (Collection $records) => $records->each->delete()),
                    ForceDeleteBulkAction::make()
                        ->authorizeIndividualRecords('forceDelete')
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                ]),
            ]);
    }
}
