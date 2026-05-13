<?php

namespace App\Filament\Resources\Depreciations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepreciationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('depreciation.table.name'))
                    ->searchable(),
                TextColumn::make('months')
                    ->label(__('depreciation.table.months'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_value')
                    ->label(__('depreciation.table.minimum_value'))
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('method')
                    ->label(__('depreciation.table.method')),
                TextColumn::make('created_at')
                    ->label(__('depreciation.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('depreciation.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
