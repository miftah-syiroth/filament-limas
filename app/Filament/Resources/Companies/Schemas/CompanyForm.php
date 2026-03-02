<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->string(),
                TextInput::make('email')
                    ->label('Email address')
                    ->nullable()
                    ->email(),
                TextInput::make('phone')
                    ->nullable()
                    ->tel()
                    ->maxLength(15),
                Textarea::make('notes')
                    ->columnSpanFull()
                    ->nullable()
                    ->maxLength(255),
            ]);
    }
}
