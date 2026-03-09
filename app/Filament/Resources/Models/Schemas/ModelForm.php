<?php

namespace App\Filament\Resources\Models\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('manufacture_id')
                    ->relationship('manufacture', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('model_number'),
                TextInput::make('min_amount')
                    ->label('Minimal Stock')
                    ->numeric()
                    ->belowContent('Jumlah stock minimal yang harus ada.'),
                TextInput::make('end_of_life')
                    ->label('Masa Pakai')
                    ->numeric()
                    ->minValue(1)
                    ->suffix('bulan'),
                Select::make('deprecation_id')
                    ->relationship('deprecation', 'name')
                    ->searchable()
                    ->preload()
                    ->default(null),
                TextInput::make('audit_interval')
                    ->label('Interval Audit')
                    ->numeric()
                    ->minValue(1)
                    ->suffix('bulan'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
