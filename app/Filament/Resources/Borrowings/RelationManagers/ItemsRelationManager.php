<?php

namespace App\Filament\Resources\Borrowings\RelationManagers;

use App\Filament\Infolists\Components\QrCodeEntry;
use App\Models\BorrowingItem;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Items')
            ->columns([
                TextColumn::make('item.serial_number')
                    ->label('Tipe'),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('checked_out_at')
                    ->label('Tanggal Keluar')
                    ->dateTime('j M Y'),
                TextColumn::make('condition_out')
                    ->label('Kondisi Keluar')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('checked_in_at')
                    ->label('Tanggal Masuk')
                    ->dateTime('j M Y'),
                TextColumn::make('condition_in')
                    ->label('Kondisi Masuk')
                    ->badge()
                    ->color('primary'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->modalSubmitAction(false)
                    ->modalWidth('md')
                    ->schema([
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(1)
                                    ->schema([
                                        QrCodeEntry::make('qr_code')
                                            ->state(fn (BorrowingItem $record): string => $record->item->serial_number)
                                            ->hiddenLabel(),
                                    ])
                                    ->columnSpan(1),
                                Grid::make(1)
                                    ->schema([
                                        TextEntry::make('item.serial_number')
                                            ->label('Serial Number')
                                            ->badge(),
                                        TextEntry::make('quantity')
                                            ->label('Jumlah')
                                            ->numeric(),
                                    ])
                                    ->columnSpan(1),
                            ]),
                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                Fieldset::make('Keluar')
                                    ->columns(1)
                                    ->schema([
                                        TextEntry::make('checked_out_at')
                                            ->label('Tanggal')
                                            ->dateTime('j M Y'),
                                        TextEntry::make('condition_out')
                                            ->hiddenLabel()
                                            ->badge()
                                            ->color('gray'),
                                    ]),
                                Fieldset::make('Masuk')
                                    ->schema([
                                        TextEntry::make('checked_in_at')
                                            ->label('Tanggal')
                                            ->dateTime('j M Y'),
                                        TextEntry::make('condition_in')
                                            ->hiddenLabel()
                                            ->badge()
                                            ->color('primary'),
                                    ]),
                            ]),
                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
