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
use Filament\Tables\Filters\TrashedFilter;
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
                    ->searchable(),
                TextColumn::make('borrowed_at')
                    ->label('Tanggal Peminjaman')
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('due_at')
                    ->label('Batas Peminjaman')
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('returned_at')
                    ->label('Tanggal Pengembalian')
                    ->date('j M Y')
                    ->sortable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')
                    ->alignCenter()
                    ->numeric(),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('overdue')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options(BorrowingStatus::class),
                TernaryFilter::make('overdue')
                    ->label('Terlambat')
                    ->placeholder('Semua')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak')
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
                TrashedFilter::make(),
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
