<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('organization.name')
                            ->label(__('location.infolist.organization'))
                            ->placeholder('-'),
                        TextEntry::make('name')
                            ->label(__('location.infolist.name')),
                        TextEntry::make('address')
                            ->label(__('location.infolist.address'))
                            ->placeholder('-'),
                        TextEntry::make('address2')
                            ->label(__('location.infolist.address2'))
                            ->placeholder('-'),
                        TextEntry::make('relationCity.name')
                            ->label(__('location.infolist.city'))
                            ->placeholder('-'),
                        TextEntry::make('relationProvince.name')
                            ->label(__('location.infolist.province'))
                            ->placeholder('-'),
                        TextEntry::make('relationCountry.name')
                            ->label(__('location.infolist.country'))
                            ->placeholder('-'),
                        TextEntry::make('zip')
                            ->label(__('location.infolist.zip'))
                            ->placeholder('-'),
                        TextEntry::make('phone')
                            ->label(__('location.infolist.phone'))
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->label(__('location.infolist.notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
