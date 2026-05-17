<?php

namespace App\Filament\Resources\Locations\Schemas;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('organization_id')
                            ->label(__('location.form.organization'))
                            ->relationship('organization', 'name')
                            ->required()
                            ->native(false),
                        TextInput::make('name')
                            ->label(__('location.form.name'))
                            ->required()
                            ->maxLength(50),
                        Select::make('country')
                            ->label(__('location.form.country'))
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
                            ->label(__('location.form.province'))
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
                            ->label(__('location.form.city'))
                            ->options(fn (Get $get): array => City::query()
                                ->where('province_code', $get('province'))
                                ->pluck('name', 'code')
                                ->toArray())
                            ->searchable()
                            ->native(false),
                        TextInput::make('address')
                            ->label(__('location.form.address'))
                            ->required(),
                        TextInput::make('address2')
                            ->label(__('location.form.address2')),
                        TextInput::make('zip')
                            ->label(__('location.form.zip'))
                            ->numeric(),
                        TextInput::make('phone')
                            ->label(__('location.form.phone'))
                            ->tel(),
                        Textarea::make('notes')
                            ->label(__('location.form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
