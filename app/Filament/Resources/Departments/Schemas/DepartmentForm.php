<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Location;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('location_id')
                    ->relationship('location', 'name')
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state): void {
                        $location = Location::find($state);
                        $set('company_id', $location?->company_id);
                    }),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->disabled()
                    ->dehydrated()
                    ->native(false),
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
