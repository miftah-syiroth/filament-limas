<?php

namespace App\Filament\Resources\Depreciations\Schemas;

use App\Enums\DepreciationMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class DepreciationForm
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
                            ->label(__('depreciation.form.name'))
                            ->required(),
                        TextInput::make('months')
                            ->label(__('depreciation.form.months'))
                            ->required()
                            ->minValue(1)
                            ->numeric(),
                        TextInput::make('minimum_value')
                            ->label(__('depreciation.form.minimum_value'))
                            ->belowContent([
                                Icon::make(Heroicon::OutlinedInformationCircle),
                                __('depreciation.form.minimum_value_helper')
                            ])
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('%'),
                        Select::make('method')
                            ->label(__('depreciation.form.method'))
                            ->options(DepreciationMethod::class)
                            ->native(false)
                            ->default(DepreciationMethod::Amount->value)
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('depreciation.form.notes'))
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
