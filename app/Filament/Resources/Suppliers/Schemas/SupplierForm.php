<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('country')
                    ->label('Country')
                    ->options(Country::query()->pluck('name', 'code'))
                    ->default('ID')
                    ->disabled()
                    ->dehydrated()
                    ->searchable()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Select $component) {
                        $component->getContainer()
                            ->getComponent('province')
                            ->state(null);
                        $component->getContainer()
                            ->getComponent('city')
                            ->state(null);
                    }),
                Select::make('province')
                    ->label('Province')
                    ->options(fn (Get $get): array => Province::query()
                        ->where('country_code', $get('country'))
                        ->pluck('name', 'code')
                        ->toArray())
                    ->searchable()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn (Select $component) => $component
                        ->getContainer()
                        ->getComponent('city')
                        ->state(null)),
                Select::make('city')
                    ->label('City')
                    ->options(fn (Get $get): array => City::query()
                        ->where('province_code', $get('province'))
                        ->pluck('name', 'code')
                        ->toArray())
                    ->searchable()
                    ->native(false),
                TextInput::make('address'),
                TextInput::make('address2'),
                TextInput::make('zip'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('url')
                    ->url(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
