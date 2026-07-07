<?php

namespace App\Filament\Resources\Borrowings\Tables;

use App\Enums\BorrowingStatus;
use App\Models\Borrowing;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BorrowingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('borrowed_at', direction: 'desc')
            ->columns([
                TextColumn::make('borrowed_at')
                    ->label(__('borrowing.table.borrowed_at'))
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('due_at')
                    ->label(__('borrowing.table.due_at'))
                    ->date('j M Y')
                    ->sortable()
                    ->icon(function (Borrowing $record): string | null {
                        if ($record->overdue) {
                            return 'heroicon-o-exclamation-triangle';
                        }
                        return null;
                    })
                    ->iconColor('primary'),
                TextColumn::make('returned_at')
                    ->label(__('borrowing.table.returned_at'))
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label(__('borrowing.table.items_count'))
                    ->alignCenter()
                    ->numeric(),
                TextColumn::make('status')
                    ->label(__('borrowing.table.status'))
                    ->badge(),
                TextColumn::make('toLocation.name')
                    ->label(__('borrowing.table.to_location'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('toDepartment.name')
                    ->label(__('borrowing.table.to_department'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('toRoom.name')
                    ->label(__('borrowing.table.to_room'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('borrowing.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('borrowing.table.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('borrowing.filters.status'))
                    ->multiple()
                    ->options(BorrowingStatus::class),
                TernaryFilter::make('overdue')
                    ->label(__('borrowing.filters.overdue'))
                    ->native(false)
                    ->placeholder(__('borrowing.filters.overdue_placeholder'))
                    ->trueLabel(__('borrowing.filters.overdue_true'))
                    ->falseLabel(__('borrowing.filters.overdue_false'))
                    ->queries(
                        true: fn(Builder $query) => $query->where(function (Builder $q) {
                            return $q->whereNull('returned_at')->where('due_at', '<', now()->startOfDay());
                        })->orWhere(function (Builder $q) {
                            return $q->whereNotNull('returned_at')->whereColumn('returned_at', '>', 'due_at');
                        }),
                        false: fn(Builder $query) => $query->where(function (Builder $q) {
                            return $q->whereNull('returned_at')->where('due_at', '>=', now()->startOfDay());
                        })->orWhere(function (Builder $q) {
                            return $q->whereNotNull('returned_at')->whereColumn('returned_at', '<=', 'due_at');
                        }),
                        blank: fn(Builder $query) => $query,
                    ),
                TrashedFilter::make()
                    ->native(false),
            ])
            ->filtersFormColumns(3)
            ->recordActions([
                ViewAction::make()
                    ->hiddenLabel(),
                EditAction::make()
                    ->hiddenLabel(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords('delete')
                        ->action(fn(Collection $records) => $records->each->delete()),
                    ForceDeleteBulkAction::make()
                        ->authorizeIndividualRecords('forceDelete')
                        ->action(function (Collection $records) {
                            $records->each(function (Borrowing $borrowing) {
                                $borrowing->items()->forceDelete();
                                $borrowing->forceDelete();
                            });
                        }),
                    RestoreBulkAction::make()
                        ->authorizeIndividualRecords('restore')
                        ->action(fn(Collection $records) => $records->each->restore()),
                ]),
            ]);
    }
}
