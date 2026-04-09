<?php

namespace App\Filament\Resources\Manufactures\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class ManufactureForm
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
                            ->label(__('manufacture.form.name'))
                            ->required(),
                        TextInput::make('url')
                            ->label(__('manufacture.form.url'))
                            ->url(),
                        TextInput::make('support_url')
                            ->label(__('manufacture.form.support_url'))
                            ->url(),
                        TextInput::make('support_phone')
                            ->label(__('manufacture.form.support_phone'))
                            ->tel(),
                        TextInput::make('support_email')
                            ->label(__('manufacture.form.support_email'))
                            ->email(),
                        TextInput::make('warranty_lookup_url')
                            ->label(__('manufacture.form.warranty_lookup_url'))
                            ->url(),
                        Textarea::make('notes')
                            ->label(__('manufacture.form.notes'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
