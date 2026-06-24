<?php

namespace App\Filament\Resources\Locations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label(__('location.table.name'))
                    ->searchable(),
                TextColumn::make('organization.name')
                    ->label(__('location.table.organization'))
                    ->searchable(),
                TextColumn::make('address')
                    ->label(__('location.table.address'))
                    ->limit(50),
                TextColumn::make('address2')
                    ->label(__('location.table.address2'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('relationCity.name')
                    ->label(__('location.table.city'))
                    ->searchable(),
                TextColumn::make('relationProvince.name')
                    ->label(__('location.table.province'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('relationCountry.name')
                    ->label(__('location.table.country'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('zip')
                    ->label(__('location.table.zip'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label(__('location.table.phone'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('items_count')
                    ->label(__('location.table.items_count'))
                    ->counts('items'),
                TextColumn::make('created_at')
                    ->label(__('location.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->authorizeIndividualRecords('delete')
                    ->action(fn(Collection $records) => $records->each->delete()),
                ]),
            ]);
    }
}
