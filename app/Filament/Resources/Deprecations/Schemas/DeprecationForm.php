<?php

namespace App\Filament\Resources\Deprecations\Schemas;

use App\Enums\DeprecationMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    ->label('Lama Penggunaan (bulan)')
                    ->required()
                    ->minValue(1)
                    ->numeric(),
                TextInput::make('minimum_value')
                    ->label('Nilai Minimum')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->suffix('%'),
                Select::make('method')
                    ->options(DeprecationMethod::class)
                    ->native(false)
                    ->default(DeprecationMethod::Amount->value)
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
