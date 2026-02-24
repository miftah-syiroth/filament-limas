<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('address'),
                TextInput::make('address2'),
                TextInput::make('city'),
                TextInput::make('state'),
                TextInput::make('country'),
                TextInput::make('zip'),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
