<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrganizationInfolist
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
                            ->label(__('organization.infolist.name')),
                        TextEntry::make('email')
                            ->label(__('organization.infolist.email')),
                        TextEntry::make('phone')
                            ->label(__('organization.infolist.phone')),
                        TextEntry::make('notes')
                            ->label(__('organization.infolist.notes')),

                    ]),
            ]);
    }
}
