<?php

namespace App\Filament\Resources\Departments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label(__('department.table.name'))
                    ->searchable(),
                TextColumn::make('organization.name')
                    ->label(__('department.table.organization')),
                TextColumn::make('locations.name')
                    ->label(__('department.table.location'))
                    ->badge()
                    ->separator(','),
                TextColumn::make('phone')
                    ->label(__('department.table.phone')),
                TextColumn::make('items_count')
                    ->label(__('department.table.items_count'))
                    ->counts('items'),
                TextColumn::make('created_at')
                    ->label(__('department.table.created_at'))
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
