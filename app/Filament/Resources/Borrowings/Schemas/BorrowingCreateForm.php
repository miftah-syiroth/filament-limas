<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BorrowingCreateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('borrowing.form.section_borrower'))
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('borrowed_at')
                            ->label(__('borrowing.form.borrowed_at'))
                            ->required()
                            ->default(now()->format('m/d/Y')),
                        DatePicker::make('due_at')
                            ->label(__('borrowing.form.due_at'))
                            ->required(),
                        Select::make('to_location_id')
                            ->label(__('borrowing.form.to_location'))
                            ->relationship('toLocation', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('to_department_id')
                            ->label(__('borrowing.form.to_department'))
                            ->relationship('toDepartment', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('to_room_id')
                            ->label(__('borrowing.form.to_room'))
                            ->relationship('toRoom', 'name')
                            ->searchable()
                            ->preload(),
                        Textarea::make('notes')
                            ->label(__('borrowing.form.notes')),
                    ]),
            ]);
    }
}
