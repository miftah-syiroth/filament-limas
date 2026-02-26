<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name'),
                Select::make('location_id')
                    ->relationship('location', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
