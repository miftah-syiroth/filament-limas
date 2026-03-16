<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BorrowingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User'),
                        TextEntry::make('status')
                            ->badge(),
                        Fieldset::make('Tanggal')
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(3)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('borrowed_at')
                                            ->label('Tanggal Peminjaman')
                                            ->dateTime('j M Y'),
                                        TextEntry::make('due_at')
                                            ->label('Batas Peminjaman')
                                            ->dateTime('j M Y'),
                                        TextEntry::make('returned_at')
                                            ->label('Tanggal Pengembalian')
                                            ->dateTime('j M Y')
                                            ->placeholder('-'),
                                    ]),
                            ]),
                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
