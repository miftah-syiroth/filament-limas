<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Borrowings\BorrowingResource;
use App\Filament\Resources\Items\ItemResource;
use App\Models\BorrowingItem;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ManageBorrowingItems extends ManageRelatedRecords
{
    protected static string $resource = ItemResource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string $relationship = 'borrowingItems';

    protected static ?string $navigationLabel = 'Peminjaman';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('checked_out_at', direction: 'desc')
            ->columns([
                TextColumn::make('borrowing.user.name')
                    ->label('Peminjam')
                    ->searchable(),
                TextColumn::make('borrowing.due_at')
                    ->label('Batas Peminjaman')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('borrowing.status')
                    ->label('Status')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('checked_out_at')
                    ->label('Tanggal Keluar')
                    ->dateTime('j M Y')
                    ->sortable(),
                TextColumn::make('condition_out')
                    ->label('Kondisi Keluar')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('checked_in_at')
                    ->label('Tanggal Masuk')
                    ->dateTime('j M Y'),
                TextColumn::make('condition_in')
                    ->label('Kondisi Masuk')
                    ->badge()
                    ->color('primary')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->alignCenter(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Peminjaman')
                    ->icon('heroicon-o-eye')
                    ->url(fn (BorrowingItem $record): string => BorrowingResource::getUrl('view', ['record' => $record->borrowing->id])),
            ])
            ->filters([
                TrashedFilter::make(),
            ]);
    }
}
