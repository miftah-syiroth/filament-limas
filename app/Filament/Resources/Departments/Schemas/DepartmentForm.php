<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Location;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('location_id')
                            ->label(__('department.form.location'))
                            ->relationship('location', 'name')
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state): void {
                                $location = Location::find($state);
                                $set('oranization_id', $location?->oranization_id);
                            }),
                        Select::make('oranization_id')
                            ->label(__('department.form.oranization'))
                            ->relationship('oranization', 'name')
                            ->disabled()
                            ->dehydrated()
                            ->native(false),
                        TextInput::make('name')
                            ->label(__('department.form.name'))
                            ->required(),
                        TextInput::make('phone')
                            ->label(__('department.form.phone'))
                            ->tel(),
                        Textarea::make('notes')
                            ->label(__('department.form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
