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
                Section::make(__('borrowing.infolist.section_general'))
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 3,
                    ])
                    ->schema([
                        TextEntry::make('user.name')
                            ->label(__('borrowing.infolist.borrower')),
                        TextEntry::make('to_location.name')
                            ->label(__('borrowing.infolist.to_location')),
                        TextEntry::make('to_department.name')
                            ->label(__('borrowing.infolist.to_department')),
                        TextEntry::make('to_room.name')
                            ->label(__('borrowing.infolist.to_room')),
                        TextEntry::make('status')
                            ->label(__('borrowing.infolist.status'))
                            ->badge(),
                        Fieldset::make(__('borrowing.infolist.fieldset_dates'))
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(3)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextEntry::make('borrowed_at')
                                            ->label(__('borrowing.infolist.borrowed_at'))
                                            ->dateTime('j M Y'),
                                        TextEntry::make('due_at')
                                            ->label(__('borrowing.infolist.due_at'))
                                            ->dateTime('j M Y'),
                                        TextEntry::make('returned_at')
                                            ->label(__('borrowing.infolist.returned_at'))
                                            ->dateTime('j M Y')
                                            ->placeholder('-'),
                                    ]),
                            ]),
                        TextEntry::make('notes')
                            ->label(__('borrowing.infolist.notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
