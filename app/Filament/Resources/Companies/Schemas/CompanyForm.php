<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
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
                            ->label(__('company.form.name'))
                            ->required()
                            ->string(),
                        TextInput::make('email')
                            ->label(__('company.form.email'))
                            ->nullable()
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('company.form.phone'))
                            ->nullable()
                            ->tel()
                            ->maxLength(15),
                        Textarea::make('notes')
                            ->label(__('company.form.notes'))
                            ->columnSpanFull()
                            ->nullable()
                            ->maxLength(255),
                    ])
            ]);
    }
}
