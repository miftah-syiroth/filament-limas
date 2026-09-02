<?php

namespace App\Filament\Resources\Depreciations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

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
                    ->numeric(),
                TextColumn::make('minimum_value')
                    ->label(__('depreciation.table.minimum_value'))
                    ->numeric()
                    ->suffix('%'),
                TextColumn::make('method')
                    ->label(__('depreciation.table.method')),
                TextColumn::make('models_count')
                    ->label(__('depreciation.table.models_count'))
                    ->counts('models'),
                TextColumn::make('created_at')
                    ->label(__('depreciation.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
