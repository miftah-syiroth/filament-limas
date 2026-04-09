<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplierForm
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
                            ->label(__('supplier.form.name'))
                            ->required(),
                        Select::make('country')
                            ->label(__('supplier.form.country'))
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
                            ->label(__('supplier.form.province'))
                            ->options(fn(Get $get): array => Province::query()
                                ->where('country_code', $get('country'))
                                ->pluck('name', 'code')
                                ->toArray())
                            ->searchable()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn(Select $component) => $component
                                ->getContainer()
                                ->getComponent('city')
                                ->state(null)),
                        Select::make('city')
                            ->label(__('supplier.form.city'))
                            ->options(fn(Get $get): array => City::query()
                                ->where('province_code', $get('province'))
                                ->pluck('name', 'code')
                                ->toArray())
                            ->searchable()
                            ->native(false),
                        TextInput::make('address')
                            ->label(__('supplier.form.address')),
                        TextInput::make('address2')
                            ->label(__('supplier.form.address2')),
                        TextInput::make('zip')
                            ->label(__('supplier.form.zip')),
                        TextInput::make('phone')
                            ->label(__('supplier.form.phone'))
                            ->tel(),
                        TextInput::make('email')
                            ->label(__('supplier.form.email'))
                            ->email(),
                        TextInput::make('url')
                            ->label(__('supplier.form.url'))
                            ->url(),
                        Textarea::make('notes')
                            ->label(__('supplier.form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
