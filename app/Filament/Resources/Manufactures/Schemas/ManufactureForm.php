<?php

namespace App\Filament\Resources\Manufactures\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ManufactureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('url')
                    ->url(),
                TextInput::make('support_url')
                    ->url(),
                TextInput::make('support_phone')
                    ->tel(),
                TextInput::make('support_email')
                    ->email(),
                TextInput::make('warranty_lookup_url')
                    ->url(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
