<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required()
                    ->native(false),
                TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                TextInput::make('address')
                    ->required(),
                TextInput::make('address2'),
                Select::make('country')
                    ->options(function () {
                        $json = file_get_contents(resource_path('data/countries.json'));

                        return json_decode($json, true);
                    })
                    ->searchable()
                    ->native(false),
                TextInput::make('state'),
                TextInput::make('city'),
                TextInput::make('zip')
                    ->numeric(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
