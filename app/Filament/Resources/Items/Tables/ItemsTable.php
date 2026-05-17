<?php

namespace App\Filament\Resources\Items\Tables;

use App\Enums\CategoryType;
use App\Enums\ItemStatus;
use App\Filament\Imports\ItemImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', direction: 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->limit(1),
                TextColumn::make('serial_number')
                    ->label(__('items.table.serial_number'))
                    ->searchable(),
                TextColumn::make('model.name')
                    ->label(__('items.form.model'))
                    ->searchable(),
                TextColumn::make('model.category.name')
                    ->label(__('items.form.category'))
                    ->searchable(),
                TextColumn::make('model.category.type')
                    ->label(__('items.table.type'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('items.table.name'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location.name')
                    ->label(__('items.form.location'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name')
                    ->label(__('items.form.department'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('room.name')
                    ->label(__('items.form.room'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('supplier.name')
                    ->label(__('items.form.supplier'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label(__('items.table.user'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('items.form.status'))
                    ->badge(),
                TextColumn::make('quantity')
                    ->label(__('items.form.quantity'))
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('order_quantity')
                    ->label(__('items.form.order_quantity'))
                    ->numeric()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('purchase_date')
                    ->label(__('items.form.purchase_date'))
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('purchase_price')
                    ->label(__('items.form.purchase_price'))
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('eol_date')
                    ->label(__('items.form.eol_date'))
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('warranty_months')
                    ->label(__('items.form.warranty_months'))
                    ->numeric()
                    ->alignCenter()
                    ->suffix(__('items.infolist.warranty_suffix'))
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_individual_tracking')
                    ->label(__('items.table.individual'))
                    ->alignCenter()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(ItemStatus::class),
                SelectFilter::make('type')
                    ->label(__('items.table.category_type'))
                    ->multiple()
                    ->options(CategoryType::class)
                    ->query(function (Builder $query, array $data): Builder {
                        $types = $data['values'] ?? [];
                        if (empty($types)) {
                            return $query;
                        }

                        return $query->whereHas('model', function (Builder $q) use ($types): Builder {
                            return $q->whereHas('category', fn (Builder $q): Builder => $q->whereIn('type', $types));
                        });
                    }),
                TernaryFilter::make('is_individual_tracking')
                    ->label(__('items.table.individual')),

            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(ItemImporter::class)
                    ->label(__('items.table.import'))
                    ->icon(Heroicon::OutlinedArrowUpTray),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
