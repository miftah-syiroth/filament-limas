<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('user.infolist.name')),
                        TextEntry::make('email')
                            ->label(__('user.infolist.email')),
                        TextEntry::make('email_verified_at')
                            ->label(__('user.infolist.email_verified_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label(__('user.infolist.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        // TextEntry::make('two_factor_secret')
                        //     ->label(__('user.infolist.two_factor_secret'))
                        //     ->placeholder('-'),
                        // TextEntry::make('two_factor_recovery_codes')
                        //     ->label(__('user.infolist.two_factor_recovery_codes'))
                        //     ->placeholder('-'),
                        // TextEntry::make('two_factor_confirmed_at')
                        //     ->label(__('user.infolist.two_factor_confirmed_at'))
                        //     ->dateTime()
                        //     ->placeholder('-'),
                    ]),
            ]);
    }
}
