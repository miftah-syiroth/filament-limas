<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizationForm
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
                            ->label(__('organization.form.name'))
                            ->required()
                            ->string(),
                        TextInput::make('email')
                            ->label(__('organization.form.email'))
                            ->nullable()
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('organization.form.phone'))
                            ->nullable()
                            ->tel()
                            ->maxLength(15),
                        Textarea::make('notes')
                            ->label(__('organization.form.notes'))
                            ->columnSpanFull()
                            ->nullable()
                            ->maxLength(255),
                    ]),
            ]);
    }
}
