<?php

namespace App\Filament\Resources\Items\Tables;

use App\Models\Item;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', direction: 'desc')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withSum(
                ['borrowingItems as borrowing_items_sum_quantity' => fn (Builder $q): Builder => $q->whereNull('checked_in_at')],
                'quantity'
            ))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->hidden(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('model.name')
                    ->searchable(),
                TextColumn::make('model.category.name'),
                TextColumn::make('model.category.type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('quantity')
                    ->label('Total')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('available_quantity')
                    ->label('Tersedia')
                    ->state(fn (Item $record): int|float => $record->quantity - ((float) ($record->borrowing_items_sum_quantity ?? 0)))
                    ->numeric()
                    ->alignCenter(),
                IconColumn::make('is_individual_tracking')
                    ->label('Individu')
                    ->alignCenter()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name'),
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
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
