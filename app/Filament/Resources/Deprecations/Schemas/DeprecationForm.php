<?php

namespace App\Filament\Resources\Deprecations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeprecationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('months')
                    ->required()
                    ->numeric(),
                TextInput::make('depreciation_min')
                    ->required()
                    ->numeric(),
                TextInput::make('depreciation_type')
                    ->required(),
            ]);
    }
}
