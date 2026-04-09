<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
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
                            ->label(__('user.form.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('user.form.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(__('user.form.password'))
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->confirmed()
                            ->rule(Password::default())
                            ->maxLength(255),
                        TextInput::make('password_confirmation')
                            ->label(__('user.form.password_confirmation'))
                            ->password()
                            ->dehydrated(false)
                            ->required(fn(string $operation): bool => $operation === 'create'),
                        Toggle::make('email_verified_at')
                            ->label(__('user.form.email_verified'))
                            ->dehydrateStateUsing(fn(bool $state): ?string => $state ? now()->toDateTimeString() : null)
                            ->formatStateUsing(fn($record): bool => $record?->email_verified_at !== null),
                    ]),
            ]);
    }
}
