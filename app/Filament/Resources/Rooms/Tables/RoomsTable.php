<?php

namespace App\Filament\Resources\Rooms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('room.table.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location.name')
                    ->label(__('room.table.location')),
                TextColumn::make('capacity')
                    ->label(__('room.table.capacity'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('items_count')
                    ->label(__('room.table.items_count'))
                    ->counts('items')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('room.table.created_at'))
                    ->dateTime(format: 'j M Y H:i:s', timezone: 'Asia/Jakarta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
            ])
            ->filters([
                SelectFilter::make('location_name')
                    ->label(__('room.table.location'))
                    ->relationship('location', 'name')
                    ->preload()
                    ->native(false),
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
