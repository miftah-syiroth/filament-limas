<?php

namespace App\Filament\Resources\Rooms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('location_id')
                    ->label(__('room.form.location'))
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                TextInput::make('name')
                    ->label(__('room.form.name'))
                    ->required(),
                TextInput::make('capacity')
                    ->label(__('room.form.capacity'))
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Textarea::make('notes')
                    ->label(__('room.form.notes'))
                    ->columnSpanFull(),
            ]);
    }
}
