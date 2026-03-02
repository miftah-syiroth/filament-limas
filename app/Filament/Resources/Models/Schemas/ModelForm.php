<?php

namespace App\Filament\Resources\Models\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('model_number'),
                TextInput::make('min_amount')
                    ->numeric(),
                TextInput::make('end_of_life')
                    ->numeric(),
                Toggle::make('require_serial_number')
                    ->required(),
                TextInput::make('manufacture_id'),
                TextInput::make('category_id'),
                TextInput::make('deprecation_id'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
