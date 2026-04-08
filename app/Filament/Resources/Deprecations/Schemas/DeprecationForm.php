<?php

namespace App\Filament\Resources\Deprecations\Schemas;

use App\Enums\DeprecationMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeprecationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('deprecation.form.name'))
                            ->required(),
                        TextInput::make('months')
                            ->label(__('deprecation.form.months'))
                            ->required()
                            ->minValue(1)
                            ->numeric(),
                        TextInput::make('minimum_value')
                            ->label(__('deprecation.form.minimum_value'))
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('%'),
                        Select::make('method')
                            ->label(__('deprecation.form.method'))
                            ->options(DeprecationMethod::class)
                            ->native(false)
                            ->default(DeprecationMethod::Amount->value)
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('deprecation.form.notes'))
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
