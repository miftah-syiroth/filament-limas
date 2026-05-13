<?php

namespace App\Filament\Resources\Borrowings\Tables;

use App\Enums\BorrowingStatus;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BorrowingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('borrowed_at', direction: 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('borrowing.table.borrower'))
                    ->searchable(),
                TextColumn::make('borrowed_at')
                    ->label(__('borrowing.table.borrowed_at'))
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('due_at')
                    ->label(__('borrowing.table.due_at'))
                    ->date('j M Y')
                    ->sortable(),
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
                IconColumn::make('overdue')
                    ->label(__('borrowing.table.overdue'))
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('borrowing.table.created_at'))
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
                    ->placeholder(__('borrowing.filters.overdue_placeholder'))
                    ->trueLabel(__('borrowing.filters.overdue_true'))
                    ->falseLabel(__('borrowing.filters.overdue_false'))
                    ->queries(
                        true: fn (Builder $query) => $query->where(function (Builder $q) {
                            return $q->whereNull('returned_at')->where('due_at', '<', now()->startOfDay());
                        })->orWhere(function (Builder $q) {
                            return $q->whereNotNull('returned_at')->whereColumn('returned_at', '>', 'due_at');
                        }),
                        false: fn (Builder $query) => $query->where(function (Builder $q) {
                            return $q->whereNull('returned_at')->where('due_at', '>=', now()->startOfDay());
                        })->orWhere(function (Builder $q) {
                            return $q->whereNotNull('returned_at')->whereColumn('returned_at', '<=', 'due_at');
                        }),
                        blank: fn (Builder $query) => $query,
                    ),

            ])
            ->recordActions([
                ViewAction::make()
                    ->hiddenLabel(),
                EditAction::make()
                    ->hiddenLabel(),
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
