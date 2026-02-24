<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('address'),
                TextInput::make('address2'),
                TextInput::make('city'),
                TextInput::make('state'),
                TextInput::make('country'),
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
