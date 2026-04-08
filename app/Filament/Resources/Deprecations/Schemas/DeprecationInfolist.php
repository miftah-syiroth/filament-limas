<?php

namespace App\Filament\Resources\Deprecations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeprecationInfolist
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
                            ->label(__('deprecation.infolist.name')),
                        TextEntry::make('months')
                            ->label(__('deprecation.infolist.months'))
                            ->numeric(),
                        TextEntry::make('minimum_value')
                            ->label(__('deprecation.infolist.minimum_value'))
                            ->numeric(),
                        TextEntry::make('method')
                            ->label(__('deprecation.infolist.method')),
                        TextEntry::make('created_at')
                            ->label(__('deprecation.infolist.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('deprecation.infolist.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                    ])
            ]);
    }
}
